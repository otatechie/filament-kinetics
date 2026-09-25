# Installation

## Requirements

| | Version |
|---|---|
| PHP | 8.3 or later |
| Laravel | 12 or 13 (12 with Filament 4) |
| Filament | 4.13.3 or later, or 5.8.3 or later |
| Node.js | Any version your Vite build runs on |

Kinetics is built by your app's own Filament theme, with Vite and Tailwind CSS
4. `php artisan make:filament-theme` sets that up if you don't have it yet.

Kinetics has two parts, and a panel needs both: the CSS in its theme (step 3)
and the plugin (step 4). The CSS alone gives Kinetics' colours and components
on Filament's layout, font and icons, and the browser console says the plugin
is missing.

Browsers: the same as Filament and Tailwind CSS 4, which means Safari 16.4,
Chrome 111 and Firefox 128 or later.

## 1. Install the package

```bash
composer require otatechie/filament-kinetics
```

This also installs [Lucide](https://lucide.dev)'s icons
(`mallardduck/blade-lucide-icons`), which Kinetics uses in place of Filament's
own.

## 2. Give your panel a custom theme

Kinetics' styles are built into your panel's theme, so the panel needs one. If
it already has a `theme.css` and `->viteTheme()`, skip to step 3.

```bash
php artisan make:filament-theme
```

If you have more than one panel, it asks which one. It creates
`resources/css/filament/{panel}/theme.css` and installs Tailwind. Where it can,
it also adds the file to `vite.config.js` and adds `->viteTheme()` to your panel
provider. Anything it can't do, it lists at the end: do those before carrying
on.

## 3. Add the Kinetics imports

Open the theme's `theme.css` and add two lines around Filament's import:

```css
@import '../../../../vendor/otatechie/filament-kinetics/resources/css/layers.css';
@import '../../../../vendor/filament/filament/resources/css/theme.css';
@import '../../../../vendor/otatechie/filament-kinetics/resources/css/kinetics.css';

@source '../../../../app/Filament/**/*';
@source '../../../../resources/views/filament/**/*';
```

| Line | What it does |
|---|---|
| `layers.css` | Sets the order of the cascade layers, so Kinetics sits above Filament's components and below Tailwind's utilities. **It must come before Filament's import.** |
| Filament's `theme.css` | Filament's own styles, already in your file |
| `kinetics.css` | Kinetics itself |
| `@source` | Where Tailwind looks for classes in your own code. Keep whatever your file already has |

The paths assume the default location, four folders below your project root.
If your theme lives elsewhere, adjust the `../` parts to match Filament's
import.

## 4. Add the plugin

In your panel provider:

```php
use Otatechie\Kinetics\KineticsPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(KineticsPlugin::make())
        ->viteTheme('resources/css/filament/admin/theme.css');
}
```

The plugin sets up the sidebar layout, the Open Runde font and the Lucide
icons. See [Customising](customising.md#what-the-plugin-sets) for everything it
changes. Panel methods you call **after** `->plugin()` override it, so put your
own settings below it.

## 5. Build

```bash
npm run build
```

Or keep `npm run dev` running while you work.

## 6. Check it

Open your panel. Kinetics checks its own setup:

| What you see | What it means |
|---|---|
| The panel in Kinetics' style, no warnings | Everything is set up |
| An error: *Kinetics needs a custom theme on the [admin] panel* | Step 2 is missing: the panel has no `->viteTheme()` or `->theme()` |
| A browser console warning: *the theme CSS is missing* | Step 3 or 5 is missing: the theme doesn't include `kinetics.css`, or hasn't been rebuilt |
| A browser console warning: *layers.css must be imported before Filament's theme* | The imports in step 3 are in the wrong order, or `layers.css` is missing |
| A browser console warning: *the theme CSS is loaded, but KineticsPlugin isn't on this panel* | Step 4 is missing: the panel has no `->plugin(KineticsPlugin::make())` |

You can also check by hand in the browser console:

```js
getComputedStyle(document.body).getPropertyValue('--kinetics-layer-order')
```

It returns `ok` when Kinetics is loaded in the right order, `wrong` when
`layers.css` isn't first, and nothing when `kinetics.css` isn't loaded. If
something's off, see [Troubleshooting](troubleshooting.md).

## The font files

Open Runde ships inside the package and is copied to
`public/fonts/otatechie/filament-kinetics/` by `php artisan filament:assets`.
Filament's installer adds `filament:upgrade`, which runs it, to your
`composer.json`, so it happens after every `composer install` and
`composer update`. If your app doesn't have that, run it once yourself, and
again after updating Kinetics:

```bash
php artisan filament:assets
```

If you deploy with a build step, run it there too, or commit the published
files.

## More than one panel

Kinetics is set up per panel. For each panel that should use it, add the plugin
and give that panel's theme the imports. Panels without the plugin are
unaffected: they keep their own layout, icons and notification position.

The one thing all panels share is the Open Runde stylesheet, which Filament
loads on every page, as it does for its own Inter font. Panels that don't use
Open Runde don't show it.

## Updating

```bash
composer update otatechie/filament-kinetics
npm run build
```

Kinetics' CSS is built into your own theme, so **rebuild after every update**,
or you'll keep the previous version. Read the [changelog](../CHANGELOG.md)
first.

## Removing

1. Remove `->plugin(KineticsPlugin::make())` from your panel provider.
2. Remove the `layers.css` and `kinetics.css` imports from your `theme.css`.
3. Rebuild with `npm run build`.
4. Remove the package with `composer remove otatechie/filament-kinetics`.
5. Delete `public/fonts/otatechie/filament-kinetics/`.
6. If you ran the Appearance page's migration, remove its table:
   1. Run `php artisan make:migration drop_kinetics_appearance_table`.
   2. Open the new file in `database/migrations/` and, inside `up()`, add
      `Schema::dropIfExists('kinetics_appearance');`.
   3. Run `php artisan migrate`.

   Keep the migration that step 1 of
   [Turning it on](customising.md#turning-it-on) copied in, the file ending
   in `_create_kinetics_appearance_table.php`: the new migration runs after
   it and removes the table, on every environment.

If your own code uses `lucide-*` icons, require
`mallardduck/blade-lucide-icons` directly before step 4, since it came with
Kinetics.
