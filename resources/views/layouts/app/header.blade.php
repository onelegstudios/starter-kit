<x-layouts::base :title="$title ?? null">
    <div class="flex flex-col min-h-dvh">
        <flux:header container class="py-2 border-b bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700">

            <flux:brand href="#" logo="https://fluxui.dev/img/demo/logo.png" name="Acme Inc." class="dark:hidden" />
            <flux:brand href="#" logo="https://fluxui.dev/img/demo/dark-mode-logo.png" name="Acme Inc."
                class="hidden dark:flex" />

            <flux:spacer />

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="home" href="{{ route('home') }}" :current="request()->routeIs('home')">Home
                </flux:navbar.item>
                @auth
                    <flux:navbar.item icon="layout-dashboard" href="{{ route('dashboard') }}"
                        :current="request()->routeIs('dashboard')">Dashboard</flux:navbar.item>
                @endauth
                <flux:navbar.item icon="inbox" badge="12" href="#">Inbox</flux:navbar.item>
                <flux:navbar.item icon="document-text" href="#">Documents</flux:navbar.item>
                <flux:navbar.item icon="calendar" href="#">Calendar</flux:navbar.item>

                <flux:separator vertical variant="subtle" class="my-2" />

                <flux:dropdown class="max-lg:hidden">
                    <flux:navbar.item icon="star" icon:trailing="chevron-down">Favorites</flux:navbar.item>

                    <flux:navmenu>
                        <flux:navmenu.item href="#">Marketing site</flux:navmenu.item>
                        <flux:navmenu.item href="#">Android app</flux:navmenu.item>
                        <flux:navmenu.item href="#">Brand guidelines</flux:navmenu.item>
                    </flux:navmenu>
                </flux:dropdown>
            </flux:navbar>

            <flux:spacer />

            <div class="flex items-center gap-2 lg:gap-3">
                <x-layouts.dark-mode class="max-lg:hidden!" />

                <flux:input type="search" icon="search" placeholder="Search..." size="sm"
                    class="w-40! max-lg:hidden" />

                @auth()
                    <livewire:layouts.user-menu align="end" />
                @else
                    <flux:button icon="log-in" href="{{ route('login') }}" label="Login" />
                    <flux:button icon="user-plus" href="{{ route('register') }}" label="Register" variant="filled" />
                @endauth
                <flux:separator vertical class="m-2 lg:hidden" />
                <flux:sidebar.toggle class="lg:hidden" icon="menu" inset="left" />
            </div>
        </flux:header>

        <flux:main container class="flex-1">
            {{ $slot }}
        </flux:main>
    </div>

    <flux:sidebar sticky collapsible="mobile" class="w-full lg:hidden bg-zinc-50 dark:bg-zinc-900">
        <flux:sidebar.header>
            <flux:sidebar.brand href="#" logo="https://fluxui.dev/img/demo/logo.png"
                logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png" name="Acme Inc." />
            <flux:spacer />
            <x-layouts.dark-mode />
            <flux:sidebar.collapse />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="{{ route('home') }}" :current="request()->routeIs('home')">Home
            </flux:sidebar.item>
            @auth
                <flux:sidebar.item icon="layout-dashboard" href="{{ route('dashboard') }}"
                    :current="request()->routeIs('dashboard')">
                    Dashboard</flux:sidebar.item>
            @endauth
            <flux:sidebar.item icon="inbox" badge="12" href="#">Inbox</flux:sidebar.item>
            <flux:sidebar.item icon="document-text" href="#">Documents</flux:sidebar.item>
            <flux:sidebar.item icon="calendar" href="#">Calendar</flux:sidebar.item>

            <flux:sidebar.group heading="Favorites" icon="star" class="grid" expandable :expanded="false">
                <flux:sidebar.item href="#">Marketing site</flux:sidebar.item>
                <flux:sidebar.item href="#">Android app</flux:sidebar.item>
                <flux:sidebar.item href="#">Brand guidelines</flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="cog-6-tooth" href="#">Settings</flux:sidebar.item>
            <flux:sidebar.item icon="information-circle" href="#">Help</flux:sidebar.item>
        </flux:sidebar.nav>
    </flux:sidebar>

</x-layouts::base>
