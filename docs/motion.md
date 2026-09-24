# Motion

Animation in Kinetics follows
[Emil Kowalski's practical animation tips](https://emilkowal.ski/ui/7-practical-animation-tips):
quick, eased out, and only where it helps.

## Rules

- **Under 300ms.** Interface animation should feel instant.
- **Ease out** for anything appearing or disappearing: it starts fast, so it
  feels responsive, and settles gently. Kinetics never uses ease-in, which
  feels sluggish.
- **Only transform and opacity** move. Colour changes on hover use a plain
  fade.
- **Nothing grows from zero.** Scaling starts from 0.95 or more.
- **Popovers grow from their trigger**, not from their centre.
- **Frequent actions don't animate.** Search results change on every
  keystroke, so they appear instantly.

## What moves

| Element | Motion |
|---|---|
| Buttons, icon buttons, page numbers | Scale to 0.97 while pressed, over 150ms |
| Dropdowns | Fade in and grow from 0.96 over 150ms. Table menus grow from the top right, the account menu upward from the bottom right |
| Modals | Open over 200ms and close over 150ms, from 0.95, with the backdrop fading in over 200ms |
| Notifications | Rise 1rem into place and fade in over 250ms. Leave by fading and shrinking to 0.95 |
| Hover colours | 150ms fade |

The easing curve is `--kinetics-ease-out`, `cubic-bezier(0.23, 1, 0.32, 1)`.

## Reduced motion

When the operating system is set to reduce motion, Filament makes every
transition near-instant, and Kinetics turns off the scaling and sliding on
buttons, dropdowns, modals and notifications, so nothing moves even for a
frame. Things still appear and disappear, just without animation.
