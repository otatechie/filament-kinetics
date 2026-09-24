# Changelog

All notable changes to Kinetics are recorded here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and versions follow
[Semantic Versioning](https://semver.org/).

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
  headings. See [Upgrading](docs/upgrading.md#from-03-to-04) to bring them back.
- The unread dot in the notifications panel is neutral, so the primary colour
  only marks things you can act on.
- The README has a compatibility table and badges.

## 0.3.1 - 2026-09-24

### Added
- Guides for [installation](docs/installation.md),
  [troubleshooting](docs/troubleshooting.md), [upgrading](docs/upgrading.md) and
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
