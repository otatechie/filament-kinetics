# Kinetics

[![Latest release](https://img.shields.io/packagist/v/otatechie/filament-kinetics?label=release)](https://packagist.org/packages/otatechie/filament-kinetics)
[![Downloads](https://img.shields.io/packagist/dt/otatechie/filament-kinetics)](https://packagist.org/packages/otatechie/filament-kinetics/stats)
[![License](https://img.shields.io/packagist/l/otatechie/filament-kinetics)](https://github.com/otatechie/filament-kinetics/blob/main/LICENSE.md)

[Filament](https://filamentphp.com) 4 and 5 theme: quiet tables, rounded
panels, Lucide icons and a warm dark mode. Free and MIT-licensed.

**[Try the live demo](https://kinetics.atoaugustine.com/admin)**: a small
marketplace admin with sample data on every page. The sign-in is filled in, and
the data resets on every deploy, so click anything.

![Kinetics in light mode: the Orders page of a marketplace admin, on a rounded panel beside the sidebar](https://raw.githubusercontent.com/otatechie/filament-kinetics/main/docs/images/orders-light.png)

Pages sit on a quiet canvas as one rounded panel. Tables have no lines,
controls are compact, and motion is quick and eased out. Kinetics takes its
colours from your panel and stays out of the way of your own Tailwind classes.

## What you get

- **A calmer layout.** No top bar: search, notifications and the account menu
  live in the sidebar, groups stay open, and the content sits on one rounded
  panel with no outlines.
- **Quiet tables.** No card, lines or background, compact rows, a soft rounded
  hover, and headings, cells and pagination on one edge. Wide tables show a
  soft shadow on the side that has more to scroll to.
- **Compact controls.** 40px inputs and buttons that line up, pill-shaped
  buttons, small checkboxes and switches, and links instead of buttons inside
  dropdowns.
- **Quick motion.** Everything under 300ms, eased out, and nothing moves when
  reduced motion is on.
- **A warm dark mode.** Charcoal with a hint of sepia, and off-white text.
- **Open Runde and Lucide.** A rounded, friendly font and a lighter icon set,
  bundled.
- **Better notifications.** Pop-ups in the bottom-right corner, clear of your
  buttons, and a plain, scannable notifications panel.
- **Your colours, accessible.** Every colour comes from the panel's palette, and
  buttons and links use the 700 shade so text passes contrast checks.
- **Setup checks.** A missing theme or wrong import order tells you what to fix.

![Kinetics in dark mode: the same Orders page on warm charcoal surfaces](https://raw.githubusercontent.com/otatechie/filament-kinetics/main/docs/images/orders-dark.png)

## Compatibility

| Filament | Laravel | PHP |
|---|---|---|
| 5.8.3 or later | 12, 13 | 8.3, 8.4, 8.5 |
| 4.13.3 or later | 12 | 8.3, 8.4 |

Your panel also needs a
[custom theme](https://filamentphp.com/docs/5.x/styling/overview#creating-a-custom-theme)
built with Vite. `php artisan make:filament-theme` creates one.

## Quick start

```bash
composer require otatechie/filament-kinetics
php artisan make:filament-theme   # if your panel doesn't have a theme yet
```

In your theme's `theme.css`, import `layers.css` before Filament and
`kinetics.css` after it:

```css
@import '../../../../vendor/otatechie/filament-kinetics/resources/css/layers.css';
@import '../../../../vendor/filament/filament/resources/css/theme.css';
@import '../../../../vendor/otatechie/filament-kinetics/resources/css/kinetics.css';

@source '../../../../app/Filament/**/*';
@source '../../../../resources/views/filament/**/*';
```

Add the plugin to your panel, then build:

```php
use Otatechie\Kinetics\KineticsPlugin;

$panel
    ->plugin(KineticsPlugin::make())
    ->viteTheme('resources/css/filament/admin/theme.css');
```

```bash
npm run build
```

[Installation](https://github.com/otatechie/filament-kinetics/blob/main/docs/installation.md) covers each step in detail, including
multiple panels, updating and removing.

## Customising

**Colours** come from your panel. Change `primary` and buttons, links, focus
rings, the current page, toggles and checkboxes all follow; `gray` sets the
light-mode surfaces and text:

```php
use Filament\Support\Colors\Color;

$panel
    ->plugin(KineticsPlugin::make())
    ->colors([
        'primary' => Color::Teal,
        'gray' => Color::Stone,
    ]);
```

**Anything the plugin sets**, such as the font, the top bar or the content
width, you can change by calling the panel method after the plugin:

```php
$panel
    ->plugin(KineticsPlugin::make())
    ->font('Inter')
    ->topbar();
```

**Everything else** is a CSS variable. Set them in your `theme.css` after the
Kinetics import, light values on `:root` and dark ones on `.dark`:

```css
:root {
    --kinetics-radius: 0.75rem;
}

.dark {
    --kinetics-panel: #1c1c1c;
}
```

| Variable | Used for |
|---|---|
| `--kinetics-radius` | Corners of cards, inputs, modals and dropdowns |
| `--kinetics-canvas` | The page behind the sidebar and panel |
| `--kinetics-panel` | The rounded content panel |
| `--kinetics-card` | Sections, inputs, modals, dropdowns and notifications |
| `--kinetics-foreground` | Main text |
| `--kinetics-muted-foreground` | Secondary text, placeholders and labels |
| `--kinetics-border` | Dividers and dropdown edges |

[Customising](https://github.com/otatechie/filament-kinetics/blob/main/docs/customising.md) lists every setting and variable, with
their light and dark values, and has [recipes](https://github.com/otatechie/filament-kinetics/blob/main/docs/customising.md#recipes)
for common changes: rounder corners, square buttons, one flat surface, a dark
mode from your own greys, and stronger field borders.

## Documentation

**Getting started**

- [Installation](https://github.com/otatechie/filament-kinetics/blob/main/docs/installation.md): requirements, setup, multiple panels,
  updating and removing
- [Troubleshooting](https://github.com/otatechie/filament-kinetics/blob/main/docs/troubleshooting.md): what each warning means, and
  fixes for common problems

**Using Kinetics**

- [Customising](https://github.com/otatechie/filament-kinetics/blob/main/docs/customising.md): what the plugin sets, colours, font,
  layout, icons, notifications, every variable, dark mode, and styling your
  own pages
- [Components](https://github.com/otatechie/filament-kinetics/blob/main/docs/components.md): how each part of Filament is styled

**Background**

- [Design principles](https://github.com/otatechie/filament-kinetics/blob/main/docs/design-principles.md): the decisions behind the look
- [Motion](https://github.com/otatechie/filament-kinetics/blob/main/docs/motion.md): timing, easing and reduced motion
- [Accessibility](https://github.com/otatechie/filament-kinetics/blob/main/docs/accessibility.md): contrast, focus, keyboard use and
  notifications

**Contributing**

- [Development](https://github.com/otatechie/filament-kinetics/blob/main/docs/development.md): how the package is organised, the CSS
  rules, testing and releasing
- [Changelog](https://github.com/otatechie/filament-kinetics/blob/main/CHANGELOG.md)

## Support

Found a bug or stuck on setup? [Open an issue](https://github.com/otatechie/filament-kinetics/issues/new/choose)
with your Filament and Kinetics versions
(`composer show filament/filament otatechie/filament-kinetics`), your
`theme.css` and your panel provider. [Troubleshooting](https://github.com/otatechie/filament-kinetics/blob/main/docs/troubleshooting.md)
covers the common problems first. For security issues, see
[SECURITY.md](https://github.com/otatechie/filament-kinetics/blob/main/SECURITY.md) instead.

## License

MIT. See [LICENSE.md](https://github.com/otatechie/filament-kinetics/blob/main/LICENSE.md).
