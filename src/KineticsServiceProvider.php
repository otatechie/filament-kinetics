<?php

namespace Otatechie\Kinetics;

use Filament\Support\Assets\Font;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

class KineticsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Published to public/ by `php artisan filament:assets`.
        FilamentAsset::register([
            Font::make('open-runde', __DIR__.'/../resources/fonts/open-runde'),
        ], 'otatechie/filament-kinetics');

        // On a panel whose theme imports Kinetics but that has no plugin, the
        // colours arrive without the layout, font and icons. Say so, since
        // the plugin's own checks aren't there to. Panels without Kinetics'
        // CSS leave the variable empty and stay quiet.
        FilamentView::registerRenderHook(
            PanelsRenderHook::SCRIPTS_AFTER,
            fn (): HtmlString => new HtmlString(filament()->getCurrentPanel()?->hasPlugin('kinetics') ? '' : <<<'HTML'
                <script>
                    (() => {
                        if (getComputedStyle(document.body).getPropertyValue('--kinetics-layer-order').trim() !== '') {
                            console.warn("Kinetics: the theme CSS is loaded, but KineticsPlugin isn't on this panel, so it has Filament's layout, font and icons. Add ->plugin(KineticsPlugin::make()) to the panel provider: https://github.com/otatechie/filament-kinetics/blob/main/docs/troubleshooting.md")
                        }
                    })()
                </script>
                HTML),
        );
    }
}
