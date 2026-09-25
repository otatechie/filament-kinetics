<?php

namespace Otatechie\Kinetics\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use Otatechie\Kinetics\KineticsPlugin;

class AppearancePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('styled')
            ->path('styled')
            ->login()
            ->plugin(KineticsPlugin::make()->appearancePage(
                authorize: fn ($user): bool => $user?->email === 'owner@example.com',
                navigationGroup: 'Settings',
            ))
            ->theme('css/admin/theme.css');
    }
}
