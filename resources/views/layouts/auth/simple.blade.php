<x-layouts::base :title="$title ?? null">

    <div class="flex flex-col items-center justify-center p-6 gap-6 bg-background min-h-svh md:p-10">
        <div class="flex flex-col w-full max-w-sm gap-2">
            <a href="{{ route('home') }}" class="flex flex-col items-center font-medium gap-2" wire:navigate>
                <span class="flex items-center justify-center mb-1 rounded-md h-9 w-9">
                    <x-app-logo-icon class="text-black fill-current size-9 dark:text-white" />
                </span>
                <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
            </a>
            <div class="flex flex-col gap-6">
                {{ $slot }}
            </div>
        </div>
    </div>

</x-layouts::base>
