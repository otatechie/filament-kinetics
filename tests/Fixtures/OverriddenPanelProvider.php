<?php

namespace Otatechie\Kinetics\Tests\Fixtures;

use Filament\Notifications\Livewire\Notifications;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\VerticalAlignment;
use Otatechie\Kinetics\KineticsPlugin;

class OverriddenPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('overridden')
            ->path('overridden')
            ->plugin(KineticsPlugin::make())
            ->theme('css/overridden/theme.css')
            ->topbar()
            ->breadcrumbs()
            ->collapsibleNavigationGroups()
            ->font('Inter')
            ->icons(['tables::actions.filter' => 'lucide-list-filter'])
            ->bootUsing(fn () => Notifications::verticalAlignment(VerticalAlignment::Start));
    }
}
