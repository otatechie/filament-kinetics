# Troubleshooting

Find what you're seeing below. Most problems come from the theme: Kinetics'
CSS is built into your panel's own theme, so the theme has to include it, in
the right order, and be rebuilt.

## "Kinetics needs a custom theme on the [admin] panel"

The panel has the plugin but no custom theme, so it would get Kinetics' layout
with Filament's default look. Kinetics stops rather than show that.

Create a theme and register it, as in
[Installation, step 2](installation.md#2-give-your-panel-a-custom-theme):

```bash
php artisan make:filament-theme
```

Then check the panel provider has `->viteTheme('resources/css/filament/{panel}/theme.css')`,
or `->theme(...)` if you build CSS without Vite.

## Console warning: "the theme CSS is missing"

The panel has a theme, but Kinetics' styles aren't in the CSS the browser
loaded. In order of likelihood:

1. **The imports aren't in the theme.** Add `layers.css` and `kinetics.css` as
   in [Installation, step 3](installation.md#3-add-the-kinetics-imports).
2. **The theme hasn't been rebuilt.** Run `npm run build`, or check
   `npm run dev` is running.
3. **`->viteTheme()` points at a different file** from the one you edited.
   Compare the path in the panel provider with the file's location.
4. **The browser has the old CSS.** Hard-refresh the page (Cmd+Shift+R, or
   Ctrl+Shift+R on Windows and Linux).

## Console warning: "layers.css must be imported before Filament's theme"

Kinetics loaded, but above Tailwind's utilities instead of below them. It
still looks right, but Kinetics now overrides your own Tailwind classes, and
some of Filament's.

Make `layers.css` the first import in `theme.css`, before Filament's:

```css
@import '../../../../vendor/otatechie/filament-kinetics/resources/css/layers.css';
@import '../../../../vendor/filament/filament/resources/css/theme.css';
@import '../../../../vendor/otatechie/filament-kinetics/resources/css/kinetics.css';
```

Then rebuild.

## Console warning: "KineticsPlugin isn't on this panel"

The panel's theme includes Kinetics' CSS, but the panel doesn't have the
plugin. The colours and components are Kinetics', but the layout, font, icons,
notification position and modal alignment are still Filament's, since the
plugin sets those. Add it to the panel provider, as in
[Installation, step 4](installation.md#4-add-the-plugin):

```php
->plugin(KineticsPlugin::make())
```

## The panel looks like plain Filament, with no warnings

The warnings come from the plugin, so no warnings usually means the plugin
isn't on this panel. Check the panel provider has
`->plugin(KineticsPlugin::make())`. With several panels, each needs its own.
If the plugin is there, hard-refresh: the browser may have cached the old CSS.

## The font isn't Open Runde

- **The font files weren't published.** Check
  `public/fonts/otatechie/filament-kinetics/open-runde/` exists. If not, run
  `php artisan filament:assets`. See
  [The font files](installation.md#the-font-files).
- **A `->font()` call after the plugin** replaces it. That's intended: remove
  it to get Open Runde back.

## A Kinetics update didn't change anything

Kinetics' CSS is built into your theme, so updating the package isn't enough.
Rebuild:

```bash
npm run build
```

## Settings on my panel are ignored

The plugin applies its settings when you call `->plugin()`, so anything you set
**before** it is overwritten. Move your own settings below the plugin:

```php
$panel
    ->plugin(KineticsPlugin::make())
    ->topbar()                      // wins
    ->maxContentWidth(Width::SevenExtraLarge);
```

## My icon changes don't apply

Kinetics sets its Lucide icons on the panel, which Filament applies when the
panel starts, after your service providers. So icons registered with
`FilamentIcon::register()` in a service provider lose to Kinetics for the same
names. Set them on the panel after the plugin instead:

```php
$panel
    ->plugin(KineticsPlugin::make())
    ->icons([
        'tables::actions.filter' => 'lucide-list-filter',
    ]);
```

Icons Kinetics doesn't set (see `KineticsPlugin::ICONS`) can still be
registered anywhere.

## Notifications still appear in the bottom-right corner

Kinetics moves them there when the panel starts, which is also after your
service providers. Set the position in `->bootUsing()`, which runs after the
plugin:

```php
use Filament\Notifications\Livewire\Notifications;
use Filament\Support\Enums\VerticalAlignment;

$panel
    ->plugin(KineticsPlugin::make())
    ->bootUsing(fn () => Notifications::verticalAlignment(VerticalAlignment::Start));
```

## My Tailwind classes aren't applied

- **Tailwind isn't scanning the file.** Classes are only generated for files an
  `@source` line covers. Add one for the folder, then rebuild.
- **Kinetics is winning over them.** It shouldn't: your classes sit in a higher
  layer. If they lose, the layer order is wrong. See the
  [layers.css warning](#console-warning-layerscss-must-be-imported-before-filaments-theme).

## Dark mode colours don't follow my gray palette

That's by design. Dark mode uses fixed warm charcoal surfaces rather than the
`gray` palette. The primary colour still follows the panel. To change the
surfaces, set the variables on `.dark`. See
[Customising: Dark mode](customising.md#dark-mode).

## A table is cut off on the right

A table wider than the panel scrolls sideways. On macOS, scrollbars are hidden
until you scroll, so Kinetics shows a soft shadow on the side with more
columns. Scroll the table with a trackpad or Shift and the mouse wheel, or show
fewer columns by default with Filament's column manager:

```php
TextColumn::make('platform_fee')
    ->toggleable(isToggledHiddenByDefault: true);
```

## Still stuck

[Open an issue](https://github.com/otatechie/filament-kinetics/issues) with
your Filament and Kinetics versions (`composer show filament/filament otatechie/filament-kinetics`),
your `theme.css` and your panel provider.
