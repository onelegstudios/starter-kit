<x-layouts::base :title="$title ?? null">
    <flux:sidebar sticky collapsible="mobile"
        class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.header>
            <flux:sidebar.brand href="#" logo="https://fluxui.dev/img/demo/logo.png"
                logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png" name="Acme Inc." />

            <flux:sidebar.collapse class="lg:hidden" />
            <x-layouts.dark-mode align="end" class="max-lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.search placeholder="Search..." />

        <flux:sidebar.nav>
            <flux:sidebar.item href="{{ route('home') }}" :current="request()->routeIs('home')" icon="home">Home
            </flux:sidebar.item>
            @auth
                <flux:sidebar.item icon="layout-dashboard" href="{{ route('dashboard') }}"
                    :current="request()->routeIs('dashboard')">
                    Dashboard</flux:sidebar.item>
            @endauth
            <flux:sidebar.item icon="inbox" badge="12" href="#">Inbox</flux:sidebar.item>
            <flux:sidebar.item icon="document-text" href="#">Documents</flux:sidebar.item>
            <flux:sidebar.item icon="calendar" href="#">Calendar</flux:sidebar.item>

            <flux:sidebar.group heading="Favorites" expandable class="grid">
                <flux:sidebar.item href="#">Marketing site</flux:sidebar.item>
                <flux:sidebar.item href="#">Android app</flux:sidebar.item>
                <flux:sidebar.item href="#">Brand guidelines</flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.spacer />

        @auth
            <x-layouts.user-menu :circle="true" />
        @else
            <flux:button href="{{ route('login') }}" variant="outline" icon="log-in" class="w-full">Login
            </flux:button>

            <flux:button href="{{ route('register') }}" variant="filled" icon="user-plus" class="w-full">Register
            </flux:button>
        @endauth

    </flux:sidebar>

    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="menu" inset="left" />

        <flux:spacer />


        <div class="flex items-center gap-2">
            <x-layouts.dark-mode />

            @auth()
                <x-layouts.user-menu :circle="true" />
            @else
                <flux:button icon="log-in" href="{{ route('login') }}" label="Login" />
                <flux:button icon="user-plus" href="{{ route('register') }}" label="Register" variant="filled" />
            </div class="flex items-center gap-2">
        @endauth
    </flux:header>

    <flux:main>
        {{ $slot }}
    </flux:main>

</x-layouts::base>
