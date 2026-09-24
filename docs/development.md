# Development

How Kinetics is put together, and how to change it without breaking it.

## What's where

| Path | What it is |
|---|---|
| `resources/css/kinetics.css` | The theme. Everything visual is here |
| `resources/css/layers.css` | The cascade layer order apps import first |
| `resources/fonts/open-runde/` | Open Runde, with its licence and `@font-face` rules |
| `src/KineticsPlugin.php` | The panel settings, the Lucide icon map and the setup checks |
| `src/KineticsServiceProvider.php` | Registers the font with Filament's asset publishing |
| `tests/` | Pest tests, run against a Filament panel in Testbench |
| `docs/` | These guides |

There's no build step in the package. Apps compile `kinetics.css` with their
own theme.

## Setting up

```bash
git clone https://github.com/otatechie/filament-kinetics.git
cd filament-kinetics
composer install
composer test
```

## Working on the CSS

CSS changes only mean something on a real panel, so work on the theme inside a
Filament app. Point the app at your local copy with a Composer path
repository, so edits show up without reinstalling:

```json
"repositories": [
    {
        "type": "path",
        "url": "../filament-kinetics",
        "options": { "symlink": true }
    }
]
```

```bash
composer require otatechie/filament-kinetics:@dev
```

Then set the app up as in [Installation](installation.md), and keep
`npm run dev` running, or run `npm run build` after each change.

It helps to have realistic data on every page (long names, empty states,
every status), so tables, badges and forms get tested the way people use them.

## CSS rules

These keep Kinetics predictable. Follow them in every change.

- **Everything goes inside `@layer kinetics`.** That's what lets Kinetics beat
  Filament's components while losing to the app's own Tailwind classes. The
  only exception is the load-order check at the top of `kinetics.css`.
- **No `!important`**, except on Filament's button colour variables (`--bg`,
  `--text` and so on), which Filament sets with utility classes in a higher
  layer.
- **Colours come from the `--kinetics-*` variables**, and those come from
  Filament's palette (`--primary-700`, `--gray-200`), so `->colors()` recolours
  everything. Don't write a raw colour in a rule. If a new one is needed, add a
  variable, give it light and dark values, and document it in
  [Customising](customising.md#variables).
- **Use logical properties**: `padding-inline`, `margin-inline-start`,
  `inset-inline-end`, never left and right. That's what makes right-to-left
  languages mirror correctly.
- **Style Filament's `fi-` classes, never its templates.** Kinetics overrides
  no Blade views, so Filament's behaviour and updates keep working.
- **Check how Filament styles the thing first.** Its CSS is in
  `vendor/filament/*/resources/css/`. Overriding a property Filament sets
  through a Tailwind utility class (`fi-text-color-700` and so on) means
  setting the property itself, since the utility only sets a variable.
- **Motion follows [Motion](motion.md)**: under 300ms, ease-out with
  `--kinetics-ease-out`, only transform and opacity, and nothing moves under
  reduced motion.
- **Comments say why**, not what. A rule that looks odd should say what it
  works around.

## Checking a change

Before committing a visual change, look at it:

- In **light and dark mode**
- With the **sidebar collapsed**
- With **reduced motion** on, if anything moves
- On a **phone-sized** window, if it touches layout
- With **keyboard focus**, if it touches something interactive

Then check nothing else moved. A computed-style comparison works well: record
the styles of every `fi-` element on a set of pages before and after the
change, and compare. Only the elements you meant to change should differ.

## Tests

```bash
composer test
vendor/bin/pint
```

The tests cover the plugin, not the look:

- The panel settings it applies, and that later panel calls override them
- The Lucide icons, including that every icon name exists
- The font on the sign-in page
- The missing-theme error and the console warnings
- The layer order check in `kinetics.css` and `layers.css`

CI runs them on PHP 8.3 to 8.5 and Laravel 12 and 13, at the lowest and latest
allowed versions, every push and every Monday. Pint must pass too.

## Releasing

1. Move the changes under **Unreleased** in the [changelog](../CHANGELOG.md) to
   a new version with today's date. Breaking changes bump the major version,
   and also go in [Upgrading](upgrading.md).
2. Commit and push, and wait for CI to pass.
3. Tag and push: `git tag -a v1.1.0 -m "v1.1.0" && git push origin v1.1.0`.
4. Create the GitHub release from the tag, with that version's changelog
   entry as the notes. Mark it **Latest**, not pre-release.
5. If you keep a demo app, release it with the same version.
