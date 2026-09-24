# Accessibility

Kinetics keeps Filament's accessibility, and adds to it where the design makes
a difference.

## Contrast

- **Buttons and coloured text use the 700 shade** of the primary colour. White
  text on the 600 shade falls below WCAG's 4.5:1 for normal text; on 700 it
  passes:

  | Colour | White on 600 | White on 700 |
  |---|---|---|
  | Teal | 3.74:1 | 5.47:1 |
  | Orange | 3.56:1 | 5.18:1 |

- **Muted text** (descriptions, times, read notifications, "Clear") still
  passes 4.5:1, measured with the default `gray` palette:

  | Muted text on | Light | Dark |
  |---|---|---|
  | Cards and inputs | 4.84:1 | 5.65:1 |
  | The content panel | 4.63:1 | 6.16:1 |

- **Unchecked checkboxes and radios, and switches that are off,** have an edge
  that passes WCAG's 3:1 for controls against the card they sit on: 3.76:1 in
  light mode and 3.39:1 in dark, with the default `gray` palette.
- **Dark-mode inputs** have a stronger edge than dividers, so fields stay easy
  to find.

If you pick a very light primary colour, check the contrast of
`--kinetics-primary-button` against white.

## Focus

- Every input shows a primary border and a soft 3px ring when focused.
- Buttons and icon buttons show a ring when reached by keyboard.
- Invalid fields keep their red ring while focused.

## Keyboard

Kinetics doesn't change how anything works, only how it looks. Tab order,
shortcuts, Escape to close a modal and keyboard use of menus, selects and date
pickers all stay as Filament built them. Kinetics overrides none of Filament's
templates.

## Notifications

- **Unread isn't shown by colour alone.** Unread notifications have a dot, a
  shape you can see without telling colours apart, and a semibold title.
  Filament also labels each one "Unread notification" for screen readers.
- **Dismiss buttons are always reachable.** With a mouse they appear on hover,
  but they also appear on keyboard focus, and on touch screens, which have no
  hover, they're always shown.
- **"Clear" is quiet, not hidden.** It's grey until you point at or tab to it,
  then red, and stays in the tab order.
- **Pop-up notifications appear bottom-right**, away from the buttons you just
  used. Filament announces them to screen readers wherever they are.
- **Errors and warnings stay until they're closed**, so there's time to read
  them. Other notifications close after six seconds, and stay open while the
  pointer is over them.
- **Stacked notifications spread out on keyboard focus** as well as on hover.

## Motion

Kinetics respects the operating system's reduced-motion setting. See
[Motion](motion.md).

## Right-to-left

Spacing and corners use logical properties (start and end, not left and
right), so right-to-left languages mirror correctly. The switch's thumb moves
the other way in right-to-left layouts.
