<?php

namespace Otatechie\Kinetics\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use Otatechie\Kinetics\KineticsPlugin;

class ThemelessPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('themeless')
            ->path('themeless')
            ->plugin(KineticsPlugin::make());
    }
}
