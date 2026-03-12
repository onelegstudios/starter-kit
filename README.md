# OneLegStudios Laravel + Livewire Starter Kit

## Overview

This project extends laravel/livewire-starter-kit by providing enhanced developer experience and local development tools.

It is intended for teams seeking a Laravel + Livewire solution, offering a practical default setup that runs quickly, tests easily, and is highly customizable.

## Added Development Packages

- `fruitcake/laravel-debugbar` for request/debug inspection
- `barryvdh/laravel-ide-helper` for IDE meta generation
- `soloterm/solo` for local terminal workflow automation
- `spatie/laravel-login-link` for local magic-login links
- `onelegstudios/starter-kit-setup` for post-create environment setup

## Project Improvements

- The kit adds a shortcut that lets developers log in instantly as a preconfigured test user, saving time during local testing.
- A customizable dark mode is included, with options such as button, dropdown, or two-bar configurations, giving users flexibility in how they enable dark mode.
- Users can upload, update, or remove a profile picture directly from their profile settings, simplifying the process of managing personal avatars.
- Uses [Lucide](https://lucide.dev/) insted of [Heroicons](https://heroicons.com/)
- Implements MustVerifyEmail by default

## Instalation

```
laravel new --using=onelegstudios/starter-kit
```

## Upstream Starter Kit

The original upstream starter kit documentation is available at:

- [Laravel Starter Kits](https://laravel.com/docs/starter-kits)
- [Livewire](https://livewire.laravel.com)
- [Flux UI](https://fluxui.dev)

## License

This project is open-sourced software licensed under the MIT license.
