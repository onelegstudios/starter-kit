@blaze

@props([
    'align' => 'start',
    'position' => 'bottom',
    'type' => 'button',
])

@if ($type === 'dropdown')
    <flux:dropdown x-data align="{{ $align }}" position="{{ $position }}"
        class="{{ $attributes->get('class') }}">
        <flux:button variant="subtle" square class="group" aria-label="Preferred color scheme">
            <flux:icon.sun x-show="$flux.appearance === 'light'" class="text-zinc-500 dark:text-white" />
            <flux:icon.moon x-show="$flux.appearance === 'dark'" class="text-zinc-500 dark:text-white" />
            <flux:icon.sun-moon x-show="$flux.appearance === 'system' && $flux.dark" />
            <flux:icon.sun-moon x-show="$flux.appearance === 'system' && ! $flux.dark" />
        </flux:button>

        <flux:menu>
            <flux:menu.item icon="sun" x-on:click="$flux.appearance = 'light'">Light</flux:menu.item>
            <flux:menu.item icon="moon" x-on:click="$flux.appearance = 'dark'">Dark</flux:menu.item>
            <flux:menu.item icon="monitor" x-on:click="$flux.appearance = 'system'">System</flux:menu.item>
        </flux:menu>
    </flux:dropdown>
@elseif ($type === 'bar')
    <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" class="{{ $attributes->get('class') }}">
        <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
        <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
        <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
    </flux:radio.group>
@elseif ($type === 'iconbar')
    <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" class="{{ $attributes->get('class') }}">
        <flux:radio value="light" icon="sun" />
        <flux:radio value="dark" icon="moon" />
        <flux:radio value="system" icon="monitor" />
    </flux:radio.group>
@elseif ($type === 'button')
    <flux:button x-data variant="subtle" square {{ $attributes->merge(['class' => 'group']) }}
        aria-label="Cycle preferred color scheme"
        x-on:click="$flux.appearance = $flux.appearance === 'light' ? 'dark' : ($flux.appearance === 'dark' ? 'system' : 'light')">
        <flux:icon.sun x-show="$flux.appearance === 'light'" class="text-zinc-500 dark:text-white" />
        <flux:icon.moon x-show="$flux.appearance === 'dark'" class="text-zinc-500 dark:text-white" />
        <flux:icon.sun-moon x-show="$flux.appearance === 'system' && $flux.dark" />
        <flux:icon.sun-moon x-show="$flux.appearance === 'system' && ! $flux.dark" />
    </flux:button>
@endif
