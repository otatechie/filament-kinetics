# Customising

Most of Kinetics is set by your panel. For the rest, change a variable.

## What the plugin sets

`KineticsPlugin::make()` changes these on its panel. Anything you call after
`->plugin()` overrides it.

| Setting | Kinetics sets | To change it, after the plugin |
|---|---|---|
| Font | Open Runde | `->font('Inter')` |
| Top bar | Off | `->topbar()` |
| Search | In the sidebar | `->globalSearch(position: GlobalSearchPosition::Topbar)` |
| Account menu | At the foot of the sidebar | `->userMenu(position: UserMenuPosition::Topbar)` |
| Sidebar | Collapsible on desktop | `->sidebarCollapsibleOnDesktop(false)` |
| Navigation groups | Always open, no arrows | `->collapsibleNavigationGroups()` |
| Breadcrumbs | Off | `->breadcrumbs()` |
| Content width | Full | `->maxContentWidth(Width::SevenExtraLarge)` |
| Sign-out item | No icon | `->userMenuItems(['logout' => fn (Action $action) => $action->icon('lucide-log-out')])` |
| Filament's icons | Lucide | `->icons([...])`, see [Icons](#icons) |
| Notifications | Bottom-right, stacked when there are several, errors and warnings stay until closed | `->bootUsing(...)`, see [Notifications](#notifications) |
| Modals | Aligned to the start, confirmations too, with no icon beside the heading | See [Modals](#modals) |
| Empty tables | When a search or filters match nothing, say so and offer to clear them | See [Empty tables](#empty-tables) |

It also adds a small script that warns in the browser console if the theme is
missing or loaded in the wrong order, and stops a panel that has no custom
theme. See [Troubleshooting](troubleshooting.md).

It doesn't set your colours, brand name, logo or navigation.

With [the Appearance page](#the-appearance-page) turned on, choices saved there
apply on top of all of this.

## The Appearance page

The Appearance page lets people change how the panel looks from inside the
panel, without editing code or deploying: colours, corners, font, dark mode
and layout. A change saved there applies to everyone who uses the panel.

The page is **off** until you turn it on, and you choose who can open it.

### Turning it on

**1. Create the table it saves to.** In your project's folder, run:

```bash
php artisan vendor:publish --tag=kinetics-migrations
php artisan migrate
```

The first command copies Kinetics' migration into your `database/migrations`
folder. The second creates the table, `kinetics_appearance`. If you skip this
step, the page opens but says "Settings can't be saved yet".

**2. Add the page to your panel.** Open your panel provider. It's in
`app/Providers/Filament/`, usually named `AdminPanelProvider.php`.

At the top of the file, with the other `use` lines, add this line if it isn't
there already. It's the model your panel's users sign in with; if yours isn't
`App\Models\User`, use your own:

```php
use App\Models\User;
```

Then find the line that adds Kinetics, `->plugin(KineticsPlugin::make())`,
and add `->appearancePage(...)` to it:

```php
->plugin(KineticsPlugin::make()->appearancePage(
    authorize: fn (User $user): bool => $user->email === 'you@example.com',
))
```

If your panel adds plugins as a list instead, `->plugins([...])`, change
`KineticsPlugin::make()` inside the list the same way.

The `authorize` line decides who can open the page. It's given the signed-in
user and returns `true` for people who may use it. The example allows one
email address. Replace it with however your app recognises an admin, for
example `$user->is_admin` if your `users` table has an `is_admin` column.

**Leave `authorize` out only if everyone who can sign in to the panel may
change its look.** Without it, they all can.

**3. Check it.** Refresh the panel while signed in as someone `authorize`
allows. **Appearance** appears in the navigation. Signed in as anyone else,
it doesn't.

### All the options

```php
->appearancePage(
    condition: true,
    authorize: fn (User $user): bool => $user->is_admin,
    navigationGroup: 'Settings',
    navigationSort: 2,
)
```

| Option | What it does | If you leave it out |
|---|---|---|
| `condition` | `true` turns the page on, `false` turns it off. To switch it from `.env`, see below | On |
| `authorize` | Who can open the page, as above | Everyone who can use the panel |
| `navigationGroup` | The navigation group it's listed under, such as `'Settings'` | Not in a group |
| `navigationSort` | Its position in that group: lower numbers come first | Filament's default order |

To switch the page on and off from `.env`, read it through a config file.
Calling `env()` in the panel provider stops working once your production
server caches its config with `php artisan config:cache`:

1. In `config/app.php`, inside the returned array, add
   `'appearance_page' => (bool) env('APPEARANCE_PAGE', false),`
2. In `.env`, add `APPEARANCE_PAGE=true` where you want the page.
3. In the panel provider, pass `condition: config('app.appearance_page')`.

### Turning it off

Remove `->appearancePage(...)` from the plugin line, keeping
`->plugin(KineticsPlugin::make())` itself, or pass `condition: false`. While
it's off:

- the page isn't in the navigation, and going to its address, such as
  `/admin/appearance`, shows "Not Found";
- anything saved on it is ignored, and the panel looks as your code sets it;
- what was saved stays in the table, so turning the page back on brings it
  back.

Someone `authorize` turns away sees the same navigation without the page, and
gets "Forbidden" if they go to its address.

### What people can change

| Section | Settings | The same thing in code |
|---|---|---|
| Colours | Primary colour, and tone: cool, neutral or warm greys | `->colors()` with `Color::Slate`, `Color::Neutral` or `Color::Stone` as `gray`, see [Colours](#colours) |
| Shape | Corners, buttons, page (floating panel or flat), field borders | The [recipes](#recipes) below |
| Type | Open Runde, Inter or DM Sans. DM Sans loads from Bunny Fonts, so it needs an internet connection | `->font()` |
| Dark mode | Whether it's allowed, which mode people start in until they pick light or dark themselves, and dark surfaces in warm charcoal or matching the tone | `->darkMode()`, `->defaultThemeMode()`, [a dark mode from your own greys](#a-dark-mode-from-your-own-greys) |
| Layout | Content width (full, medium or narrow), top bar, collapsible sidebar, collapsible menu groups | `->maxContentWidth()`, `->topbar()`, `->sidebarCollapsibleOnDesktop()`, `->collapsibleNavigationGroups()` |
| Notifications | Bottom right or top right | See [Notifications](#notifications) |

The page only changes how the panel looks. Settings that change how it
behaves are left to your code, where you can test them against your own
pages:

| Setting | In code |
|---|---|
| A ⌘K (Mac) or Ctrl+K shortcut to search | `->globalSearchKeyBindings(['command+k', 'ctrl+k'])` |
| Pages that change without a full reload | `->spa()`. Pages with their own JavaScript may need changes to work with it |
| A warning before leaving a form with unsaved changes | `->unsavedChangesAlerts()` |

### How saved changes work

- **Before anything is saved,** the panel looks exactly as your code sets it.
  The page shows your code's colours, font, dark mode and layout. For the
  shape settings and dark surfaces it shows Kinetics' defaults, since it
  can't read your `theme.css`.
- **Only what someone changes is saved.** Everything they didn't touch is
  still decided by your code, so later changes to your code still apply.
- **A saved change wins over your code,** both your panel provider and your
  `theme.css`. The one exception is code in a `->bootUsing()` callback, which
  runs after saved changes, so it wins.
- **"As set in code"** appears when your code chose something the page doesn't
  offer, such as a colour given as a hex code. Leave it selected and your
  code's choice stays.
- **Reset to defaults** deletes what was saved. The panel goes back to how
  your code sets it.
- **After saving,** the page reloads. Everyone else sees the change the next
  time they open or refresh a page.
- **Each panel is separate.** With more than one panel, add
  `->appearancePage(...)` to each one that should have the page. Each keeps
  its own saved settings.

### Changing the page's wording

1. In your app, create the file `lang/vendor/kinetics/en/appearance.php`,
   creating the folders if they don't exist.
2. Make it return an array with only the text you want to change, under the
   same keys as [Kinetics' own file](../resources/lang/en/appearance.php).
   For example, to rename the page:

   ```php
   <?php

   return [
       'title' => 'Look and feel',
   ];
   ```

Everything you leave out keeps Kinetics' wording. To translate the page, use
your language's code in place of `en`, such as `fr`.

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

Kinetics is designed around Filament's own palettes, and any of them works.
If you'd rather not choose, the [live demo](https://kinetics.atoaugustine.com/admin)
uses warm orange on neutral greys:

```php
$panel->colors([
    'primary' => Color::Orange,
    'gray' => Color::Gray,
]);
```

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

When several arrive, they stack into a deck: the newest in front, the older
ones peeking out above it. Hover or focus spreads them out. The deck is only
in the bottom corners; at the top, notifications list as Filament shows them.

Errors and warnings stay until they're closed, since they usually need
something done. Other notifications close after six seconds, as in Filament.
To change one, set its duration:

```php
Notification::make()
    ->title('The refund was not requested')
    ->danger()
    ->seconds(10)
    ->send();
```

## Modals

Every modal reads the same way: heading and text from the left, then the
button that acts, then Cancel. Filament centres confirmations, such as Delete,
and puts an icon above them. Kinetics doesn't: the heading says what the modal
is for, and a red button already marks a destructive one.

To centre one action's modal again, set it on the action:

```php
use Filament\Support\Enums\Alignment;

DeleteAction::make()
    ->modalAlignment(Alignment::Center)
    ->modalFooterActionsAlignment(Alignment::Center);
```

Kinetics hides the icon in its CSS. To show icons again, add this to your
theme after the Kinetics import:

```css
.fi-modal-window .fi-modal-icon-ctn {
    display: flex;
}
```

## Empty tables

When a search or filters leave a table with nothing to show, Filament says
"No orders", as if there were none. Kinetics says what happened instead,
`No results for “ok”` or "No orders match these filters", and adds a button
that clears the search, the filters or both. Like modals, empty tables have no
icon above the heading. With only a search on, the row of active filters is
hidden, since the search box already shows it.

A table's own `->emptyStateHeading()`, `->emptyStateDescription()` or
`->emptyStateActions()` still wins. To change the wording everywhere, publish
your own copy of the translations to `lang/vendor/kinetics/en/tables.php`:

```php
return [
    'empty' => [
        'search' => [
            'heading' => 'Nothing found for “:search”',
        ],
    ],
];
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
| `--kinetics-control-border` | Unchecked checkboxes and radios, and switches that are off | `gray-400` mixed 40% with `gray-500` | `#7c7973` |

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

## Recipes

Each of these goes in your `theme.css`, after the Kinetics import.

### Rounder or squarer corners

One variable sets the corners of sections, inputs, modals, dropdowns and
notifications:

```css
:root {
    --kinetics-radius: 0.75rem; /* 0.25rem for squarer */
}
```

### Square buttons instead of pills

Buttons are pill-shaped. To give them the same corners as everything else:

```css
.fi-btn {
    border-radius: var(--kinetics-radius);
}
```

### One flat surface

The page sits on a rounded panel over a slightly darker canvas. To make it one
surface, give the canvas the panel's colour:

```css
:root {
    --kinetics-canvas: var(--kinetics-panel);
}
```

### A dark mode from your own greys

Dark mode uses fixed warm charcoal. To follow the panel's `gray` palette
instead:

```css
.dark {
    --kinetics-canvas: var(--gray-950);
    --kinetics-panel: var(--gray-950);
    --kinetics-panel-border: var(--gray-800);
    --kinetics-card: var(--gray-900);
    --kinetics-card-border: var(--gray-800);
    --kinetics-background: var(--gray-950);
    --kinetics-muted: var(--gray-800);
    --kinetics-border: var(--gray-800);
    --kinetics-input-border: var(--gray-700);
    --kinetics-foreground: var(--gray-50);
    --kinetics-muted-foreground: var(--gray-400);
}
```

### Stronger field borders

Inputs have a light edge in light mode. For more contrast:

```css
:root {
    --kinetics-input-border: var(--gray-300);
}
```

## Your own styles

Kinetics lives in its own cascade layer, between Filament's components and
Tailwind's utilities. `layers.css` sets that order:

```css
@layer theme, base, components, kinetics, utilities;
```

So a Tailwind class in your own Blade views wins over Kinetics without
`!important`. A plain CSS rule in your theme wins too, as long as it isn't
inside a lower layer.

## Matching your own pages

Custom pages and widgets can use Kinetics' variables, so they follow the theme
in light and dark mode. In Blade, with Tailwind:

```blade
<p class="text-sm text-(--kinetics-muted-foreground)">
    Sellers are paid the next working day.
</p>

<div class="rounded-(--kinetics-radius) bg-(--kinetics-card) p-4">
    ...
</div>
```

Or in your theme's CSS, after the Kinetics import:

```css
.payout-note {
    color: var(--kinetics-muted-foreground);
    border-radius: var(--kinetics-radius);
}
```

Filament's own components, such as `<x-filament::section>` and
`<x-filament::button>`, are styled by Kinetics already, so prefer them where
they fit.

## A footer on the sign-in page

Kinetics styles a small, muted footer under the sign-in form. It's most useful
for someone who can't get in, so say how to get help rather than only your
name or a copyright line.

To add one:

**1.** Open your panel provider. It's in `app/Providers/Filament/`, usually
named `AdminPanelProvider.php`.

**2.** At the top of the file, with the other `use` lines, add:

```php
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
```

**3.** In the `panel()` method, add a `->renderHook(...)` call to the chain
of settings on `$panel`, before the `;` that ends it. A render hook adds your
own HTML at a fixed place on Filament's pages; `SIMPLE_LAYOUT_END` is the
bottom of the sign-in page:

```php
return $panel
    // ...your other settings
    ->plugin(KineticsPlugin::make())
    ->renderHook(
        PanelsRenderHook::SIMPLE_LAYOUT_END,
        fn (): HtmlString => new HtmlString('<footer class="fi-simple-footer">Trouble signing in? support@example.com</footer>'),
    );
```

**4.** Change the text between `<footer ...>` and `</footer>` to your own,
and refresh the sign-in page. There's nothing to rebuild. Keep
`class="fi-simple-footer"`: it's what gives the footer Kinetics' small, muted
style.

With a link:

```php
new HtmlString('<footer class="fi-simple-footer">Trouble signing in? <a href="mailto:support@example.com">support@example.com</a></footer>')
```

With text from your app's config or database, wrap it in `e()`. That escapes
it, so text containing `<` or `&` shows as written instead of breaking the
page:

```php
new HtmlString('<footer class="fi-simple-footer">'.e(config('app.name')).' · '.e(config('mail.from.address')).'</footer>')
```

It shows on every page with the centred layout: sign-in, registration,
password reset and the two-factor code page, but not inside the panel. To
remove it, delete the `->renderHook()` call.
