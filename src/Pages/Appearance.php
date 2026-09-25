<?php

namespace Otatechie\Kinetics\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Otatechie\Kinetics\Appearance\AppearanceSettings;
use Otatechie\Kinetics\KineticsPlugin;
use UnitEnum;

/**
 * How the panel looks, changed from the panel itself. Saved
 * changes apply to everyone. Turned on with KineticsPlugin::appearancePage().
 */
class Appearance extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'lucide-palette';

    protected static ?string $slug = 'appearance';

    protected string $view = 'filament-panels::pages.page';

    /** @var array<string, mixed> */
    public ?array $data = [];

    /**
     * Fields whose current value was set in code, so they can keep it.
     *
     * @var array<string>
     */
    public array $setInCode = [];

    /**
     * What the form showed when it opened, so saving stores only what was
     * changed, and the panel's code still decides the rest.
     *
     * @var array<string, mixed>
     */
    public array $shown = [];

    public static function canAccess(): bool
    {
        return KineticsPlugin::get()->canManageAppearance();
    }

    public static function getNavigationLabel(): string
    {
        return __('kinetics::appearance.title');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return KineticsPlugin::get()->getAppearanceNavigationGroup();
    }

    public static function getNavigationSort(): ?int
    {
        return KineticsPlugin::get()->getAppearanceNavigationSort();
    }

    public function getTitle(): string
    {
        return __('kinetics::appearance.title');
    }

    public function getSubheading(): ?string
    {
        return __('kinetics::appearance.subheading');
    }

    /** @return array<string> */
    public function getPageClasses(): array
    {
        return ['kinetics-appearance'];
    }

    public function mount(): void
    {
        $values = $this->settings()->current(Filament::getCurrentPanel());

        $this->setInCode = array_keys($values, AppearanceSettings::CUSTOM, true);
        $this->shown = $values;

        $this->form->fill($values);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->inlineLabel(false)
            ->disabled(! AppearanceSettings::isInstalled())
            ->components([
                Callout::make(__('kinetics::appearance.not_installed.title'))
                    ->description(__('kinetics::appearance.not_installed.description'))
                    ->warning()
                    ->visible(! AppearanceSettings::isInstalled()),
                Section::make(__('kinetics::appearance.colours.heading'))
                    ->description(__('kinetics::appearance.colours.description'))
                    ->aside()
                    ->schema([
                        $this->choice(Select::make('primary_color'), $this->colourOptions())
                            ->label(__('kinetics::appearance.colours.primary'))
                            ->helperText(__('kinetics::appearance.colours.primary_help'))
                            ->allowHtml()
                            ->native(false)
                            ->selectablePlaceholder(false),
                        $this->choice(ToggleButtons::make('gray_color'), __('kinetics::appearance.colours.tones'))
                            ->label(__('kinetics::appearance.colours.gray'))
                            ->helperText(__('kinetics::appearance.colours.gray_help')),
                    ]),
                Section::make(__('kinetics::appearance.shape.heading'))
                    ->description(__('kinetics::appearance.shape.description'))
                    ->aside()
                    ->schema([
                        $this->choice(ToggleButtons::make('radius'), __('kinetics::appearance.shape.radius_options'))
                            ->label(__('kinetics::appearance.shape.radius')),
                        $this->choice(ToggleButtons::make('buttons'), __('kinetics::appearance.shape.buttons_options'))
                            ->label(__('kinetics::appearance.shape.buttons')),
                        $this->choice(ToggleButtons::make('surface'), __('kinetics::appearance.shape.surface_options'))
                            ->label(__('kinetics::appearance.shape.surface'))
                            ->helperText(__('kinetics::appearance.shape.surface_help')),
                        $this->choice(ToggleButtons::make('field_borders'), __('kinetics::appearance.shape.field_borders_options'))
                            ->label(__('kinetics::appearance.shape.field_borders'))
                            ->helperText(__('kinetics::appearance.shape.field_borders_help')),
                    ]),
                Section::make(__('kinetics::appearance.type.heading'))
                    ->description(__('kinetics::appearance.type.description'))
                    ->aside()
                    ->schema([
                        $this->choice(ToggleButtons::make('font'), array_combine(array_keys(AppearanceSettings::FONTS), array_keys(AppearanceSettings::FONTS)))
                            ->label(__('kinetics::appearance.type.font'))
                            ->helperText(__('kinetics::appearance.type.font_help')),
                    ]),
                Section::make(__('kinetics::appearance.dark_mode.heading'))
                    ->description(__('kinetics::appearance.dark_mode.description'))
                    ->aside()
                    ->schema([
                        Toggle::make('dark_mode')
                            ->label(__('kinetics::appearance.dark_mode.allow'))
                            ->helperText(__('kinetics::appearance.dark_mode.allow_help'))
                            ->live(),
                        $this->choice(ToggleButtons::make('theme_mode'), __('kinetics::appearance.dark_mode.start_in_options'))
                            ->label(__('kinetics::appearance.dark_mode.start_in'))
                            ->helperText(__('kinetics::appearance.dark_mode.start_in_help'))
                            ->visible(fn (Get $get): bool => (bool) $get('dark_mode')),
                        $this->choice(ToggleButtons::make('dark_style'), __('kinetics::appearance.dark_mode.style_options'))
                            ->label(__('kinetics::appearance.dark_mode.style'))
                            ->visible(fn (Get $get): bool => (bool) $get('dark_mode')),
                    ]),
                Section::make(__('kinetics::appearance.layout.heading'))
                    ->description(__('kinetics::appearance.layout.description'))
                    ->aside()
                    ->schema([
                        $this->choice(ToggleButtons::make('content_width'), __('kinetics::appearance.layout.width_options'))
                            ->label(__('kinetics::appearance.layout.width'))
                            ->helperText(__('kinetics::appearance.layout.width_help')),
                        Toggle::make('topbar')
                            ->label(__('kinetics::appearance.layout.topbar'))
                            ->helperText(__('kinetics::appearance.layout.topbar_help')),
                        Toggle::make('sidebar_collapsible')
                            ->label(__('kinetics::appearance.layout.sidebar'))
                            ->helperText(__('kinetics::appearance.layout.sidebar_help')),
                        Toggle::make('collapsible_groups')
                            ->label(__('kinetics::appearance.layout.groups'))
                            ->helperText(__('kinetics::appearance.layout.groups_help')),
                    ]),
                Section::make(__('kinetics::appearance.notifications.heading'))
                    ->description(__('kinetics::appearance.notifications.description'))
                    ->aside()
                    ->schema([
                        $this->choice(ToggleButtons::make('notifications'), __('kinetics::appearance.notifications.position_options'))
                            ->label(__('kinetics::appearance.notifications.position'))
                            ->helperText(__('kinetics::appearance.notifications.position_help')),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->extraAttributes(['class' => 'kinetics-appearance-form'])
                // Under the fields column, so the buttons sit with what they save.
                ->footer([
                    Grid::make(['default' => 1, 'md' => 2])
                        ->visible(AppearanceSettings::isInstalled())
                        ->schema([
                            Actions::make([
                                Action::make('save')
                                    ->label(__('kinetics::appearance.actions.save'))
                                    ->submit('save'),
                                Action::make('reset')
                                    ->label(__('kinetics::appearance.actions.reset'))
                                    ->color('gray')
                                    ->requiresConfirmation()
                                    ->modalHeading(__('kinetics::appearance.actions.reset_heading'))
                                    ->modalDescription(__('kinetics::appearance.actions.reset_description'))
                                    ->modalSubmitActionLabel(__('kinetics::appearance.actions.reset_confirm'))
                                    ->action(function (): void {
                                        $this->settings()->reset();
                                        $this->saved(__('kinetics::appearance.notifications_sent.reset'));
                                    }),
                            ])
                                ->columnStart(['md' => 2])
                                ->key('form-actions'),
                        ]),
                ]),
        ]);
    }

    public function save(): void
    {
        $changed = array_filter(
            $this->form->getState(),
            fn (mixed $value, string $key): bool => ! array_key_exists($key, $this->shown) || $this->shown[$key] !== $value,
            ARRAY_FILTER_USE_BOTH,
        );

        $this->settings()->save([...$this->settings()->saved(), ...$changed]);

        $this->saved(__('kinetics::appearance.notifications_sent.saved'));
    }

    /** Reloads the page, since colours, fonts and layout apply as it loads. */
    protected function saved(string $title): void
    {
        Notification::make()
            ->title($title)
            ->body(__('kinetics::appearance.notifications_sent.body'))
            ->success()
            ->send();

        $this->redirect(static::getUrl());
    }

    protected function settings(): AppearanceSettings
    {
        return KineticsPlugin::get()->appearance();
    }

    /**
     * A required choice with its options, plus "As set in code" when the
     * panel's code chose something the page doesn't offer.
     *
     * @template T of Select|ToggleButtons
     *
     * @param  T  $field
     * @param  array<string, string>  $options
     * @return T
     */
    protected function choice(Select|ToggleButtons $field, array $options): Select|ToggleButtons
    {
        if (in_array($field->getName(), $this->setInCode, true)) {
            $options[AppearanceSettings::CUSTOM] = e(__('kinetics::appearance.set_in_code'));
        }

        if ($field instanceof ToggleButtons) {
            $field->inline();
        }

        return $field
            ->options($options)
            ->required()
            ->markAsRequired(false);
    }

    /** @return array<string, string> */
    protected function colourOptions(): array
    {
        return collect(AppearanceSettings::PRIMARY_COLORS)
            ->mapWithKeys(fn (string $name): array => [$name => '<span class="kinetics-swatch"><span class="kinetics-swatch-dot" style="background:'.e(AppearanceSettings::palette($name, AppearanceSettings::PRIMARY_COLORS)[600]).'"></span>'.e($name).'</span>'])
            ->all();
    }
}
