<?php

use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

new class extends Component
{
    use ProfileValidationRules;
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public ?TemporaryUploadedFile $photo = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();
        $previousProfilePhotoPath = $user->profile_photo_path;
        $newProfilePhotoPath = null;

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($this->photo) {
            $newProfilePhotoPath = $this->photo->store('profile-photos', 'public');

            if ($newProfilePhotoPath === false) {
                $this->addError('photo', __('Unable to store the profile photo. Please try again.'));
                return;
            }

            $user->profile_photo_path = $newProfilePhotoPath;
            $this->photo = null;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        try {
            $user->save();
        } catch (\Exception $e) {
            // If save fails, clean up the newly stored file to prevent orphaning
            if ($newProfilePhotoPath !== null) {
                Storage::disk('public')->delete($newProfilePhotoPath);
            }

            throw $e;
        }

        if ($newProfilePhotoPath !== null && $previousProfilePhotoPath) {
            Storage::disk('public')->delete($previousProfilePhotoPath);
        }

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        /** @phpstan-ignore-next-line */
        return ! Auth::user() instanceof MustVerifyEmail || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile Settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Profile')" :subheading="__('Update your name, email address and profile picture')">
        <form wire:submit="updateProfileInformation" class="w-full my-6 space-y-6">
            <div class="flex items-center gap-10">
                <flux:input wire:model="photo" :label="__('Profile photo')" type="file" accept="image/*" />
                <flux:avatar
                    :src="$photo && $photo->isPreviewable() ? $photo->temporaryUrl() : (Auth::user()->profile_photo_path ? Storage::disk('public')->url(Auth::user()->profile_photo_path) : null)"
                    :name="($photo && $photo->isPreviewable()) || Auth::user()->profile_photo_path ? null : Auth::user()->name"
                    circle
                    size="xl"
                />
            </div>

                <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 text-green-600!">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-profile-button">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:pages::settings.profile.delete-user-form />
        @endif
    </x-pages::settings.layout>
</section>
