# The Appearance page

A page inside your panel where the people you choose can change how it looks,
without editing code or deploying. A change saved there applies to everyone
who uses the panel.

The page is **off** until you turn it on.

## What people can change

| Section | Settings |
|---|---|
| Colours | The primary colour, and the tone of the greys: cool, neutral or warm |
| Shape | Corners, buttons, the page (a floating panel or flat), field borders |
| Type | The font: Open Runde, Inter or DM Sans |
| Dark mode | Whether it's allowed, which mode people start in, and the dark surfaces |
| Layout | Content width, the top bar, a collapsible sidebar, collapsible menu groups |
| Notifications | Bottom right or top right |

Each of these can also be set in code; see [the table below](#the-same-settings-in-code).

## Turning it on

**1. Create the table it saves to.** In your project's folder, run:

```bash
php artisan vendor:publish --tag=kinetics-migrations
php artisan migrate
```

The first command copies Kinetics' migration into `database/migrations`. The
second creates the table, `kinetics_appearance`. Without it, the page opens
but says "Settings can't be saved yet".

**2. Add the page to your panel.** Open your panel provider: it's in
`app/Providers/Filament/`, usually named `AdminPanelProvider.php`. Find the
line that adds Kinetics, `->plugin(KineticsPlugin::make())`, and change it to:

```php
->plugin(KineticsPlugin::make()->appearancePage(
    authorize: fn (User $user): bool => $user->email === 'you@example.com',
))
```

At the top of the file, with the other `use` lines, add `use App\Models\User;`
if it isn't there already. That's the model your panel's users sign in with;
if yours is different, use your own.

If your panel adds plugins as a list, `->plugins([...])`, change
`KineticsPlugin::make()` inside the list the same way.

**3. Decide who can open it.** `authorize` is given the signed-in user and
returns `true` for the people who may use the page. The example allows one
email address. Change it to however your app recognises an admin, such as
`$user->is_admin` if your `users` table has an `is_admin` column.

> **Without `authorize`, everyone who can sign in to the panel can change how
> it looks for everyone else.** Leave it out only if that's what you want.

**4. Check it.** Refresh the panel while signed in as someone `authorize`
allows: **Appearance** appears in the navigation. Signed in as anyone else,
it doesn't, and going to its address shows "Forbidden".

## Options

```php
->appearancePage(
    condition: true,
    authorize: fn (User $user): bool => $user->email === 'you@example.com',
    navigationGroup: 'Settings',
    navigationSort: 2,
)
```

| Option | What it does | If you leave it out |
|---|---|---|
| `condition` | `true` turns the page on, `false` turns it off | On |
| `authorize` | Who can open the page | Everyone who can use the panel |
| `navigationGroup` | The navigation group it's listed under | Not in a group |
| `navigationSort` | Its position in that group: lower numbers come first | Filament's default order |

### Turning it on and off from `.env`

Read the setting through a config file. `env()` called in the panel provider
stops working once a server caches its config with `php artisan config:cache`,
which is usual in production.

1. In `config/app.php`, inside the returned array, add
   `'appearance_page' => (bool) env('APPEARANCE_PAGE', false),`
2. In `.env`, add `APPEARANCE_PAGE=true` wherever you want the page.
3. In the panel provider, pass `condition: config('app.appearance_page')`.

## Turning it off

Remove `->appearancePage(...)` and keep `->plugin(KineticsPlugin::make())`,
or pass `condition: false`. While it's off:

- the page isn't in the navigation, and its address shows "Not Found";
- anything saved on it is ignored, and the panel looks as your code sets it;
- what was saved stays in the table, so turning the page back on brings it
  back.

## How saved changes work

- **Before anything is saved,** the panel looks as your code sets it. The page
  shows your code's colours, font, dark mode and layout. For shape and dark
  surfaces it shows Kinetics' defaults, since it can't read your `theme.css`.
- **Only what someone changes is saved.** Whatever they didn't touch is still
  decided by your code, so later changes to your code still apply to it.
- **A saved change wins over your code,** both your panel provider and your
  `theme.css`. The exception is a `->bootUsing()` callback, which runs after
  saved changes, so it wins.
- **"As set in code"** appears when your code chose something the page doesn't
  offer, such as a colour given as a hex code. Leave it selected to keep it.
- **Reset to defaults** deletes what was saved, so the panel goes back to how
  your code sets it.
- **After saving,** the page reloads. Everyone else sees the change the next
  time they open or refresh a page.
- **Each panel is separate.** Add `->appearancePage(...)` to each panel that
  should have the page. Each keeps its own saved settings.

## The same settings in code

| Setting | In code |
|---|---|
| Primary colour and tone | `->colors(['primary' => Color::Teal, 'gray' => Color::Stone])`. The tones are `Color::Slate` (cool), `Color::Neutral` and `Color::Stone` (warm). See [Colours](customising.md#colours) |
| Corners, buttons, page, field borders | The [recipes](customising.md#recipes) |
| Font | `->font()`, see [Font](customising.md#font) |
| Dark mode | `->darkMode()`, `->defaultThemeMode()`, and [a dark mode from your own greys](customising.md#a-dark-mode-from-your-own-greys) |
| Layout | `->maxContentWidth()`, `->topbar()`, `->sidebarCollapsibleOnDesktop()`, `->collapsibleNavigationGroups()` |
| Notifications | See [Notifications](customising.md#notifications) |

The page doesn't include settings that change how the panel behaves. Set those
in code, where you can test them against your own pages:

| Setting | In code |
|---|---|
| A ⌘K (Mac) or Ctrl+K shortcut to search | `->globalSearchKeyBindings(['command+k', 'ctrl+k'])` |
| Pages that change without a full reload | `->spa()`. Pages with their own JavaScript may need changes to work with it |
| A warning before leaving a form with unsaved changes | `->unsavedChangesAlerts()` |

## Changing the page's wording

1. In your app, create `lang/vendor/kinetics/en/appearance.php`, creating the
   folders if they don't exist.
2. Make it return only the text you want to change, under the same keys as
   [Kinetics' own file](../resources/lang/en/appearance.php). For example, to
   rename the page:

   ```php
   <?php

   return [
       'title' => 'Look and feel',
   ];
   ```

Everything you leave out keeps Kinetics' wording. To translate the page, use
your language's code in place of `en`, such as `fr`.

## Removing it

Remove `->appearancePage(...)`, then drop the table as in
[Removing Kinetics](installation.md#removing), step 6.

If something doesn't work, see
[Troubleshooting](troubleshooting.md#the-appearance-page-isnt-in-the-navigation).
