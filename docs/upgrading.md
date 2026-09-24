# Upgrading

Kinetics follows [Semantic Versioning](https://semver.org). Before 1.0, a minor
version (0.2 to 0.3) can include changes that need work on your side. They're
listed here. The [changelog](../CHANGELOG.md) has every change.

After any update, rebuild your theme:

```bash
composer update otatechie/filament-kinetics
npm run build
```

## From 0.4 to 0.5

### Modals have no icons, and confirmations aren't centred

**Affects you if** you rely on the icon beside a modal's heading, or on
confirmations such as Delete being centred.

Kinetics now aligns every modal to the start and hides the icon beside the
heading. To centre one action's modal again, call `->modalAlignment()` and
`->modalFooterActionsAlignment()` on it. To show icons again, see
[Customising: Modals](customising.md#modals).

## From 0.3 to 0.4

### Navigation groups no longer collapse

**Affects you if** your users collapse sidebar groups, or your navigation is
long enough to need it.

Kinetics now turns off the collapse arrows on navigation groups: with a few
items per group they save no space, and a collapsed group hides pages. To bring
them back, after the plugin:

```php
$panel
    ->plugin(KineticsPlugin::make())
    ->collapsibleNavigationGroups();
```

### Everything else

The table scroll shadows and the neutral unread dot need nothing from you.

## From 0.2 to 0.3

### The ready-made theme is gone

**Affects you if** your panel uses the plugin without its own theme, with no
`->viteTheme()`.

0.2 could load a precompiled theme on its own. That copy of Filament's CSS
could fall behind when you updated Filament, and leave parts of the panel
unstyled, so 0.3 builds Kinetics into your theme instead. Without one, the
panel now stops with *Kinetics needs a custom theme*.

Follow [Installation](installation.md) from step 2: create a theme, add the
imports, keep the plugin and rebuild.

### `layers.css` replaces the `@layer` line

**Affects you if** your `theme.css` starts with
`@layer theme, base, components, kinetics, utilities;`.

It still works, but replace it with the import, which stays correct if the
order ever changes:

```css
/* Before */
@layer theme, base, components, kinetics, utilities;

/* After */
@import '../../../../vendor/otatechie/filament-kinetics/resources/css/layers.css';
```

### Kinetics' icons are set on the panel

**Affects you if** you changed any of Kinetics' icons with
`FilamentIcon::register()` in a service provider.

Kinetics now sets its icons through `$panel->icons()`, which Filament applies
after your service providers, so those changes no longer win. Move them to the
panel, after the plugin:

```php
$panel
    ->plugin(KineticsPlugin::make())
    ->icons([
        'tables::actions.filter' => 'lucide-list-filter',
    ]);
```

### Notifications moved to the bottom-right corner

**Affects you if** you want them at the top. Set the position after the
plugin:

```php
use Filament\Notifications\Livewire\Notifications;
use Filament\Support\Enums\VerticalAlignment;

$panel
    ->plugin(KineticsPlugin::make())
    ->bootUsing(fn () => Notifications::verticalAlignment(VerticalAlignment::Start));
```

### Everything else

The compact sidebar, the notifications panel and the setup checks need nothing
from you.

## From 0.1

0.1 wasn't a package: it was the theme inside a demo app. If you copied its CSS
into your own app, remove that copy, including any `--g-*` variables, and follow
[Installation](installation.md). Variables are named `--kinetics-*` now; see
[Customising](customising.md#variables).
