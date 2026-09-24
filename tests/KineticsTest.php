<?php

use BladeUI\Icons\Exceptions\SvgNotFound;
use BladeUI\Icons\Factory;
use Filament\Actions\DeleteAction;
use Filament\Enums\GlobalSearchPosition;
use Filament\Enums\UserMenuPosition;
use Filament\Facades\Filament;
use Filament\Notifications\Livewire\Notifications;
use Filament\Notifications\Notification;
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
        ->and($panel->getFontFamily())->toBe('Open Runde')
        ->and($panel->hasCollapsibleNavigationGroups())->toBeFalse();
});

it('lets panel methods called after the plugin override it', function () {
    $panel = Filament::getPanel('overridden');

    expect($panel->hasTopbar())->toBeTrue()
        ->and($panel->hasBreadcrumbs())->toBeTrue()
        ->and($panel->hasCollapsibleNavigationGroups())->toBeTrue()
        ->and($panel->getFontFamily())->toBe('Inter');
});

it('loads Open Runde and the load check on the sign-in page', function () {
    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('fonts/otatechie/filament-kinetics/open-runde/index.css', escape: false)
        ->assertSee("getPropertyValue('--kinetics-layer-order')", escape: false);
});

it('warns when the theme CSS is on a panel without the plugin', function () {
    $this->get('/pluginless/login')
        ->assertOk()
        ->assertSee("KineticsPlugin isn't on this panel", escape: false);
});

it('does not warn about the plugin on a panel that has it', function () {
    $this->get('/admin/login')
        ->assertOk()
        ->assertDontSee("KineticsPlugin isn't on this panel", escape: false);
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

it('aligns confirmation modals like other modals', function () {
    Filament::getPanel('admin')->boot();

    $action = DeleteAction::make();

    expect($action->getModalAlignment())->toBe(Alignment::Start)
        ->and($action->getModalFooterActionsAlignment())->toBe(Alignment::Start);
});

it('lets an action keep its own modal alignment', function () {
    Filament::getPanel('admin')->boot();

    expect(DeleteAction::make()->modalAlignment(Alignment::Center)->getModalAlignment())->toBe(Alignment::Center);
});

it('has wording for tables emptied by a search or filters', function () {
    expect(__('kinetics::tables.empty.search.heading', ['search' => 'ok']))->toBe('No results for “ok”')
        ->and(__('kinetics::tables.empty.filters.heading', ['model' => 'orders']))->toBe('No orders match these filters')
        ->and(__('kinetics::tables.empty.actions.clear_search_and_filters'))->toBe('Clear search and filters');
});

it('keeps errors and warnings open until they are closed', function () {
    Filament::getPanel('admin')->boot();

    expect(Notification::make()->danger()->getDuration())->toBe('persistent')
        ->and(Notification::make()->warning()->getDuration())->toBe('persistent')
        ->and(Notification::make()->success()->getDuration())->toBe(6000)
        ->and(Notification::make()->danger()->duration(3000)->getDuration())->toBe(3000);
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

it('only reports ok when Kinetics sits below Tailwind utilities', function () {
    $css = file_get_contents(__DIR__.'/../resources/css/kinetics.css');

    // "wrong" in the Kinetics layer, "ok" in utilities: utilities only wins
    // when it comes after Kinetics.
    expect($css)->toMatch('/@layer kinetics \{\s*body \{\s*--kinetics-layer-order: wrong;/')
        ->toMatch('/@layer utilities \{\s*body \{\s*--kinetics-layer-order: ok;/');
});

it('warns about a missing theme and a wrong layer order separately', function () {
    $this->get('/admin/login')
        ->assertSee('the theme CSS is missing', escape: false)
        ->assertSee('layers.css must be imported before', escape: false);
});

it('ships the layer order for your theme to import first', function () {
    expect(file_get_contents(__DIR__.'/../resources/css/layers.css'))
        ->toContain('@layer theme, base, components, kinetics, utilities;');
});
