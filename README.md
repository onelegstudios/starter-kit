# OneLegStudios Laravel + Livewire Starter Kit

## Overview

This project extends laravel/livewire-starter-kit by providing enhanced developer experience and local development tools.

It is intended for teams seeking a Laravel + Livewire solution, offering a practical default setup that runs quickly, tests easily, and is highly customizable.

## Project improvements

### Philosophy

This starter kit adopts the Livewire 4 view-first structure, organizing code into dedicated `pages`, `layouts`, and `components` folders.

- Store only full-page Livewire components in `resources/views/pages`.
- Place reusable helper UI components in `resources/views/components`, grouping them by domain (for example, (for example `components/settings/profile/*` and `components/settings/two-factor/*`).
- Use Blade components when Livewire interactivity is not required.
- Favor components over partials for reusable UI.

### Key enhancements

Experience enhanced usability and customization with this starter kit. It introduces several practical improvements over the upstream default. Enjoy customizable dark mode controls, including a button, a dropdown, and a visual toggle switch. The toggle switch comes in two variants. One displays text labels, while the other uses only an icon. Both variants are ideal for a settings page. The kit also supports profile picture upload, replacement, and removal from settings. Mail settings compatible with MailPint. It switches to [Lucide](https://lucide.dev) icons for a cleaner visual style and enables `MustVerifyEmail` by default.

### Developer Tooling

It also adds focused development and local tooling packages to improve workflow. [`barryvdh/laravel-ide-helper`](https://github.com/barryvdh/laravel-ide-helper) improves editor intelligence, [`fruitcake/laravel-debugbar`](https://github.com/fruitcake/laravel-debugbar) helps inspect requests and performance during development, [`soloterm/solo`](https://github.com/soloterm/solo)runs multiple commands simultaneously to aid local development. We made it easy to enable the local HTTP server and run the mailpint server. All the commands needed to run your application live are available through a single artisan command. [`spatie/laravel-login-link`](https://github.com/spatie/laravel-login-link) simplifies secure local login and testing flows.

## Installation

```
laravel new --using=onelegstudios/starter-kit
```

## Documentation

### Dark mode component

The `resources/views/components/layouts/dark-mode.blade.php` component provides a reusable UI for switching Flux appearance between `light`, `dark`, and `system`.

Use it in Blade as:

```blade
<x-layouts.dark-mode />
```

Supported props:

- `type` (`button` | `dropdown` | `bar` | `iconbar`, default: `button`)
- `align` (default: `start`, used by `dropdown`)
- `position` (default: `bottom`, used by `dropdown`)
- standard Blade attribute bag (for example `class`)

Examples:

```blade
{{-- Default cycle button (light -> dark -> system) --}}
<x-layouts.dark-mode />

{{-- Dropdown menu variant --}}
<x-layouts.dark-mode type="dropdown" align="end" position="bottom" class="max-lg:hidden" />

{{-- Segmented control with labels --}}
<x-layouts.dark-mode type="bar" class="w-full" />

{{-- Segmented control with icons only --}}
<x-layouts.dark-mode type="iconbar" />
```

Notes:

- The component expects Flux appearance state (`$flux.appearance` and `$flux.dark`) to be available.
- `type="bar"` and `type="iconbar"` are useful on settings/profile pages where users expect persistent display controls.

## Upstream starter kit

The original upstream starter kit documentation is available at:

- [Laravel Starter Kits](https://laravel.com/docs/starter-kits)
- [Livewire](https://livewire.laravel.com)
- [Flux UI](https://fluxui.dev)

## License

This project is open-sourced software licensed under the MIT license.
