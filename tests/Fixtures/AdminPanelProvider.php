<?php

namespace Otatechie\Kinetics\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use Otatechie\Kinetics\KineticsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->plugin(KineticsPlugin::make());
    }
}
