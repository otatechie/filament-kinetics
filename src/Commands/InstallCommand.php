<?php

namespace Otatechie\Kinetics\Commands;

use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use function Laravel\Prompts\select;

/**
 * Steps 3 and 4 of the installation guide, the ones easy to get wrong by
 * hand: the imports around Filament's in the panel's theme.css, in the right
 * order, and the plugin in its provider. Safe to run again.
 */
class InstallCommand extends Command
{
    protected $signature = 'kinetics:install {panel? : The ID of the panel to use Kinetics on}';

    protected $description = "Add Kinetics to a panel's theme and provider";

    protected const MANUAL = 'https://github.com/otatechie/filament-kinetics/blob/main/docs/installation.md#3-add-the-kinetics-imports';

    public function handle(): int
    {
        $panel = $this->panel();

        if (! $panel) {
            return self::FAILURE;
        }

        $theme = Arr::first(Arr::wrap($panel->getViteTheme()), fn (string $path): bool => str_ends_with($path, '.css'));

        if ($theme === null) {
            $this->components->error("The [{$panel->getId()}] panel doesn't have a Vite theme yet, and Kinetics lives in one. Create it with `php artisan make:filament-theme {$panel->getId()}`, then run this again. If you build your theme another way, add the imports by hand: ".self::MANUAL);

            return self::FAILURE;
        }

        if (! $this->addImports($theme)) {
            return self::FAILURE;
        }

        $this->addPlugin($panel);

        if (! is_dir(public_path('fonts/otatechie/filament-kinetics'))) {
            $this->components->warn("Kinetics' font isn't in public/ yet. Run `php artisan filament:assets` to copy it there.");
        }

        $this->components->info('Done. Run `npm run build` to see Kinetics in your panel.');

        return self::SUCCESS;
    }

    protected function panel(): ?Panel
    {
        $panels = Filament::getPanels();

        if (filled($id = $this->argument('panel'))) {
            if (! isset($panels[$id])) {
                $this->components->error("There's no panel called [{$id}]. Your panels are: ".implode(', ', array_keys($panels)).'.');

                return null;
            }

            return $panels[$id];
        }

        if ($panels === []) {
            $this->components->error("There's no Filament panel to add Kinetics to yet. Create one with `php artisan filament:install --panels`, then run this again.");

            return null;
        }

        if (count($panels) === 1) {
            return Arr::first($panels);
        }

        $ids = array_keys($panels);

        // Without someone to ask, picking one would be a guess.
        if (! $this->input->isInteractive()) {
            $this->components->error("You have more than one panel, so say which one should use Kinetics, as in `php artisan kinetics:install {$ids[0]}`. Your panels are: ".implode(', ', $ids).'.');

            return null;
        }

        return $panels[select('Which panel should use Kinetics?', array_combine($ids, $ids))];
    }

    protected function addImports(string $theme): bool
    {
        $path = base_path($theme);

        if (! is_file($path)) {
            $this->components->error("The panel points at [{$theme}] for its theme, but that file doesn't exist. Create it with `php artisan make:filament-theme`, or fix the path in `->viteTheme()`.");

            return false;
        }

        $css = file_get_contents($path);
        $eol = self::lineEnding($css);

        // Imports already there are taken out first, so a wrong order is put right.
        $lines = collect(explode($eol, $css))
            ->reject(fn (string $line): bool => (bool) preg_match('/^\s*@import\s+[\'"][^\'"]*filament-kinetics\/resources\/css\/(layers|kinetics)\.css[\'"]\s*;\s*$/', $line))
            ->values()
            ->all();

        $filamentImport = '/^\s*@import\s+([\'"])(.*?)filament\/filament\/resources\/css\/theme\.css\1\s*;\s*$/';
        $filament = collect($lines)->search(fn (string $line): bool => (bool) preg_match($filamentImport, $line));

        if ($filament === false) {
            $this->components->error("[{$theme}] doesn't import Filament's theme.css, which Kinetics' imports go around, so nothing was changed. Add them by hand: ".self::MANUAL);

            return false;
        }

        // Kinetics sits beside Filament in vendor/, so its imports are
        // Filament's with the package swapped, however deep the theme is.
        preg_match($filamentImport, $lines[$filament], $match);
        [, $quote, $vendor] = $match;
        $import = fn (string $file): string => "@import {$quote}{$vendor}otatechie/filament-kinetics/resources/css/{$file}{$quote};";

        // kinetics.css straight after Filament's, and layers.css before every
        // import, since the first to mention a layer fixes the order.
        array_splice($lines, $filament + 1, 0, [$import('kinetics.css')]);
        array_splice($lines, collect($lines)->search(fn (string $line): bool => str_starts_with(ltrim($line), '@import')), 0, [$import('layers.css')]);

        $updated = implode($eol, $lines);

        if ($updated === $css) {
            $this->components->info("[{$theme}] already imports Kinetics, in the right order.");

            return true;
        }

        file_put_contents($path, $updated);

        $this->components->info("Added the Kinetics imports to [{$theme}].");

        return true;
    }

    protected function addPlugin(Panel $panel): void
    {
        if ($panel->hasPlugin('kinetics')) {
            return;
        }

        $provider = 'app/Providers/Filament/'.Str::studly($panel->getId()).'PanelProvider.php';
        $php = is_file(base_path($provider)) ? file_get_contents(base_path($provider)) : '';
        $eol = self::lineEnding($php);

        // Only in the provider for this panel, on a line of its own after
        // ->path() or ->id(), so the provider's settings below still override
        // the plugin's.
        $chain = null;

        if (preg_match('/->id\(\s*[\'"]'.preg_quote($panel->getId(), '/').'[\'"]\s*\)/', $php)) {
            foreach (['path', 'id'] as $method) {
                if (preg_match('/^([^\S\r\n]*)->'.$method.'\(.*\)(?=[^\S\r\n]*\r?$)/m', $php, $chain, PREG_OFFSET_CAPTURE)) {
                    break;
                }
            }
        }

        if (! $chain) {
            $this->components->warn("Couldn't find the provider for the [{$panel->getId()}] panel, so the plugin still needs adding. Add `->plugin(KineticsPlugin::make())` to it, with `use Otatechie\\Kinetics\\KineticsPlugin;` at the top.");

            return;
        }

        // With an import beside the others, or the full name if there are none.
        $imported = str_contains($php, 'use Otatechie\Kinetics\KineticsPlugin;');
        $use = $imported ? null : Arr::last(preg_match_all('/^use [^;]+;(?=\r?$)/m', $php, $uses, PREG_OFFSET_CAPTURE) ? $uses[0] : []);
        $class = ($imported || $use) ? 'KineticsPlugin' : '\Otatechie\Kinetics\KineticsPlugin';

        // The later insertion first, so the earlier one's offset still holds.
        $php = substr_replace($php, $eol.$chain[1][0]."->plugin({$class}::make())", $chain[0][1] + strlen($chain[0][0]), 0);

        if ($use) {
            $php = substr_replace($php, $eol.'use Otatechie\Kinetics\KineticsPlugin;', $use[1] + strlen($use[0]), 0);
        }

        file_put_contents(base_path($provider), $php);

        $this->components->info("Added the plugin to [{$provider}].");
    }

    protected static function lineEnding(string $text): string
    {
        return str_contains($text, "\r\n") ? "\r\n" : "\n";
    }
}
