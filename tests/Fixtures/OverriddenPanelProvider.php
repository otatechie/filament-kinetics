<?php

namespace Otatechie\Kinetics\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use Otatechie\Kinetics\KineticsPlugin;

class OverriddenPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('overridden')
            ->path('overridden')
            ->plugin(KineticsPlugin::make())
            ->topbar()
            ->breadcrumbs()
            ->font('Inter')
            ->icons(['tables::actions.filter' => 'lucide-list-filter']);
    }
}
