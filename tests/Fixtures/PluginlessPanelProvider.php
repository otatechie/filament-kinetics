<?php

namespace Otatechie\Kinetics\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;

class PluginlessPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('pluginless')
            ->path('pluginless')
            ->login();
    }
}
