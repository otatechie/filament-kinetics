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

- **Checkboxes and radios** have a stronger outline than dividers, since they
  have no fill when unchecked.
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

## Motion

Kinetics respects the operating system's reduced-motion setting. See
[Motion](motion.md).

## Right-to-left

Spacing and corners use logical properties (start and end, not left and
right), so right-to-left languages mirror correctly. The switch's thumb moves
the other way in right-to-left layouts.
