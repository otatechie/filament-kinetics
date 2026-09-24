# Kinetics

A theme for [Filament 5](https://filamentphp.com) admin panels. Pages sit on a
quiet canvas as one rounded panel, with no outlines, calm tables, compact
controls and quick, eased-out motion. It takes its colours from your panel, and
stays out of the way of your own Tailwind classes.

Kinetics is still being designed, so expect changes before 1.0.

## Installation

```bash
composer require otatechie/filament-kinetics
```

Kinetics styles your panel through a
[custom theme](https://filamentphp.com/docs/5.x/styling/overview#creating-a-custom-theme).
If your panel doesn't have one yet, create it:

```bash
php artisan make:filament-theme
```

Then add the Kinetics imports to your theme's `theme.css`. `layers.css` goes
first, before Filament's CSS, and `kinetics.css` after it:

```css
@import '../../../../vendor/otatechie/filament-kinetics/resources/css/layers.css';
@import '../../../../vendor/filament/filament/resources/css/theme.css';
@import '../../../../vendor/otatechie/filament-kinetics/resources/css/kinetics.css';

@source '../../../../app/Filament/**/*';
@source '../../../../resources/views/filament/**/*';
```

Add the plugin to your panel, next to your theme:

```php
use Otatechie\Kinetics\KineticsPlugin;

$panel
    ->plugin(KineticsPlugin::make())
    ->viteTheme('resources/css/filament/admin/theme.css');
```

Then build your assets:

```bash
npm run build
```

The plugin adds the Open Runde font and Lucide icons, and sets up the sidebar
layout. The font is published to `public/` by `php artisan filament:assets`.
Filament's installer runs it after every `composer install` and
`composer update`, through `filament:upgrade`; if your app doesn't, run it once
yourself.

Kinetics checks its setup for you. A panel without a custom theme stops with an
error explaining what to add. If the panel has a theme but the Kinetics imports
are missing, the browser console shows a warning. To check by hand, run this in
the console. It returns `ok`:

```js
getComputedStyle(document.body).getPropertyValue('--kinetics-layer-order')
```

## Documentation

- [Design principles](docs/design-principles.md): the decisions behind the look
- [Customising](docs/customising.md): colours, font, layout, icons and every variable
- [Components](docs/components.md): how each part of Filament is styled
- [Motion](docs/motion.md): timing, easing and reduced motion
- [Accessibility](docs/accessibility.md): contrast, focus and keyboard use

## Development

The theme is [`resources/css/kinetics.css`](resources/css/kinetics.css). Apps
build it with their own theme, so there's nothing to compile here.

```bash
composer test
```

## Credits

Motion follows [Emil Kowalski](https://emilkowal.ski/ui/7-practical-animation-tips).
Icons by [Lucide](https://lucide.dev). Open Runde by
[Laurids Kern](https://github.com/lauridskern/open-runde), under the SIL Open
Font License.

## License

MIT. See [LICENSE.md](LICENSE.md).
