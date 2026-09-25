<?php

// The Appearance page. Override in lang/vendor/kinetics/{locale}/appearance.php.
return [

    'title' => 'Appearance',
    'subheading' => 'How the panel looks, for everyone who uses it.',
    'set_in_code' => 'As set in code',

    'not_installed' => [
        'title' => 'Settings can\'t be saved yet',
        'description' => 'Publish and run Kinetics\' migration: php artisan vendor:publish --tag=kinetics-migrations, then php artisan migrate.',
    ],

    'colours' => [
        'heading' => 'Colours',
        'description' => 'Buttons, links and surfaces.',
        'primary' => 'Primary colour',
        'primary_help' => 'Marks what people can act on: buttons, links and the current page.',
        'gray' => 'Tone',
        'gray_help' => 'The tint of backgrounds, borders and grey text. The difference is subtle.',
        'tones' => ['Slate' => 'Cool', 'Neutral' => 'Neutral', 'Stone' => 'Warm'],
    ],

    'shape' => [
        'heading' => 'Shape',
        'description' => 'Corners, buttons and field edges.',
        'radius' => 'Corners',
        'radius_options' => ['square' => 'Square', 'default' => 'Rounded', 'round' => 'Round'],
        'buttons' => 'Buttons',
        'buttons_options' => ['pill' => 'Pill', 'rounded' => 'Same as corners'],
        'surface' => 'Page',
        'surface_options' => ['panel' => 'Floating panel', 'flat' => 'Flat'],
        'surface_help' => 'Flat drops the darker canvas around the page.',
        'field_borders' => 'Field borders',
        'field_borders_options' => ['light' => 'Light', 'strong' => 'Strong'],
        'field_borders_help' => 'Strong is easier to see on bright screens.',
    ],

    'type' => [
        'heading' => 'Type',
        'description' => 'The typeface for the whole panel.',
        'font' => 'Font',
        'font_help' => 'DM Sans loads from Bunny Fonts, so it needs an internet connection.',
    ],

    'dark_mode' => [
        'heading' => 'Dark mode',
        'description' => 'Whether people can use it, and how it looks.',
        'allow' => 'Allow dark mode',
        'allow_help' => 'Off, the panel is always light and the switch is gone.',
        'start_in' => 'Start in',
        'start_in_options' => ['system' => 'Match device', 'light' => 'Light', 'dark' => 'Dark'],
        'start_in_help' => 'For anyone who hasn\'t picked light or dark themselves.',
        'style' => 'Dark surfaces',
        'style_options' => ['warm' => 'Warm charcoal', 'palette' => 'Match tone'],
    ],

    'layout' => [
        'heading' => 'Layout',
        'description' => 'The page width, the top bar and the sidebar.',
        'width' => 'Content width',
        'width_options' => ['full' => 'Full', 'medium' => 'Medium', 'narrow' => 'Narrow'],
        'width_help' => 'Narrower is easier to read on wide screens. Full fits more table columns.',
        'topbar' => 'Top bar',
        'topbar_help' => 'A bar across the top with notifications. Search and the account menu stay in the sidebar.',
        'sidebar' => 'Collapsible sidebar',
        'sidebar_help' => 'A button at the top of the sidebar shrinks it to icons.',
        'groups' => 'Collapsible menu groups',
        'groups_help' => 'Arrows beside each menu group fold its pages away.',
    ],

    'notifications' => [
        'heading' => 'Notifications',
        'description' => 'Where messages appear after saving or deleting.',
        'position' => 'Position',
        'position_options' => ['bottom-right' => 'Bottom right', 'top-right' => 'Top right'],
        'position_help' => 'At the bottom, several notifications stack into a deck.',
    ],

    'actions' => [
        'save' => 'Save changes',
        'reset' => 'Reset to defaults',
        'reset_heading' => 'Reset the appearance?',
        'reset_description' => 'Everything on this page goes back to how the panel is set up in code, for everyone.',
        'reset_confirm' => 'Reset',
    ],

    'notifications_sent' => [
        'saved' => 'Appearance saved',
        'reset' => 'Appearance reset',
        'body' => 'Everyone sees the changes from their next page.',
    ],

];
