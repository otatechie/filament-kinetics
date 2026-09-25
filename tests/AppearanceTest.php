<?php

use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Filament\FontProviders\BunnyFontProvider;
use Filament\Notifications\Livewire\Notifications;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Orchestra\Testbench\Factories\UserFactory;
use Otatechie\Kinetics\Appearance\AppearanceSettings;
use Otatechie\Kinetics\KineticsPlugin;
use Otatechie\Kinetics\Pages\Appearance;

beforeEach(function () {
    $this->loadLaravelMigrations();
    $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

    Filament::setCurrentPanel('styled');
});

function signInAs(string $email): void
{
    test()->actingAs(UserFactory::new()->create(['email' => $email]));
}

it('has no Appearance page unless the panel turns it on', function () {
    expect(Filament::getPanel('admin')->getPages())->not->toContain(Appearance::class)
        ->and(Filament::getPanel('styled')->getPages())->toContain(Appearance::class);
});

it('opens for the people the panel allows', function () {
    signInAs('owner@example.com');

    Livewire::test(Appearance::class)->assertOk();
});

it('stays closed to everyone else', function () {
    signInAs('someone@example.com');

    Livewire::test(Appearance::class)->assertForbidden();
});

it('shows how the panel is set up until something is saved', function () {
    signInAs('owner@example.com');

    Filament::getPanel('styled')->boot();

    Livewire::test(Appearance::class)
        ->assertSchemaStateSet([
            'font' => 'Open Runde',
            'topbar' => false,
            'content_width' => 'full',
            'notifications' => 'bottom-right',
            'radius' => 'default',
        ]);
});

it('applies saved choices when the panel boots', function () {
    KineticsPlugin::get()->appearance()->save([
        'primary_color' => 'Teal',
        'gray_color' => 'Stone',
        'font' => 'DM Sans',
        'topbar' => true,
        'content_width' => 'narrow',
        'dark_mode' => false,
        'notifications' => 'top-right',
    ]);

    $panel = Filament::getPanel('styled');
    $panel->boot();

    expect(FilamentColor::getColor('primary')[500])->toBe(Color::Teal[500])
        ->and(FilamentColor::getColor('gray')[500])->toBe(Color::Stone[500])
        ->and($panel->getFontFamily())->toBe('DM Sans')
        ->and($panel->getFontProvider())->toBe(BunnyFontProvider::class)
        ->and($panel->hasTopbar())->toBeTrue()
        ->and($panel->getMaxContentWidth())->toBe(Width::FourExtraLarge)
        ->and($panel->hasDarkMode())->toBeFalse()
        ->and(Notifications::$verticalAlignment)->toBe(VerticalAlignment::Start);
});

it('saves only what was changed, and resets to how the panel is set up', function () {
    signInAs('owner@example.com');

    Livewire::test(Appearance::class)
        ->fillForm(['radius' => 'round', 'topbar' => true])
        ->call('save')
        ->assertHasNoFormErrors();

    // Only what was changed, so the panel's code still decides the rest.
    expect(KineticsPlugin::get()->appearance()->saved())->toBe(['radius' => 'round', 'topbar' => '1']);

    Livewire::test(Appearance::class)
        ->callAction(TestAction::make('reset')->schemaComponent('form-actions', schema: 'content'));

    expect(KineticsPlugin::get()->appearance()->saved())->toBe([])
        ->and((string) KineticsPlugin::get()->appearance()->css())->toBe('');
});

it('writes CSS only for the shape choices made', function () {
    $settings = KineticsPlugin::get()->appearance();

    // Corners set in the theme's CSS stay until corners are chosen here.
    $settings->save(['buttons' => 'rounded', 'field_borders' => 'strong', 'dark_style' => 'palette']);

    expect((string) $settings->css())
        ->toContain('.fi-btn { border-radius: var(--kinetics-radius); }')
        ->toContain('.dark { --kinetics-input-border: var(--gray-600); }')
        ->not->toContain('--kinetics-radius:');

    $settings->save(['radius' => 'round']);

    expect((string) $settings->css())->toContain('--kinetics-radius: 0.75rem');
});

it('keeps choices made in code that the page does not offer', function () {
    FilamentColor::register(['primary' => '#123456']);

    $settings = KineticsPlugin::get()->appearance();
    $settings->save($settings->current(Filament::getPanel('styled')));

    expect($settings->saved())->not->toHaveKey('primary_color');
});

it('keeps the saved choices to their own panel', function () {
    KineticsPlugin::get()->appearance()->save(['topbar' => true]);

    $admin = Filament::getPanel('admin');
    $admin->boot();

    expect($admin->hasTopbar())->toBeFalse();
});

it('boots before the migration has run', function () {
    Schema::drop(AppearanceSettings::TABLE);

    $panel = Filament::getPanel('styled');
    $panel->boot();

    expect($panel->hasTopbar())->toBeFalse()
        ->and(AppearanceSettings::isInstalled())->toBeFalse();
});
