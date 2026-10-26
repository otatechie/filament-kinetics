<?php

use Filament\Panel;
use Filament\PanelRegistry;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Straight into the registry: the facade's registerPanel() waits for it to be built.
    app(PanelRegistry::class)->register(Panel::make()->id('installable')->path('installable')->viteTheme('resources/css/filament/installable/theme.css'));

    File::ensureDirectoryExists(resource_path('css/filament/installable'));
});

afterEach(function () {
    File::deleteDirectory(resource_path('css/filament/installable'));
    File::delete(app_path('Providers/Filament/InstallablePanelProvider.php'));
});

function installableTheme(?string $css = null): string
{
    if ($css !== null) {
        file_put_contents(resource_path('css/filament/installable/theme.css'), $css);
    }

    return file_get_contents(resource_path('css/filament/installable/theme.css'));
}

it("adds the imports around Filament's, with its path and quotes", function () {
    installableTheme(<<<'CSS'
        @import "../../../../vendor/filament/filament/resources/css/theme.css";

        @source "../../../../app/Filament/**/*";
        CSS);

    $this->artisan('kinetics:install installable')->assertSuccessful();

    expect(installableTheme())->toBe(<<<'CSS'
        @import "../../../../vendor/otatechie/filament-kinetics/resources/css/layers.css";
        @import "../../../../vendor/filament/filament/resources/css/theme.css";
        @import "../../../../vendor/otatechie/filament-kinetics/resources/css/kinetics.css";

        @source "../../../../app/Filament/**/*";
        CSS);
});

it("puts layers.css before every import, not only Filament's", function () {
    installableTheme(<<<'CSS'
        @import '../../../../vendor/some/plugin/resources/css/plugin.css';
        @import '../../../../vendor/filament/filament/resources/css/theme.css';
        CSS);

    $this->artisan('kinetics:install installable')->assertSuccessful();

    expect(installableTheme())->toBe(<<<'CSS'
        @import '../../../../vendor/otatechie/filament-kinetics/resources/css/layers.css';
        @import '../../../../vendor/some/plugin/resources/css/plugin.css';
        @import '../../../../vendor/filament/filament/resources/css/theme.css';
        @import '../../../../vendor/otatechie/filament-kinetics/resources/css/kinetics.css';
        CSS);
});

it('puts imports in the wrong order right, and changes nothing the second time', function () {
    installableTheme(<<<'CSS'
        @import '../../../../vendor/filament/filament/resources/css/theme.css';
        @import '../../../../vendor/otatechie/filament-kinetics/resources/css/kinetics.css';
        @import '../../../../vendor/otatechie/filament-kinetics/resources/css/layers.css';
        CSS);

    $this->artisan('kinetics:install installable')->assertSuccessful();

    $fixed = installableTheme();

    expect($fixed)->toBe(<<<'CSS'
        @import '../../../../vendor/otatechie/filament-kinetics/resources/css/layers.css';
        @import '../../../../vendor/filament/filament/resources/css/theme.css';
        @import '../../../../vendor/otatechie/filament-kinetics/resources/css/kinetics.css';
        CSS);

    $this->artisan('kinetics:install installable')
        ->expectsOutputToContain('already imports Kinetics')
        ->assertSuccessful();

    expect(installableTheme())->toBe($fixed);
});

it('stops with directions when the panel has no theme', function () {
    $this->artisan('kinetics:install themeless')
        ->expectsOutputToContain('make:filament-theme themeless')
        ->assertFailed();
});

it("stops when the theme doesn't import Filament", function () {
    installableTheme("@import 'tailwindcss';\n");

    $this->artisan('kinetics:install installable')
        ->expectsOutputToContain("doesn't import Filament's theme.css")
        ->assertFailed();

    expect(installableTheme())->toBe("@import 'tailwindcss';\n");
});

function installableProvider(?string $php = null): string
{
    $path = app_path('Providers/Filament/InstallablePanelProvider.php');

    if ($php !== null) {
        File::ensureDirectoryExists(dirname($path));
        file_put_contents($path, $php);
    }

    return file_get_contents($path);
}

const INSTALLABLE_PROVIDER = <<<'PHP'
    <?php

    namespace App\Providers\Filament;

    use Filament\Panel;
    use Filament\PanelProvider;

    class InstallablePanelProvider extends PanelProvider
    {
        public function panel(Panel $panel): Panel
        {
            return $panel
                ->id('installable')
                ->path('installable')
                ->login();
        }
    }
    PHP;

it("adds the plugin to the panel's provider, below ->path()", function () {
    installableTheme("@import '../../../../vendor/filament/filament/resources/css/theme.css';\n");
    installableProvider(INSTALLABLE_PROVIDER);

    $this->artisan('kinetics:install installable')->assertSuccessful();

    expect(installableProvider())
        ->toContain("use Filament\\PanelProvider;\nuse Otatechie\\Kinetics\\KineticsPlugin;\n")
        ->toContain("->path('installable')\n            ->plugin(KineticsPlugin::make())\n            ->login();");
});

it('keeps Windows line endings in the theme and the provider', function () {
    installableTheme("@import '../../../../vendor/filament/filament/resources/css/theme.css';\r\n\r\n@source '../../../../app/Filament/**/*';\r\n");
    installableProvider(str_replace("\n", "\r\n", INSTALLABLE_PROVIDER));

    $this->artisan('kinetics:install installable')->assertSuccessful();

    expect(installableTheme())->toBe(
        "@import '../../../../vendor/otatechie/filament-kinetics/resources/css/layers.css';\r\n"
        ."@import '../../../../vendor/filament/filament/resources/css/theme.css';\r\n"
        ."@import '../../../../vendor/otatechie/filament-kinetics/resources/css/kinetics.css';\r\n"
        ."\r\n@source '../../../../app/Filament/**/*';\r\n"
    )->and(installableProvider())
        ->toContain("use Filament\\PanelProvider;\r\nuse Otatechie\\Kinetics\\KineticsPlugin;\r\n")
        ->toContain("->path('installable')\r\n            ->plugin(KineticsPlugin::make())\r\n            ->login();")
        ->not->toMatch("/[^\r]\n/");
});

it('uses the full class name in a provider without imports', function () {
    installableTheme("@import '../../../../vendor/filament/filament/resources/css/theme.css';\n");
    installableProvider(str_replace(['use Filament\Panel;'."\n", 'use Filament\PanelProvider;'."\n", 'extends PanelProvider', 'Panel $panel): Panel'], ['', '', 'extends \Filament\PanelProvider', '\Filament\Panel $panel): \Filament\Panel'], INSTALLABLE_PROVIDER));

    $this->artisan('kinetics:install installable')->assertSuccessful();

    expect(installableProvider())
        ->toContain('->plugin(\Otatechie\Kinetics\KineticsPlugin::make())')
        ->not->toContain('use Otatechie');
});

it('asks for the panel instead of guessing when it cannot ask', function () {
    $this->artisan('kinetics:install', ['--no-interaction' => true])
        ->expectsOutputToContain('say which one should use Kinetics')
        ->assertFailed();
});

it("says how to add the plugin when it can't find the provider", function () {
    installableTheme("@import '../../../../vendor/filament/filament/resources/css/theme.css';\n");

    $this->artisan('kinetics:install installable')
        ->expectsOutputToContain('->plugin(KineticsPlugin::make())')
        ->assertSuccessful();
});
