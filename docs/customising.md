# Customising

Most of Kinetics is set by your panel. For the rest, change a variable.

## Colours

Kinetics takes every colour from the panel's palette:

```php
use Filament\Support\Colors\Color;

$panel->colors([
    'primary' => Color::Teal,
    'gray' => Color::Gray,
]);
```

Change `primary` and the whole theme follows: buttons, links, focus rings, the
active menu item, the current page, toggles, checkboxes, date pickers and text
selection. `gray` sets the light-mode surfaces and text. Dark mode uses fixed
charcoal surfaces (see below), with the primary colour still following the
panel.

Buttons and coloured text use the palette's **700** shade, so white text on
them passes contrast checks. See [Accessibility](accessibility.md).

## Font

Kinetics bundles Open Runde and sets it on the panel. To use another font, call
`->font()` after the plugin:

```php
$panel
    ->plugin(KineticsPlugin::make())
    ->font('Inter');
```

## Layout

The plugin sets up the layout Kinetics is designed for: no top bar, with search
and the account menu in the sidebar. It's the same as calling:

```php
use Filament\Enums\GlobalSearchPosition;
use Filament\Enums\UserMenuPosition;
use Filament\Support\Enums\Width;

$panel
    ->topbar(false)
    ->globalSearch(position: GlobalSearchPosition::Sidebar)
    ->userMenu(position: UserMenuPosition::Sidebar)
    ->sidebarCollapsibleOnDesktop()
    ->breadcrumbs(false)
    ->maxContentWidth(Width::Full);
```

It also removes the icon from the sign-out item. Anything you call after
`->plugin()` wins, so you can turn any of these back:

```php
$panel
    ->plugin(KineticsPlugin::make())
    ->topbar()
    ->breadcrumbs()
    ->maxContentWidth(Width::SevenExtraLarge);
```

With the top bar on, the panel fits below it. With a narrower content width,
the line under the page title spans the content rather than the whole panel.

## Icons

Kinetics swaps Filament's own icons (table actions, sort arrows, modals and so
on) for [Lucide](https://lucide.dev), drawn at a slightly lighter stroke. The
full list is `KineticsPlugin::ICONS`; anything it leaves out keeps its
Heroicon. Your own icons override it as usual:

```php
use Filament\Support\Facades\FilamentIcon;

FilamentIcon::register([
    'tables::actions.filter' => 'lucide-list-filter',
]);
```

Lucide is installed with Kinetics, so your own navigation items and actions can
use it too: `->icon('lucide-shopping-bag')`.

## Variables

Everything else is a CSS variable. Variables need
[your own theme](../README.md#with-your-own-theme): set them after the Kinetics
import, light values on `:root` and dark ones on `.dark`:

```css
:root {
    --kinetics-radius: 0.75rem;
    --kinetics-canvas: var(--gray-100);
}

.dark {
    --kinetics-panel: #1c1c1c;
}
```

### Surfaces

| Variable | Used for |
|---|---|
| `--kinetics-canvas` | The page behind the sidebar and panel |
| `--kinetics-panel` | The rounded content panel |
| `--kinetics-panel-border` | The line under the page title |
| `--kinetics-card` | Sections, inputs, modals, dropdowns, notifications |
| `--kinetics-card-border` | Section and card edges |
| `--kinetics-background` | Secondary (outline) buttons |
| `--kinetics-muted` | Hover tints, the segmented-control track, empty-state circles |

### Text and lines

| Variable | Used for |
|---|---|
| `--kinetics-foreground` | Main text |
| `--kinetics-muted-foreground` | Secondary text, placeholders, labels, sort arrows |
| `--kinetics-border` | Dividers and dropdown edges |
| `--kinetics-input-border` | Text inputs and selects |
| `--kinetics-control-border` | Unchecked checkboxes and radios |

### Primary colour

| Variable | Default | Used for |
|---|---|---|
| `--kinetics-primary` | primary 600 | Focus borders, text selection |
| `--kinetics-primary-text` | primary 700 | Links, active menu item, current page, active tab underline |
| `--kinetics-primary-button` | primary 700 | Primary buttons, toggles, checked boxes, the selected date |
| `--kinetics-primary-button-hover` | primary 800 | Primary buttons on hover |
| `--kinetics-primary-foreground` | white | Text on primary buttons |
| `--kinetics-primary-ring` | primary 600 at 12% | The soft ring around a focused field |

### Errors

| Variable | Used for |
|---|---|
| `--kinetics-destructive` | Error text, invalid fields, destructive buttons |
| `--kinetics-destructive-ring` | The ring around a focused invalid field |

### Shape and motion

| Variable | Default | Used for |
|---|---|---|
| `--kinetics-radius` | `0.5rem` | Corners of cards, inputs and dropdowns |
| `--kinetics-ease-out` | `cubic-bezier(0.23, 1, 0.32, 1)` | Every animation |

## Dark mode

Dark mode uses warm charcoal with a hint of sepia, and off-white text. These
surfaces are fixed rather than taken from the `gray` palette; override them on
`.dark` to change them. Inputs get a stronger edge than dividers in dark mode,
so fields stay easy to find.

## Your own styles

Kinetics lives in its own cascade layer, between Filament's components and
Tailwind's utilities:

```css
@layer theme, base, components, kinetics, utilities;
```

So a Tailwind class in your own Blade views wins over Kinetics without
`!important`. A plain CSS rule in your theme wins too, as long as it isn't
inside a lower layer.

To check the order loaded correctly, run this in the browser console. It
returns `ok`:

```js
getComputedStyle(document.body).getPropertyValue('--kinetics-layer-order')
```
