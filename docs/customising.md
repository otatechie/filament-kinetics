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
Heroicon. To change one, call `->icons()` after the plugin:

```php
$panel
    ->plugin(KineticsPlugin::make())
    ->icons([
        'tables::actions.filter' => 'lucide-list-filter',
    ]);
```

Lucide is installed with Kinetics, so your own navigation items and actions can
use it too: `->icon('lucide-shopping-bag')`.

## Notifications

Kinetics shows notifications in the bottom-right corner, since the top of the
page holds the header's buttons. To move them, set Filament's position in
`->bootUsing()`, which runs after the plugin:

```php
use Filament\Notifications\Livewire\Notifications;
use Filament\Support\Enums\VerticalAlignment;

$panel
    ->plugin(KineticsPlugin::make())
    ->bootUsing(fn () => Notifications::verticalAlignment(VerticalAlignment::Start));
```

## Variables

Everything else is a CSS variable. Set them in your theme after the Kinetics
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

Defaults use Filament's palette variables: `gray-200` is `var(--gray-200)`, and
so on. Dark mode uses fixed warm charcoal (see [Dark mode](#dark-mode)).

### Surfaces

| Variable | Used for | Light | Dark |
|---|---|---|---|
| `--kinetics-canvas` | The page behind the sidebar and panel | `gray-100` mixed 70% with `gray-200` | `#1a1918` |
| `--kinetics-panel` | The rounded content panel | `gray-50` | `#222120` |
| `--kinetics-panel-border` | The line under the page title | `gray-200` | `#33322f` |
| `--kinetics-card` | Sections, inputs, modals, dropdowns, notifications | `#fff` | `#292826` |
| `--kinetics-card-border` | Section and card edges | `gray-200` mixed 60% with white | `#33322f` |
| `--kinetics-background` | Secondary (outline) buttons | `gray-50` | `#1a1918` |
| `--kinetics-muted` | Hover tints, the segmented-control track, empty-state circles | `gray-100` | `#302f2c` |

### Text and lines

| Variable | Used for | Light | Dark |
|---|---|---|---|
| `--kinetics-foreground` | Main text | `gray-900` | `#e9e7e3` |
| `--kinetics-muted-foreground` | Secondary text, placeholders, labels, sort arrows | `gray-500` | `#a3a09a` |
| `--kinetics-border` | Dividers and dropdown edges | `gray-200` | `#373532` |
| `--kinetics-input-border` | Text inputs and selects | `gray-200` | `#4a4844` |
| `--kinetics-control-border` | Unchecked checkboxes and radios | `gray-400` | `#6a6762` |

### Primary colour

| Variable | Used for | Light | Dark |
|---|---|---|---|
| `--kinetics-primary` | Focus borders, text selection | `primary-600` | `primary-500` |
| `--kinetics-primary-text` | Links, active menu item, current page, active tab underline | `primary-700` | `primary-400` |
| `--kinetics-primary-button` | Primary buttons, toggles, checked boxes, the selected date | `primary-700` | `primary-700` |
| `--kinetics-primary-button-hover` | Primary buttons on hover | `primary-800` | `primary-800` |
| `--kinetics-primary-foreground` | Text on primary buttons | `#fff` | `#fff` |
| `--kinetics-primary-ring` | The soft ring around a focused field | `primary-600` at 12% | `primary-500` at 20% |

### Errors

| Variable | Used for | Light | Dark |
|---|---|---|---|
| `--kinetics-destructive` | Error text, invalid fields, destructive buttons | `danger-500` | `danger-400` |
| `--kinetics-destructive-ring` | The ring around a focused invalid field | `danger-600` at 12% | `danger-600` at 12% |

### Shape and motion

| Variable | Used for | Default |
|---|---|---|
| `--kinetics-radius` | Corners of cards, inputs and dropdowns | `0.5rem` |
| `--kinetics-ease-out` | Every animation | `cubic-bezier(0.23, 1, 0.32, 1)` |

## Dark mode

Dark mode uses warm charcoal with a hint of sepia, and off-white text. These
surfaces are fixed rather than taken from the `gray` palette; override them on
`.dark` to change them. Inputs get a stronger edge than dividers in dark mode,
so fields stay easy to find.

## Your own styles

Kinetics lives in its own cascade layer, between Filament's components and
Tailwind's utilities. `layers.css` sets that order:

```css
@layer theme, base, components, kinetics, utilities;
```

So a Tailwind class in your own Blade views wins over Kinetics without
`!important`. A plain CSS rule in your theme wins too, as long as it isn't
inside a lower layer.
