# Components

How Kinetics styles each part of Filament. Sizes are at the default text size.

## Layout

| Part | Style |
|---|---|
| Canvas | The page colour, behind the sidebar and panel |
| Content panel | One shade lighter, 12px corners, no outline, 8px from the window edges |
| Page header | A slim band at the top of the panel: 15px title, buttons on the right, one line underneath |
| Sidebar | On the canvas, no divider. Items are 32px, with 16px icons. Groups are 12px apart, under 24px headings |
| Active menu item | Primary colour and semibold, no background |
| Collapsed sidebar | One centred column of icons, 40px targets |
| Notifications button | At the foot of the sidebar, styled like a menu item, with the unread count as a badge |
| Account menu | At the foot of the sidebar: 24px avatar centred on the menu's icons, name in line with the menu's labels, 200px menu, a small segmented theme switcher |

## Buttons

| Kind | Style |
|---|---|
| Primary | Solid primary (700 shade), white text, fully rounded |
| Secondary | Outlined, on the background colour |
| Destructive | A red tint with red text |
| In a dropdown | A text link in the primary colour |
| Sizes | xs 32px, sm 36px, md 40px, lg 44px, xl 48px. In the page header, md is 32px |

Every button scales to 0.97 while pressed.

## Forms

| Part | Style |
|---|---|
| Text inputs and selects | 40px, 8px corners, a 1px border. On focus, a primary border and a soft 3px ring |
| Invalid field | Red border and ring, with a small red message below |
| Labels | 12px, medium weight, above the field. Required fields get a red `*` |
| Help text | 12px, muted |
| Placeholder | Muted, at half strength |
| Checkboxes and radios | An outline when unchecked, solid primary when checked |
| Toggles | A compact 32×18 switch |
| Labels beside a checkbox or toggle | 14px body text, level with the control |
| Date picker | 6px rounded days. The selected day is solid primary, and today is in the primary colour |
| Select menus | Compact options, a grey hover, the selected option in the primary colour |

## Tables

| Part | Style |
|---|---|
| Container | No card, background or lines |
| Header row | Plain, medium-weight headings |
| Rows | 10px above and below the content. Hover gives a rounded tint |
| Edges | Headings, cells, toolbar and pagination share one 12px edge |
| References and codes | The interface font with even-width figures, muted |
| Icon columns | 18px |
| Sort arrows | 14px, muted |
| Toolbar | A 36px search box. The filter count only shows while filters are on |
| Pagination | The count on the left, small and muted. On the right, a borderless per-page select and page numbers as quiet buttons, with the current page in the primary colour |

## Tabs

| Kind | Style |
|---|---|
| On their own | A segmented control: a muted track with the active tab raised |
| Inside a card | Text tabs with a primary underline on the active tab |

## Overlays

| Part | Style |
|---|---|
| Modals | 12px corners and a hairline, sized to their form. Slide-overs stay square against the screen edge |
| Dropdowns | 8px corners, a soft shadow, faint dividers between sections, 16px icons |
| Notifications | Bottom-right, clear of the header's buttons. The card colour, a medium-weight title and muted body text |
| Notifications panel | A plain list, no boxes. Unread items have a primary dot and a semibold title; read items are quieter. Title, then message, then the time. Dismiss buttons show on hover or focus, and stay visible on touch screens |
| Tooltips | Inverted (dark on light, light on dark), 11px, 4px corners, no shadow |

## Other

| Part | Style |
|---|---|
| Badges | Fully rounded, tinted background, coloured text |
| Stats widgets | Large semibold figures with even-width digits, muted labels |
| Empty states | A small icon in a muted circle, a 15px heading, muted description |
| Sign-in page | No card: a 360px column on the canvas, with a small footer |
