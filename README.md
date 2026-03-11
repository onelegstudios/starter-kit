# OneLegStudios Laravel + Livewire Starter Kit

## Overview

This project is based on `laravel/livewire-starter-kit` and extended with additional authentication, developer experience, and local-development tooling.

It is designed for teams that want Laravel + Livewire with a practical default setup that is fast to run, easy to test, and convenient to customize.

## Added Packages

### Development

- `fruitcake/laravel-debugbar` for request/debug inspection
- `barryvdh/laravel-ide-helper` for IDE meta generation
- `soloterm/solo` for local terminal workflow automation
- `spatie/laravel-login-link` for local magic-login links
- `onelegstudios/starter-kit-setup` for post-create environment setup

## Instalation

```
laravel new --using=onelegstudios/starter-kit
```

## Project Improvements

- Included a prebuilt test-user login shortcut in the login view when running in `local`.
- Dark mode component with button, dropdown and two bar variatns.
- Profile picture managment.
- Uses [Lucide](https://lucide.dev/) insted of [Heroicons](https://heroicons.com/)
- Implements MustVerifyEmail by default

## Upstream Starter Kit

The original upstream starter kit documentation is available at:

- [Laravel Starter Kits](https://laravel.com/docs/starter-kits)
- [Livewire](https://livewire.laravel.com)
- [Flux UI](https://fluxui.dev)

## License

This project is open-sourced software licensed under the MIT license.
