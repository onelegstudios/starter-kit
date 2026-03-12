<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public string $align = 'start';

    public string $position = 'bottom';

    public bool $circle = true;

    public string $name = '';

    public string $email = '';

    public ?string $profilePhotoPath = null;

    public function mount(string $align = 'start', string $position = 'bottom', bool $circle = true): void
    {
        $this->align    = $align;
        $this->position = $position;
        $this->circle   = $circle;

        $this->syncAuthenticatedUser();
    }

    #[On('profile-updated')]
    public function syncAuthenticatedUser(): void
    {
        $user = Auth::user();

        abort_unless($user instanceof User, 403);

        $freshUser = $user->fresh();

        if ($freshUser instanceof User) {
            $user = $freshUser;
        }

        $this->name             = $user->name;
        $this->email            = $user->email;
        $this->profilePhotoPath = $user->profile_photo_path;
    }

    #[Computed]
    public function avatarUrl(): ?string
    {
        if ($this->profilePhotoPath === null) {
            return null;
        }

        return Storage::disk('public')->url($this->profilePhotoPath);
    }

    #[Computed]
    public function initials(): string
    {
        return (new User(['name' => $this->name]))->initials();
    }
};
?>

<flux:dropdown align="{{ $align }}" position="{{ $position }}">
    <flux:profile
        :circle="$circle"
        :avatar="$this->avatarUrl"
        :initials="$this->initials"
    />

    <flux:menu>
        <div class="grid items-center gap-2 px-1 py-1.5 text-start text-sm">
            <div class="flex items-center gap-2">
                <flux:avatar
                    circle
                    :src="$this->avatarUrl"
                    :name="$profilePhotoPath ? null : $name"
                />
                <div class="grid flex-1 text-start text-sm leading-tight">
                    <flux:heading class="truncate">{{ $name }}</flux:heading>
                    <flux:text class="truncate">{{ $email }}</flux:text>
                </div>
            </div>
        </div>
        <flux:menu.separator />
        <flux:menu.item :href="route('profile.edit')" icon="settings" wire:navigate>
            {{ __('Settings') }}
        </flux:menu.item>
        <flux:menu.separator />
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <flux:menu.item as="button" type="submit" icon="log-out" class="w-full cursor-pointer" data-test="logout-button">
                {{ __('Log Out') }}
            </flux:menu.item>
        </form>
    </flux:menu>
</flux:dropdown>