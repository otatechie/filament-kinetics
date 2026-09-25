# Changelog

All notable changes to Kinetics are recorded here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and versions follow
[Semantic Versioning](https://semver.org/).

## 1.5.1 - 2026-09-25

### Changed
- On phones, actions on table rows show just their icon, so the row's data
  has the width. The word is still there for screen readers.

### Fixed
- "Reset" in the filters panel was red, as if it deleted something. It's
  muted until pointed at.
- The README screenshots show the current look.

## 1.5.0 - 2026-09-25

### Changed
- Actions on table rows are muted, text and icon, and take their own colour
  when pointed at or focused: the primary colour for Edit, red for Delete.
  Filament coloured them differently from page to page, and a red Delete on
  every row drew the eye to the action least wanted by mistake.
- Coloured badges are tinted with the 100 shade instead of 50 in light mode.
  The 50 tint was nearly the panel's colour, so a badge read as coloured
  text.

- Success, warning, info and custom-coloured buttons are solid like
  primary: the 700 shade with white text. Filament's bright fills with dark
  text made green and amber the loudest buttons on a page.
- Disabled buttons are grey and flat whatever their colour. Faded to 70%, a
  disabled orange button looked like a lighter orange one.
- Badge text in light mode is the 800 shade, at least 6.3:1 on its tint.

### Fixed
- Red buttons failed contrast: red text on the red tint was 3.1:1 in light
  mode and 4.1:1 in dark. It's 5.3:1 and 6.2:1 now.
- Blue badges failed contrast in light mode, at 4.3:1.
- Outlined buttons had no outline, so they looked like links.
- Badge sizes were ignored: xs, sm and md all showed at 20px. They're 16px,
  18px and 20px now, with lg at 24px.
- In infolists with labels beside their values (`->inlineLabel()`), each
  label sat 3px above its value. It's on the value's baseline now.

## 1.4.0 - 2026-09-25

### Added
- An Appearance page, off unless you turn it on with
  `KineticsPlugin::make()->appearancePage()`. The primary colour, tone,
  corners, buttons, font, dark mode, content width, the top bar, the sidebar
  and where notifications appear can be changed from the panel, for
  everyone, by the people you allow. It needs a table:
  `php artisan vendor:publish --tag=kinetics-migrations`, then migrate. See
  [The Appearance page](docs/appearance-page.md).

### Changed
- Toggle buttons in a row look like standalone tabs: one track, the chosen
  option raised. On phones, a row of up to four fills the width instead of
  wrapping onto a second line, and on touch screens each option is 40px tall.
- In dark mode, the chosen toggle button and the active standalone tab are
  lifted instead of looking sunken.
- The description under a switch or a row of toggle buttons is a step
  smaller than its label: 12px.

### Fixed
- Links in the sign-in page's footer are in the primary colour and
  underlined. They looked like the text around them.
- With the top bar on, the notification badge on the bell was cut off at the
  window's edge, and sat on a white patch instead of the top bar's grey.
- With the top bar on, its icons (the sidebar toggle and the bell) were 24px
  beside 16px menu icons. They're 18px, as in the sidebar.

## 1.3.2 - 2026-09-24

### Fixed
- Switches that are on show the primary colour again. Kinetics' grey track
  covered it, so on and off differed only by where the thumb sat.

## 1.3.1 - 2026-09-24

### Fixed
- Switches that are off were a faint stripe on white cards, at 1.2:1. The
  track now uses the same edge as an unchecked checkbox.
- That edge is stronger, so unchecked checkboxes, radios and off switches pass
  WCAG's 3:1 for controls: 3.76:1 in light mode and 3.39:1 in dark, up from
  2.6:1.

## 1.3.0 - 2026-09-24

### Added
- Notifications in the bottom corner stack into a deck when there are several:
  the newest in front, older ones peeking out above it. Hover or focus spreads
  them out. With reduced motion on, they stack without moving.

### Changed
- Errors and warnings stay until they're closed, instead of disappearing after
  six seconds. A notification's own `->duration()` or `->seconds()` still wins.
- Notification pop-ups are narrower: 356px instead of 384px.

## 1.2.2 - 2026-09-24

### Fixed
- Clearing a search no longer shows the row of active filters for a moment
  before it disappears.

## 1.2.1 - 2026-09-24

### Changed
- Empty tables have no icon above the heading, as in modals: the heading says
  what happened.
- With only a search on, the row of active filters is hidden, since the search
  box already shows it. It still appears once a filter is on.

## 1.2.0 - 2026-09-24

### Added
- A table emptied by a search or filters says so: `No results for “ok”` or
  "No orders match these filters", with a button that clears them. Filament
  says "No orders", as if there were none. A table's own empty state still
  wins, and the wording can be translated; see
  [Customising: Empty tables](docs/customising.md#empty-tables).

### Changed
- The row of active filters above a table has no background or line of its
  own, sits on the columns' edge, and keeps "clear all" beside the badges.

## 1.1.1 - 2026-09-24

### Changed
- A plainer package description: "A minimal theme for Filament 4 and 5."
- The README's feature list says what each feature does, in plainer words, and
  now includes modals and the missing-plugin check.

## 1.1.0 - 2026-09-24

### Added
- Filament 4 support, from 4.13.3, on Laravel 12. Kinetics looks the same on
  Filament 4 as on 5.
- [Recipes](docs/customising.md#recipes) for common changes: rounder or
  squarer corners, square buttons, one flat surface, a dark mode from your own
  greys, and stronger field borders.
- A suggested palette, for anyone who'd rather not choose one.

## 1.0.0 - 2026-09-24

The first release.

## 0.5.1 - 2026-09-24

### Added
- A browser console warning when a panel's theme includes Kinetics' CSS but
  the panel doesn't have the plugin. That panel gets Kinetics' colours on
  Filament's layout, font and icons, and before, nothing said why.

### Changed
- On phones, modal buttons sit one per row at full width, with the button that
  acts on top. Before, a third button, such as *Create & create another*,
  pushed Cancel onto a row of its own.

## 0.5.0 - 2026-09-24

### Changed
- Confirmation modals, such as Delete, read like every other modal: from the
  left, with the button that acts first and Cancel after it. Filament centres
  them. An action's own `->modalAlignment()` still wins.
- No modal shows an icon beside its heading. The heading says what the modal
  is for, and a red button already marks a destructive one. See
  [Customising: Modals](docs/customising.md#modals) to bring icons back.

## 0.4.2 - 2026-09-24

### Added
- Copyable text (`Text::make()->copyable()`) shows a copy icon, since Filament
  gives no sign it can be copied until it's clicked.

### Changed
- On desktops, pages sit 48px in from the panel's edges instead of 32px, and
  the page title starts where the content does instead of 8px to its left.
- Modals have the same edge as dropdowns and toasts: a solid border, the
  Kinetics radius and a soft shadow. Before, a faint ring and a long shadow
  made the bottom edge heavier than the top, and in dark mode the edge almost
  disappeared. Modal headers and footers follow the rounded corners, instead of
  painting square corners over the border.
- Modals built as steps, such as setting up two-factor authentication, put the
  primary button first and Cancel or Back after it, together at the start, as
  other modals do. Before, the buttons sat at opposite edges in the reverse
  order.

### Fixed
- Aside sections (`Section::make()->aside()`) show their heading beside the
  card, as in Filament, instead of inside a card that also wrapped the heading.

## 0.4.1 - 2026-09-24

### Fixed
- On phones and tablets, the content panel runs edge to edge instead of
  floating with a strip of background down each side.
- On phones and tablets, the menu button sits on the page title's edge, at the
  size of Kinetics' other header icons, instead of alone in a strip of its own.

## 0.4.0 - 2026-09-24

### Added
- Tables wider than their space show a soft shadow on each side that has more
  to scroll to, since macOS hides scrollbars. It's painted behind the table,
  so dropdowns inside it aren't clipped.
- An issue template that asks for versions, the load-order check and your
  `theme.css`, a security policy, and Dependabot for the dev tools and CI.

### Changed
- Navigation groups no longer collapse, so there are no arrows beside group
  headings. `->collapsibleNavigationGroups()` after the plugin brings them
  back.
- The unread dot in the notifications panel is neutral, so the primary colour
  only marks things you can act on.
- The README has a compatibility table and badges.

## 0.3.1 - 2026-09-24

### Added
- Guides for [installation](docs/installation.md),
  [troubleshooting](docs/troubleshooting.md), upgrading and
  [development](docs/development.md), a reference of everything the plugin sets,
  recipes for styling your own pages and adding a sign-in footer, and
  screenshots in the README.

### Fixed
- The load-order check (`--kinetics-layer-order`) returned `ok` even when
  `layers.css` was missing or imported after Filament's theme. It now returns
  `wrong` in that case, and the console warning says to move `layers.css`.

## 0.3.0 - 2026-09-24

### Added
- `resources/css/layers.css`, to import before Filament's theme in place of
  typing the `@layer` order yourself.
- A panel without a custom theme stops with an error explaining how to set one
  up, instead of getting Kinetics' layout with Filament's look.
- A browser console warning when the panel's theme is missing the Kinetics
  imports.

### Changed
- The sidebar is more compact: 12px between groups and 24px group headings.
  Menu rows stay 32px.
- The notifications panel (`->databaseNotifications()`) is a plain list on the
  panel's surface instead of stacked cards. Unread items get a dot and a
  semibold title, the time moves under the message, and dismiss buttons show on
  hover. The header drops its unread count (the list's dots and the sidebar
  button already show it) and its divider, and "Clear" stays grey until you
  point at it.
- The notifications button and account menu at the foot of the sidebar line up
  with the menu above.
- Notifications appear in the bottom-right corner, clear of the page header's
  buttons, and rise into place over 250ms instead of sliding in from the side.
- Lucide icons are set through the panel's `->icons()`, so an `->icons()` call
  after the plugin overrides them, like any other panel setting.
- The variable tables in [Customising](docs/customising.md) list light and dark
  defaults for every variable.

### Fixed
- Sidebar items with a badge, like a count, were 4px taller than the rest.
- Slide-over headers used Filament's grey in dark mode instead of Kinetics'
  surface colour.

### Removed
- The ready-made theme. It bundled a compiled copy of Filament's CSS, so a
  Filament update could leave panels half-styled until Kinetics was rebuilt.
  Kinetics now always builds with your own theme, against the Filament version
  you have.

## 0.2.0 - 2026-09-24

### Added
- Kinetics is a Composer package, `otatechie/filament-kinetics`. `KineticsPlugin`
  loads a ready-made theme, Open Runde and Lucide icons, and sets up the sidebar
  layout. Apps with their own theme can import `kinetics.css` instead.
- `--kinetics-layer-order`, to check the layer order loaded correctly.
- Documentation in `docs/`.

### Changed
- Kinetics now lives in its own cascade layer (`@layer kinetics`), between
  Filament's components and Tailwind's utilities. It restyles Filament without
  `!important`, and your own Tailwind classes can override it.
- Theme variables are public and renamed from `--g-*` to `--kinetics-*`. See
  [Customising](docs/customising.md).

## 0.1.0 - 2026-09-24

Released from the demo app, before Kinetics became a package, so it has no tag
here.

First tagged version: the canvas and panel layout, palette-driven colours with
passing contrast, Open Runde, Lucide icons, motion, tables, form controls,
overlays, sign-in page and dark mode, with the demo panel.
