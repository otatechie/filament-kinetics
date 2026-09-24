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

Add the plugin to your panel:

```php
use Otatechie\Kinetics\KineticsPlugin;

$panel->plugin(KineticsPlugin::make());
```

That's it. The plugin loads a ready-made theme (Filament's styles with Kinetics
on top), the Open Runde font and Lucide icons, and sets up the sidebar layout.
The files are published to `public/` by `php artisan filament:assets`. Filament's
installer runs it after every `composer install` and `composer update`, through
`filament:upgrade`; if your app doesn't, run it once yourself.

### With your own theme

If your panel has a [custom theme](https://filamentphp.com/docs/5.x/styling/overview#creating-a-custom-theme),
keep it and import Kinetics into it. You need this to change Kinetics'
[variables](docs/customising.md#variables), or to use Tailwind classes in your
own Blade views. Add the first and third lines to your `theme.css`:

```css
@layer theme, base, components, kinetics, utilities;

@import '../../../../vendor/filament/filament/resources/css/theme.css';
@import '../../../../vendor/otatechie/filament-kinetics/resources/css/kinetics.css';

@source '../../../../app/Filament/**/*';
@source '../../../../resources/views/filament/**/*';
```

The `@layer` line must come before Filament's import. Keep `->viteTheme()` on
your panel alongside the plugin; your theme replaces the ready-made one.

## Documentation

- [Design principles](docs/design-principles.md): the decisions behind the look
- [Customising](docs/customising.md): colours, font, layout, icons and every variable
- [Components](docs/components.md): how each part of Filament is styled
- [Motion](docs/motion.md): timing, easing and reduced motion
- [Accessibility](docs/accessibility.md): contrast, focus and keyboard use

## Development

The theme is [`resources/css/kinetics.css`](resources/css/kinetics.css). After
changing it, rebuild the ready-made theme and commit the result:

```bash
npm install
npm run build
```

Rebuild after a Filament update too: the ready-made theme includes Filament's
styles, so it has to match the Filament version it ships with. CI checks it's
current.

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
