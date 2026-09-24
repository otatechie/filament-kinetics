<?php

namespace Otatechie\Kinetics;

use Filament\Actions\Action;
use Filament\Contracts\Plugin;
use Filament\Enums\GlobalSearchPosition;
use Filament\Enums\UserMenuPosition;
use Filament\FontProviders\LocalFontProvider;
use Filament\Notifications\Livewire\Notifications;
use Filament\Panel;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use LogicException;

class KineticsPlugin implements Plugin
{
    /**
     * Filament's own icons in Lucide. Anything not listed keeps its Heroicon.
     * The panel's own ->icons(), called after the plugin, overrides these.
     *
     * @var array<string, string>
     */
    public const ICONS = [
        'actions::action-group' => 'lucide-ellipsis-vertical',
        'actions::create-action.grouped' => 'lucide-plus',
        'actions::delete-action' => 'lucide-trash-2',
        'actions::delete-action.grouped' => 'lucide-trash-2',
        'actions::delete-action.modal' => 'lucide-trash-2',
        'actions::detach-action' => 'lucide-x',
        'actions::detach-action.modal' => 'lucide-x',
        'actions::dissociate-action' => 'lucide-x',
        'actions::dissociate-action.modal' => 'lucide-x',
        'actions::edit-action' => 'lucide-square-pen',
        'actions::edit-action.grouped' => 'lucide-square-pen',
        'actions::export-action.grouped' => 'lucide-download',
        'actions::force-delete-action' => 'lucide-trash-2',
        'actions::force-delete-action.grouped' => 'lucide-trash-2',
        'actions::force-delete-action.modal' => 'lucide-trash-2',
        'actions::import-action.grouped' => 'lucide-upload',
        'actions::modal.confirmation' => 'lucide-triangle-alert',
        'actions::replicate-action' => 'lucide-copy',
        'actions::replicate-action.grouped' => 'lucide-copy',
        'actions::restore-action' => 'lucide-undo-2',
        'actions::restore-action.grouped' => 'lucide-undo-2',
        'actions::restore-action.modal' => 'lucide-undo-2',
        'actions::view-action' => 'lucide-eye',
        'actions::view-action.grouped' => 'lucide-eye',
        'badge.delete-button' => 'lucide-x',
        'breadcrumbs.separator' => 'lucide-chevron-right',
        'breadcrumbs.separator.rtl' => 'lucide-chevron-left',
        'forms::components.builder.actions.clone' => 'lucide-copy',
        'forms::components.builder.actions.collapse' => 'lucide-chevron-up',
        'forms::components.builder.actions.delete' => 'lucide-trash-2',
        'forms::components.builder.actions.edit' => 'lucide-square-pen',
        'forms::components.builder.actions.expand' => 'lucide-chevron-down',
        'forms::components.builder.actions.move-down' => 'lucide-arrow-down',
        'forms::components.builder.actions.move-up' => 'lucide-arrow-up',
        'forms::components.builder.actions.reorder' => 'lucide-grip-vertical',
        'forms::components.checkbox-list.search-field' => 'lucide-search',
        'forms::components.key-value.actions.delete' => 'lucide-trash-2',
        'forms::components.key-value.actions.reorder' => 'lucide-grip-vertical',
        'forms::components.repeater.actions.clone' => 'lucide-copy',
        'forms::components.repeater.actions.collapse' => 'lucide-chevron-up',
        'forms::components.repeater.actions.delete' => 'lucide-trash-2',
        'forms::components.repeater.actions.expand' => 'lucide-chevron-down',
        'forms::components.repeater.actions.move-down' => 'lucide-arrow-down',
        'forms::components.repeater.actions.move-up' => 'lucide-arrow-up',
        'forms::components.repeater.actions.reorder' => 'lucide-grip-vertical',
        'forms::components.select.actions.create-option' => 'lucide-plus',
        'forms::components.select.actions.edit-option' => 'lucide-square-pen',
        'forms::components.text-input.actions.copy' => 'lucide-copy',
        'forms::components.text-input.actions.hide-password' => 'lucide-eye-off',
        'forms::components.text-input.actions.show-password' => 'lucide-eye',
        'forms::components.toggle-buttons.boolean.false' => 'lucide-x',
        'forms::components.toggle-buttons.boolean.true' => 'lucide-check',
        'infolists::components.icon-entry.false' => 'lucide-circle-x',
        'infolists::components.icon-entry.true' => 'lucide-circle-check',
        'modal.close-button' => 'lucide-x',
        'notifications::database.modal.empty-state' => 'lucide-bell-off',
        'notifications::notification.close-button' => 'lucide-x',
        'notifications::notification.danger' => 'lucide-circle-x',
        'notifications::notification.info' => 'lucide-info',
        'notifications::notification.success' => 'lucide-circle-check',
        'notifications::notification.warning' => 'lucide-triangle-alert',
        'pagination.first-button' => 'lucide-chevrons-left',
        'pagination.first-button.rtl' => 'lucide-chevrons-right',
        'pagination.last-button' => 'lucide-chevrons-right',
        'pagination.last-button.rtl' => 'lucide-chevrons-left',
        'pagination.next-button' => 'lucide-chevron-right',
        'pagination.next-button.rtl' => 'lucide-chevron-left',
        'pagination.previous-button' => 'lucide-chevron-left',
        'pagination.previous-button.rtl' => 'lucide-chevron-right',
        'panels::global-search.field' => 'lucide-search',
        'panels::pages.dashboard.actions.filter' => 'lucide-funnel',
        'panels::pages.dashboard.navigation-item' => 'lucide-house',
        'panels::resources.pages.edit-record.navigation-item' => 'lucide-square-pen',
        'panels::resources.pages.manage-related-records.navigation-item' => 'lucide-layers',
        'panels::resources.pages.view-record.navigation-item' => 'lucide-eye',
        'panels::sidebar.collapse-button' => 'lucide-panel-left-close',
        'panels::sidebar.collapse-button.rtl' => 'lucide-panel-right-close',
        'panels::sidebar.expand-button' => 'lucide-panel-left-open',
        'panels::sidebar.expand-button.rtl' => 'lucide-panel-right-open',
        'panels::sidebar.group.collapse-button' => 'lucide-chevron-up',
        'panels::sidebar.open-database-notifications-button' => 'lucide-bell',
        'panels::theme-switcher.dark-button' => 'lucide-moon',
        'panels::theme-switcher.light-button' => 'lucide-sun',
        'panels::theme-switcher.system-button' => 'lucide-monitor',
        'panels::topbar.close-sidebar-button' => 'lucide-x',
        'panels::topbar.group.toggle-button' => 'lucide-chevron-down',
        'panels::topbar.open-database-notifications-button' => 'lucide-bell',
        'panels::topbar.open-sidebar-button' => 'lucide-menu',
        'panels::user-menu.logout-button' => 'lucide-log-out',
        'panels::user-menu.profile-item' => 'lucide-circle-user',
        'panels::user-menu.toggle-button' => 'lucide-chevrons-up-down',
        'panels::widgets.account.logout-button' => 'lucide-log-out',
        'panels::widgets.filament-info.open-documentation-button' => 'lucide-book-open',
        'schema::components.callout.danger' => 'lucide-circle-x',
        'schema::components.callout.info' => 'lucide-info',
        'schema::components.callout.success' => 'lucide-circle-check',
        'schema::components.callout.warning' => 'lucide-triangle-alert',
        'schema::components.tabs.dropdown-trigger-button' => 'lucide-chevron-down',
        'schema::components.tabs.more-tabs-button' => 'lucide-ellipsis',
        'schema::components.wizard.completed-step' => 'lucide-check',
        'section.collapse-button' => 'lucide-chevron-up',
        'tables::actions.column-manager' => 'lucide-columns-3',
        'tables::actions.disable-reordering' => 'lucide-check',
        'tables::actions.enable-reordering' => 'lucide-arrow-up-down',
        'tables::actions.filter' => 'lucide-funnel',
        'tables::actions.group' => 'lucide-layers',
        'tables::actions.open-bulk-actions' => 'lucide-ellipsis-vertical',
        'tables::columns.collapse-button' => 'lucide-chevron-down',
        'tables::columns.icon-column.false' => 'lucide-circle-x',
        'tables::columns.icon-column.true' => 'lucide-circle-check',
        'tables::empty-state' => 'lucide-inbox',
        'tables::filters.remove-all-button' => 'lucide-x',
        'tables::grouping.collapse-button' => 'lucide-chevron-up',
        'tables::header-cell.sort-asc-button' => 'lucide-chevron-up',
        'tables::header-cell.sort-button' => 'lucide-chevrons-up-down',
        'tables::header-cell.sort-desc-button' => 'lucide-chevron-down',
        'tables::reorder.handle' => 'lucide-grip-vertical',
        'tables::search-field' => 'lucide-search',
        'widgets::chart-widget.empty-state' => 'lucide-chart-no-axes-column',
        'widgets::chart-widget.filter' => 'lucide-funnel',
    ];

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'kinetics';
    }

    /**
     * Sets the layout Kinetics is designed for. Panel methods called after
     * ->plugin() still override any of these.
     */
    public function register(Panel $panel): void
    {
        $panel
            ->icons(self::ICONS)
            ->font('Open Runde', provider: LocalFontProvider::class)
            ->topbar(false)
            ->sidebarCollapsibleOnDesktop()
            ->breadcrumbs(false)
            ->maxContentWidth(Width::Full)
            ->globalSearch(position: GlobalSearchPosition::Sidebar)
            ->userMenu(position: UserMenuPosition::Sidebar)
            ->userMenuItems([
                'logout' => fn (Action $action): Action => $action->icon(null),
            ])
            // kinetics.css sets this variable: empty means the imports are
            // missing, "wrong" means layers.css isn't first.
            ->renderHook(PanelsRenderHook::SCRIPTS_AFTER, fn (): HtmlString => new HtmlString(<<<'HTML'
                <script>
                    (() => {
                        const order = getComputedStyle(document.body).getPropertyValue('--kinetics-layer-order').trim()

                        if (order === 'wrong') {
                            console.warn("Kinetics: layers.css must be imported before Filament's theme in your theme.css: https://github.com/otatechie/filament-kinetics/blob/main/docs/troubleshooting.md")
                        } else if (order !== 'ok') {
                            console.warn('Kinetics: the theme CSS is missing. Add the Kinetics imports to your theme.css: https://github.com/otatechie/filament-kinetics/blob/main/docs/troubleshooting.md')
                        }
                    })()
                </script>
                HTML));
    }

    public function boot(Panel $panel): void
    {
        // Without a custom theme the panel gets Kinetics' layout but Filament's
        // look, so stop with directions instead.
        if (blank($panel->getViteTheme()) && $panel->getTheme() === $panel->getDefaultTheme()) {
            throw new LogicException(
                "Kinetics needs a custom theme on the [{$panel->getId()}] panel. Run `php artisan make:filament-theme`, "
                .'add the Kinetics imports to its theme.css, and set ->viteTheme() on the panel. '
                .'See https://github.com/otatechie/filament-kinetics#installation',
            );
        }

        // Bottom corner: the top of the page holds the header's buttons. The
        // panel's ->bootUsing() runs after this, so it can move them back.
        Notifications::alignment(Alignment::End);
        Notifications::verticalAlignment(VerticalAlignment::End);
    }
}
