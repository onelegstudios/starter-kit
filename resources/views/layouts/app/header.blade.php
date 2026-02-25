<x-layouts::base :title="$title ?? null">
    <div class="flex min-h-dvh flex-col">
        <flux:header container class="border-b bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="menu" inset="left" />

            <flux:brand href="#" logo="https://fluxui.dev/img/demo/logo.png" name="Acme Inc."
                class="max-lg:hidden dark:hidden" />
            <flux:brand href="#" logo="https://fluxui.dev/img/demo/dark-mode-logo.png" name="Acme Inc."
                class="max-lg:hidden! hidden dark:flex" />

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

            <flux:navbar class="me-4">
                <flux:navbar.item icon="magnifying-glass" href="#" label="Search" />
            </flux:navbar>

            <div class="flex items-center gap-2">
                <x-layouts.dark-mode />

                @auth()
                    <x-layouts.user-menu align="end" />
                @else
                    <flux:button icon="log-in" href="{{ route('login') }}" label="Login" />
                    <flux:button icon="user-plus" href="{{ route('register') }}" label="Register" variant="filled" />
                @endauth
            </div>
        </flux:header>

        <flux:main container class="flex-1">
            {{ $slot }}
        </flux:main>
    </div>

    <flux:sidebar sticky collapsible="mobile"
        class="border-r lg:hidden bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.header>
            <flux:sidebar.brand href="#" logo="https://fluxui.dev/img/demo/logo.png"
                logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png" name="Acme Inc." />

            <flux:sidebar.collapse
                class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
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
