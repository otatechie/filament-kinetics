<?php

use BladeUI\Icons\Exceptions\SvgNotFound;
use BladeUI\Icons\Factory;
use Filament\Enums\GlobalSearchPosition;
use Filament\Enums\UserMenuPosition;
use Filament\Facades\Filament;
use Filament\Notifications\Livewire\Notifications;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentIcon;
use Otatechie\Kinetics\KineticsPlugin;

it('sets up the panel layout Kinetics is designed for', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->hasTopbar())->toBeFalse()
        ->and($panel->isSidebarCollapsibleOnDesktop())->toBeTrue()
        ->and($panel->hasBreadcrumbs())->toBeFalse()
        ->and($panel->getMaxContentWidth())->toBe(Width::Full)
        ->and($panel->getGlobalSearchPosition())->toBe(GlobalSearchPosition::Sidebar)
        ->and($panel->getUserMenuPosition())->toBe(UserMenuPosition::Sidebar)
        ->and($panel->getFontFamily())->toBe('Open Runde');
});

it('lets panel methods called after the plugin override it', function () {
    $panel = Filament::getPanel('overridden');

    expect($panel->hasTopbar())->toBeTrue()
        ->and($panel->hasBreadcrumbs())->toBeTrue()
        ->and($panel->getFontFamily())->toBe('Inter');
});

it('loads Open Runde and the load check on the sign-in page', function () {
    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('fonts/otatechie/filament-kinetics/open-runde/index.css', escape: false)
        ->assertSee("getPropertyValue('--kinetics-layer-order')", escape: false);
});

it('stops a panel without a custom theme', function () {
    Filament::getPanel('themeless')->boot();
})->throws(LogicException::class, 'Kinetics needs a custom theme');

it('swaps Filament icons for Lucide when the panel boots', function () {
    Filament::getPanel('admin')->boot();

    expect(FilamentIcon::resolve('tables::search-field'))->toBe('lucide-search');
});

it('shows notifications in the bottom corner', function () {
    Notifications::alignment(Alignment::Right);
    Notifications::verticalAlignment(VerticalAlignment::Start);

    Filament::getPanel('admin')->boot();

    expect(Notifications::$alignment)->toBe(Alignment::End)
        ->and(Notifications::$verticalAlignment)->toBe(VerticalAlignment::End);
});

it('lets the panel move notifications back', function () {
    Filament::getPanel('overridden')->boot();

    expect(Notifications::$verticalAlignment)->toBe(VerticalAlignment::Start);
});

it('lets icons set after the plugin override it', function () {
    Filament::getPanel('overridden')->boot();

    expect(FilamentIcon::resolve('tables::actions.filter'))->toBe('lucide-list-filter')
        ->and(FilamentIcon::resolve('tables::search-field'))->toBe('lucide-search');
});

it('only maps to Lucide icons that exist', function () {
    $factory = app(Factory::class);

    $missing = array_filter(KineticsPlugin::ICONS, function (string $icon) use ($factory): bool {
        try {
            $factory->svg($icon);

            return false;
        } catch (SvgNotFound) {
            return true;
        }
    });

    expect($missing)->toBeEmpty();
});

it('ships the layer order for your theme to import first', function () {
    expect(file_get_contents(__DIR__.'/../resources/css/layers.css'))
        ->toContain('@layer theme, base, components, kinetics, utilities;');
});
