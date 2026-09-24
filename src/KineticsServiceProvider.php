<?php

namespace Otatechie\Kinetics;

use Filament\Support\Assets\Font;
use Filament\Support\Assets\Theme;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;

class KineticsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Published to public/ by `php artisan filament:assets`.
        FilamentAsset::register([
            Theme::make('kinetics', __DIR__.'/../resources/dist/kinetics.css'),
            Font::make('open-runde', __DIR__.'/../resources/fonts/open-runde'),
        ], 'otatechie/filament-kinetics');
    }
}
