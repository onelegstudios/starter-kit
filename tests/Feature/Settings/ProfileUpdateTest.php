<?php
namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use RuntimeException;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $this->actingAs($user = User::factory()->create());

        $this->get(route('profile.edit'))->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = Livewire::test('pages::settings.profile')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->call('updateProfileInformation');

        $response->assertHasNoErrors();

        $user->refresh();

        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = Livewire::test('pages::settings.profile')
            ->set('name', 'Test User')
            ->set('email', $user->email)
            ->call('updateProfileInformation');

        $response->assertHasNoErrors();

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_profile_photo_can_be_updated(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user);

        $photo = UploadedFile::fake()->image('profile.jpg');

        $response = Livewire::test('pages::settings.profile')
            ->set('photo', $photo)
            ->call('updateProfileInformation');

        $response->assertHasNoErrors();

        $user->refresh();

        $this->assertNotNull($user->profile_photo_path);
        Storage::disk('public')->assertExists($user->profile_photo_path);
    }

    public function test_temporary_profile_photo_preview_is_shown_before_saving(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user);

        $photo = UploadedFile::fake()->image('profile-preview.jpg');

        $response = Livewire::test('pages::settings.profile')
            ->set('photo', $photo);

        $previewUrl = $response->get('photo')->temporaryUrl();

        $response->assertSee($previewUrl);
        $this->assertNull($user->refresh()->profile_photo_path);
    }

    public function test_user_menu_refreshes_after_profile_update_event(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name'               => 'Original User',
            'profile_photo_path' => null,
        ]);

        $this->actingAs($user);

        $response = Livewire::test('layouts.user-menu')
            ->assertSet('name', 'Original User')
            ->assertSet('profilePhotoPath', null);

        Storage::disk('public')->put('profile-photos/updated-photo.jpg', 'updated-photo-content');

        $user->forceFill([
            'name'               => 'Updated User',
            'profile_photo_path' => 'profile-photos/updated-photo.jpg',
        ])->save();

        $response
            ->dispatch('profile-updated')
            ->assertSet('name', 'Updated User')
            ->assertSet('profilePhotoPath', 'profile-photos/updated-photo.jpg')
            ->assertSee(Storage::disk('public')->url('profile-photos/updated-photo.jpg'));
    }

    public function test_old_profile_photo_is_deleted_when_uploading_a_new_one(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('profile-photos/old-photo.jpg', 'old-photo-content');

        $user = User::factory()->create([
            'profile_photo_path' => 'profile-photos/old-photo.jpg',
        ]);

        $this->actingAs($user);

        $photo = UploadedFile::fake()->image('new-profile.jpg');

        $response = Livewire::test('pages::settings.profile')
            ->set('photo', $photo)
            ->call('updateProfileInformation');

        $response->assertHasNoErrors();

        $user->refresh();

        $this->assertNotEquals('profile-photos/old-photo.jpg', $user->profile_photo_path);
        Storage::disk('public')->assertMissing('profile-photos/old-photo.jpg');
        Storage::disk('public')->assertExists($user->profile_photo_path);
    }

    public function test_old_profile_photo_is_preserved_when_saving_fails(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('profile-photos/old-photo.jpg', 'old-photo-content');

        $user = User::factory()->create([
            'profile_photo_path' => 'profile-photos/old-photo.jpg',
        ]);

        $mockUser = \Mockery::mock($user)->makePartial();
        $mockUser->shouldReceive('save')->once()->andThrow(new RuntimeException('Unable to save user.'));

        /** @var User&\Mockery\LegacyMockInterface&\Mockery\MockInterface $mockUser */
        $this->actingAs($mockUser);

        $photo = UploadedFile::fake()->image('new-profile.jpg');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to save user.');

        Livewire::test('pages::settings.profile')
            ->set('photo', $photo)
            ->call('updateProfileInformation');

        Storage::disk('public')->assertExists('profile-photos/old-photo.jpg');

        $files = Storage::disk('public')->allFiles('profile-photos');
        $this->assertCount(1, $files);
        $this->assertEquals('profile-photos/old-photo.jpg', $files[0]);
    }

    public function test_profile_photo_must_be_an_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user);

        $photo = UploadedFile::fake()->create('profile.pdf', 100, 'application/pdf');

        $response = Livewire::test('pages::settings.profile')
            ->set('photo', $photo)
            ->call('updateProfileInformation');

        $response->assertHasErrors(['photo']);

        $this->assertNull($user->refresh()->profile_photo_path);
        Storage::disk('public')->assertDirectoryEmpty('profile-photos');
    }

    public function test_profile_photo_must_not_exceed_max_size(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user);

        $photo = UploadedFile::fake()->image('too-large.jpg')->size(3000);

        $response = Livewire::test('pages::settings.profile')
            ->set('photo', $photo)
            ->call('updateProfileInformation');

        $response->assertHasErrors(['photo' => ['max']]);

        $this->assertNull($user->refresh()->profile_photo_path);
        Storage::disk('public')->assertDirectoryEmpty('profile-photos');
    }

    public function test_existing_profile_photo_is_preserved_when_no_new_upload_is_provided(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('profile-photos/existing.jpg', 'existing-photo-content');

        $user = User::factory()->create([
            'name'               => 'Original Name',
            'email'              => 'original@example.com',
            'profile_photo_path' => 'profile-photos/existing.jpg',
        ]);

        $this->actingAs($user);

        $response = Livewire::test('pages::settings.profile')
            ->set('name', 'Updated Name')
            ->set('email', 'updated@example.com')
            ->call('updateProfileInformation');

        $response->assertHasNoErrors();

        $user->refresh();

        $this->assertEquals('profile-photos/existing.jpg', $user->profile_photo_path);
        Storage::disk('public')->assertExists('profile-photos/existing.jpg');
    }

    public function test_profile_photo_can_be_replaced_even_when_previous_file_is_missing(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'profile_photo_path' => 'profile-photos/missing-old.jpg',
        ]);

        $this->actingAs($user);

        $photo = UploadedFile::fake()->image('replacement.jpg');

        $response = Livewire::test('pages::settings.profile')
            ->set('photo', $photo)
            ->call('updateProfileInformation');

        $response->assertHasNoErrors();

        $user->refresh();

        $this->assertNotEquals('profile-photos/missing-old.jpg', $user->profile_photo_path);
        Storage::disk('public')->assertExists($user->profile_photo_path);
        Storage::disk('public')->assertMissing('profile-photos/missing-old.jpg');
    }

    public function test_non_previewable_temporary_profile_photo_does_not_break_avatar_rendering(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('profile-photos/existing.jpg', 'existing-photo-content');

        $user = User::factory()->create([
            'name'               => 'Profile User',
            'profile_photo_path' => 'profile-photos/existing.jpg',
        ]);

        $this->actingAs($user);

        $photo = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = Livewire::test('pages::settings.profile')
            ->set('photo', $photo);

        $response->assertSee(Storage::disk('public')->url('profile-photos/existing.jpg'));
        $this->assertEquals('profile-photos/existing.jpg', $user->refresh()->profile_photo_path);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = Livewire::test('pages::settings.delete-user-modal')
            ->set('password', 'password')
            ->call('deleteUser');

        $response
            ->assertHasNoErrors()
            ->assertRedirect('/');

        $this->assertNull($user->fresh());
        $this->assertFalse(auth()->check());
    }

    public function test_profile_photo_is_deleted_when_user_deletes_their_account(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('profile-photos/to-delete.jpg', 'photo-content');

        $user = User::factory()->create([
            'profile_photo_path' => 'profile-photos/to-delete.jpg',
        ]);

        $this->actingAs($user);

        $response = Livewire::test('pages::settings.delete-user-modal')
            ->set('password', 'password')
            ->call('deleteUser');

        $response
            ->assertHasNoErrors()
            ->assertRedirect('/');

        Storage::disk('public')->assertMissing('profile-photos/to-delete.jpg');
        $this->assertNull($user->fresh());
    }

    public function test_warning_is_logged_when_profile_photo_deletion_fails_during_account_deletion(): void
    {
        $user = User::factory()->create([
            'profile_photo_path' => 'profile-photos/missing.jpg',
        ]);

        Log::shouldReceive('warning')
            ->once()
            ->withArgs(function (string $message, array $context) use ($user): bool {
                return $message === 'Failed to delete profile photo on account deletion'
                && ($context['path'] ?? null) === 'profile-photos/missing.jpg'
                && ($context['user_id'] ?? null) === $user->getKey();
            });

        Storage::shouldReceive('disk->delete')
            ->once()
            ->with('profile-photos/missing.jpg')
            ->andReturnFalse();

        $this->actingAs($user);

        $response = Livewire::test('pages::settings.delete-user-modal')
            ->set('password', 'password')
            ->call('deleteUser');

        $response
            ->assertHasNoErrors()
            ->assertRedirect('/');

        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = Livewire::test('pages::settings.delete-user-modal')
            ->set('password', 'wrong-password')
            ->call('deleteUser');

        $response->assertHasErrors(['password']);

        $this->assertNotNull($user->fresh());
    }
}
