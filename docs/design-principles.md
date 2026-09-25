# Design principles

Kinetics makes a Filament panel feel calm and quick to use. Every decision
below comes back to one of four ideas.

## 1. Surfaces, not boxes

Things are grouped by where they sit, not by lines drawn around them.

- The page is a **canvas**. The sidebar sits directly on it.
- The content is one rounded **panel**, a shade lighter than the canvas. It has
  no outline: the change in colour is the edge.
- **Tables** sit straight on the panel, with no card, background or lines
  between rows. A hovered row gets a soft, rounded tint.
- The **sign-in page** has no card: a narrow column on the canvas.
- The only line in a page is the one under its title.

Lines are kept for things that need an edge to be understood: inputs,
checkboxes and radios.

## 2. Quiet by default, clear when it matters

- **One accent colour**, used for what you can act on or where you are: buttons,
  links, the active menu item and the current page number. The panel's primary
  colour drives it.
- The **current page** in the menu shows in colour and weight alone. A filled
  pill looks like hover or a pressed button.
- **Secondary text** (descriptions, codes, counts) is muted, so the main value
  in each row stands out.
- **Destructive buttons** are a red tint, not a solid block.
- **Actions on table rows** are muted until pointed at, so a column of Edit
  and Delete links doesn't compete with the data, and Delete isn't red on
  every row.
- Inside dropdowns, buttons are **text links**, so they don't outweigh the
  options they confirm.

## 3. Compact and aligned

- Inputs and buttons are 40px tall and line up side by side. Page-header
  buttons are 32px, to fit the slim header band.
- Table rows have 10px above and below the content. Headings, cell text,
  pagination and the toolbar share one 12px edge.
- Small parts are sized to their text: 16px icons in menus, 14px sort arrows,
  an 18px switch, a 24px avatar.
- Modals are sized to their form, not Filament's wide default.

## 4. Fast, and never in the way

- Animations stay under 300ms, eased out, and only move or fade. See
  [Motion](motion.md).
- Search results, which change on every keystroke, don't animate.
- Everything works with the keyboard, and colours pass contrast checks. See
  [Accessibility](accessibility.md).

## What Kinetics doesn't do

- **It doesn't pick your colours or font.** Both come from the panel, so it
  fits any brand.
- **It doesn't override Filament's templates.** It restyles Filament through
  CSS, so Filament's behaviour, permissions and updates keep working.
- **It doesn't fight your own styles.** It sits in its own cascade layer, below
  Tailwind's utilities, so a normal class in your own views wins.
