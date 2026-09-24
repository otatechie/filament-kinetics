# Changelog

All notable changes to Kinetics are recorded here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and versions follow
[Semantic Versioning](https://semver.org/).

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

Released from the [demo repository](https://github.com/otatechie/kinetics/releases/tag/v0.1.0),
before Kinetics became a package, so it has no tag here.

First tagged version: the canvas and panel layout, palette-driven colours with
passing contrast, Open Runde, Lucide icons, motion, tables, form controls,
overlays, sign-in page and dark mode, with the demo panel.
