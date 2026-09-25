<?php

namespace Otatechie\Kinetics\Appearance;

use Filament\Enums\ThemeMode;
use Filament\FontProviders\BunnyFontProvider;
use Filament\FontProviders\LocalFontProvider;
use Filament\Notifications\Livewire\Notifications;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\HtmlString;
use Throwable;

/**
 * What the Appearance page saves for one panel, and how it's applied. Only
 * saved values are applied, so until something is saved the panel keeps its
 * own configuration.
 */
class AppearanceSettings
{
    public const TABLE = 'kinetics_appearance';

    /** A choice the panel's own code made, which the page leaves alone. */
    public const CUSTOM = 'custom';

    public const PRIMARY_COLORS = ['Orange', 'Amber', 'Emerald', 'Teal', 'Sky', 'Blue', 'Indigo', 'Violet', 'Pink', 'Rose'];

    /** Cool, neutral and warm: Tailwind's other greys differ too little to tell apart. */
    public const GRAY_COLORS = ['Slate', 'Neutral', 'Stone'];

    /** @var array<string, array{string, class-string}> The name shown => the family and where it loads from. */
    public const FONTS = [
        'Open Runde' => ['Open Runde', LocalFontProvider::class],
        'Inter' => ['Inter Variable', LocalFontProvider::class],
        'DM Sans' => ['DM Sans', BunnyFontProvider::class],
    ];

    public const RADII = ['square' => '0.25rem', 'default' => '0.5rem', 'round' => '0.75rem'];

    public const WIDTHS = ['full' => Width::Full, 'medium' => Width::FiveExtraLarge, 'narrow' => Width::FourExtraLarge];

    /** Kinetics' own look, which only its CSS knows about. */
    public const LOOK_DEFAULTS = [
        'radius' => 'default',
        'buttons' => 'pill',
        'surface' => 'panel',
        'field_borders' => 'light',
        'dark_style' => 'warm',
    ];

    public const KEYS = [
        'primary_color', 'gray_color', 'radius', 'buttons', 'surface', 'field_borders', 'font',
        'dark_mode', 'theme_mode', 'dark_style', 'content_width', 'topbar', 'sidebar_collapsible',
        'collapsible_groups', 'notifications',
    ];

    /** @var array<string, string>|null */
    private ?array $saved = null;

    public function __construct(
        private readonly string $panel,
    ) {}

    /** @return array<string, string> */
    public function saved(): array
    {
        return $this->saved ??= $this->load();
    }

    /** @param  array<string, mixed>  $values */
    public function save(array $values): void
    {
        $values = collect($values)
            ->only(self::KEYS)
            ->map(fn (mixed $value): string => is_bool($value) ? ($value ? '1' : '0') : (string) $value)
            ->reject(fn (string $value): bool => $value === self::CUSTOM)
            ->all();

        DB::table(self::TABLE)->updateOrInsert(
            ['panel' => $this->panel],
            ['settings' => json_encode($values), 'updated_at' => now()],
        );

        $this->saved = null;
    }

    public function reset(): void
    {
        DB::table(self::TABLE)->where('panel', $this->panel)->delete();

        $this->saved = null;
    }

    public static function isInstalled(): bool
    {
        try {
            return Schema::hasTable(self::TABLE);
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * What the page shows: saved values, and for everything else what the
     * panel is doing now.
     *
     * @return array<string, string|bool>
     */
    public function current(Panel $panel): array
    {
        $saved = $this->saved();

        $values = [
            ...self::LOOK_DEFAULTS,
            'primary_color' => self::colorName('primary', self::PRIMARY_COLORS),
            'gray_color' => self::colorName('gray', self::GRAY_COLORS),
            'font' => self::fontName($panel->getFontFamily()),
            'dark_mode' => $panel->hasDarkMode(),
            'theme_mode' => $panel->getDefaultThemeMode()->value,
            'content_width' => array_search($panel->getMaxContentWidth(), self::WIDTHS, true) ?: self::CUSTOM,
            'topbar' => $panel->hasTopbar(),
            'sidebar_collapsible' => $panel->isSidebarCollapsibleOnDesktop(),
            'collapsible_groups' => $panel->hasCollapsibleNavigationGroups(),
            'notifications' => Notifications::$verticalAlignment === VerticalAlignment::Start ? 'top-right' : 'bottom-right',
        ];

        foreach (self::LOOK_DEFAULTS as $key => $default) {
            $values[$key] = $saved[$key] ?? $default;
        }

        return $values;
    }

    /**
     * Applies what's been saved. Runs in the plugin's boot(), after the
     * panel's own configuration and before its ->bootUsing() callbacks.
     */
    public function apply(Panel $panel): void
    {
        $saved = $this->saved();

        $colors = array_filter([
            'primary' => self::palette($saved['primary_color'] ?? null, self::PRIMARY_COLORS),
            'gray' => self::palette($saved['gray_color'] ?? null, self::GRAY_COLORS),
        ]);

        if ($colors !== []) {
            FilamentColor::register($colors);
        }

        if (isset(self::FONTS[$saved['font'] ?? ''])) {
            [$family, $provider] = self::FONTS[$saved['font']];

            // Named with its provider: a font set without one keeps the
            // previous font's, which would look for DM Sans in local files.
            $panel->font($family, provider: $provider);
        }

        if (isset($saved['theme_mode'])) {
            $panel->defaultThemeMode(ThemeMode::tryFrom($saved['theme_mode']) ?? ThemeMode::System);
        }

        if (isset(self::WIDTHS[$saved['content_width'] ?? ''])) {
            $panel->maxContentWidth(self::WIDTHS[$saved['content_width']]);
        }

        foreach ([
            'dark_mode' => fn (bool $on) => $panel->darkMode($on),
            'topbar' => fn (bool $on) => $panel->topbar($on),
            'sidebar_collapsible' => fn (bool $on) => $panel->sidebarCollapsibleOnDesktop($on),
            'collapsible_groups' => fn (bool $on) => $panel->collapsibleNavigationGroups($on),
        ] as $key => $set) {
            if (isset($saved[$key])) {
                $set($saved[$key] === '1');
            }
        }

        if (isset($saved['notifications'])) {
            Notifications::verticalAlignment($saved['notifications'] === 'top-right' ? VerticalAlignment::Start : VerticalAlignment::End);
        }
    }

    /**
     * The shape and dark-mode choices, as the recipes in Kinetics' customising
     * guide. Plain CSS in the page, so it wins over Kinetics' layer without
     * !important.
     */
    public function css(): HtmlString
    {
        $chosen = array_intersect_key($this->saved(), self::LOOK_DEFAULTS);
        $look = [...self::LOOK_DEFAULTS, ...$chosen];
        $rules = [];

        // Only what was chosen here, so a radius set in the theme's CSS
        // stays until someone picks corners on the page.
        if (isset($chosen['radius'])) {
            $rules[] = ':root { --kinetics-radius: '.(self::RADII[$look['radius']] ?? self::RADII['default']).'; }';
        }

        if ($look['buttons'] === 'rounded') {
            $rules[] = '.fi-btn { border-radius: var(--kinetics-radius); }';
        }

        if ($look['surface'] === 'flat') {
            $rules[] = ':root { --kinetics-canvas: var(--kinetics-panel); }';
        }

        if ($look['dark_style'] === 'palette') {
            $rules[] = '.dark { --kinetics-canvas: var(--gray-950); --kinetics-panel: var(--gray-950); --kinetics-panel-border: var(--gray-800); --kinetics-card: var(--gray-900); --kinetics-card-border: var(--gray-800); --kinetics-background: var(--gray-950); --kinetics-muted: var(--gray-800); --kinetics-border: var(--gray-800); --kinetics-input-border: var(--gray-700); --kinetics-foreground: var(--gray-50); --kinetics-muted-foreground: var(--gray-400); }';

            if ($look['surface'] === 'flat') {
                $rules[] = '.dark { --kinetics-canvas: var(--kinetics-panel); }';
            }
        }

        // After the dark surfaces, so strong borders win in dark mode too.
        if ($look['field_borders'] === 'strong') {
            $rules[] = ':root { --kinetics-input-border: var(--gray-300); }';
            $rules[] = $look['dark_style'] === 'palette'
                ? '.dark { --kinetics-input-border: var(--gray-600); }'
                : '.dark { --kinetics-input-border: #6a6762; }';
        }

        if ($rules === []) {
            return new HtmlString('');
        }

        return new HtmlString('<style id="kinetics-appearance">'.implode("\n", $rules).'</style>');
    }

    /**
     * @param  array<string>  $allowed
     * @return array<int|string, string>|null
     */
    public static function palette(?string $name, array $allowed): ?array
    {
        return in_array($name, $allowed, true) ? constant(Color::class.'::'.$name) : null;
    }

    /** @param  array<string>  $names */
    private static function colorName(string $color, array $names): string
    {
        $shade = FilamentColor::getColor($color)[500] ?? null;

        foreach ($names as $name) {
            if (self::palette($name, $names)[500] === $shade) {
                return $name;
            }
        }

        return self::CUSTOM;
    }

    private static function fontName(string $family): string
    {
        foreach (self::FONTS as $name => [$fontFamily]) {
            if (in_array($family, [$name, $fontFamily], true)) {
                return $name;
            }
        }

        return self::CUSTOM;
    }

    /** @return array<string, string> */
    private function load(): array
    {
        try {
            $settings = DB::table(self::TABLE)->where('panel', $this->panel)->value('settings');
        } catch (Throwable) {
            // Not migrated yet.
            return [];
        }

        return is_string($settings) ? (json_decode($settings, true) ?: []) : [];
    }
}
