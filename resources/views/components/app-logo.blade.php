@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Laravel Starter Kit" {{ $attributes }}>
        <x-slot name="logo" class="flex items-center justify-center aspect-square size-8 rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="text-white fill-current size-5 dark:text-black" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Laravel Starter Kit" {{ $attributes }}>
        <x-slot name="logo" class="flex items-center justify-center aspect-square size-8 rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="text-white fill-current size-5 dark:text-black" />
        </x-slot>
    </flux:brand>
@endif
