<?php

namespace Otatechie\Kinetics;

use Closure;
use Filament\Actions\Action;
use Filament\Contracts\Plugin;
use Filament\Enums\GlobalSearchPosition;
use Filament\Enums\UserMenuPosition;
use Filament\Facades\Filament;
use Filament\FontProviders\LocalFontProvider;
use Filament\Notifications\Livewire\Notifications;
use Filament\Notifications\Notification;
use Filament\Panel;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Support\Enums\Width;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use LogicException;
use Otatechie\Kinetics\Appearance\AppearanceSettings;
use Otatechie\Kinetics\Pages\Appearance;
use UnitEnum;

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

    protected bool $hasAppearancePage = false;

    protected ?Closure $authorizeAppearance = null;

    protected string|UnitEnum|null $appearanceNavigationGroup = null;

    protected ?int $appearanceNavigationSort = null;

    protected ?AppearanceSettings $appearance = null;

    public static function make(): static
    {
        return app(static::class);
    }

    /** The plugin on the current panel. */
    public static function get(): static
    {
        return Filament::getCurrentOrDefaultPanel()->getPlugin('kinetics');
    }

    /**
     * Adds the Appearance page, where colours, shape, font, dark mode and
     * layout can be changed from the panel, for everyone. Off, the page is
     * hidden and anything saved on it is ignored.
     *
     * @param  ?Closure(mixed $user): bool  $authorize  Who can open it. Without one, anyone who can use the panel.
     */
    public function appearancePage(
        bool $condition = true,
        ?Closure $authorize = null,
        string|UnitEnum|null $navigationGroup = null,
        ?int $navigationSort = null,
    ): static {
        $this->hasAppearancePage = $condition;
        $this->authorizeAppearance = $authorize;
        $this->appearanceNavigationGroup = $navigationGroup;
        $this->appearanceNavigationSort = $navigationSort;

        return $this;
    }

    public function canManageAppearance(): bool
    {
        if (! $this->hasAppearancePage) {
            return false;
        }

        return $this->authorizeAppearance === null || (bool) ($this->authorizeAppearance)(Filament::auth()->user());
    }

    public function getAppearanceNavigationGroup(): string|UnitEnum|null
    {
        return $this->appearanceNavigationGroup;
    }

    public function getAppearanceNavigationSort(): ?int
    {
        return $this->appearanceNavigationSort;
    }

    public function appearance(): AppearanceSettings
    {
        return $this->appearance ??= new AppearanceSettings(Filament::getCurrentOrDefaultPanel()->getId());
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
            // Short groups gain nothing from collapsing, and a collapsed group hides pages.
            ->collapsibleNavigationGroups(false)
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
                HTML))
            // Toasts in the bottom corner stack into a deck, newest in front,
            // and spread out on hover. CSS can't read their heights, so this
            // measures them and sets the variables kinetics.css stacks with.
            ->renderHook(PanelsRenderHook::SCRIPTS_AFTER, fn (): HtmlString => new HtmlString(<<<'HTML'
                <script>
                    (() => {
                        const peek = 10

                        const stack = (deck) => {
                            const toasts = [...deck.children].filter((toast) => toast.classList.contains('fi-no-notification') && getComputedStyle(toast).display !== 'none')
                            const front = toasts.at(-1)

                            deck.toggleAttribute('data-kinetics-stacked', toasts.length > 1)

                            if (! front) {
                                return
                            }

                            deck.style.setProperty('--kinetics-toast-front-height', `${front.offsetHeight}px`)

                            toasts.forEach((toast, index) => {
                                const depth = toasts.length - 1 - index

                                toast.style.setProperty('--kinetics-toast-depth', depth)
                                toast.style.setProperty('--kinetics-toast-shift', `${front.offsetTop - toast.offsetTop - depth * peek}px`)
                                toast.toggleAttribute('data-kinetics-toast-front', depth === 0)
                                toast.toggleAttribute('data-kinetics-toast-buried', depth > 2)
                            })
                        }

                        const watch = () => {
                            const deck = document.querySelector('.fi-no.fi-vertical-align-end')

                            if (! deck || deck.kineticsObserver) {
                                return
                            }

                            deck.kineticsObserver = new MutationObserver(() => requestAnimationFrame(() => stack(deck)))
                            // Toasts are added hidden and shown by a class change, so
                            // watch classes as well as additions and removals.
                            deck.kineticsObserver.observe(deck, { childList: true, subtree: true, attributes: true, attributeFilter: ['class'] })
                            stack(deck)
                        }

                        watch()
                        document.addEventListener('DOMContentLoaded', watch)
                        document.addEventListener('livewire:navigated', watch)
                    })()
                </script>
                HTML));

        if ($this->hasAppearancePage) {
            $panel
                ->pages([Appearance::class])
                ->renderHook(PanelsRenderHook::HEAD_END, fn (): HtmlString => $this->appearance()->css());
        }
    }

    /**
     * Whether a search, filters or both are narrowing the table, or null.
     *
     * @return 'search'|'filters'|'both'|null
     */
    protected static function emptiedBy(Table $table): ?string
    {
        $isSearching = filled($table->getLivewire()->getTableSearch());
        $hasFilters = count($table->getFilterIndicators()) > ($isSearching ? 1 : 0);

        return match (true) {
            $isSearching && $hasFilters => 'both',
            $isSearching => 'search',
            $hasFilters => 'filters',
            default => null,
        };
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

        // After the panel's own configuration, so saved choices win.
        if ($this->hasAppearancePage) {
            $this->appearance()->apply($panel);
        }

        // Confirmations read like every other modal: from the left, with the
        // button that acts first. Calls on your own actions after ::make()
        // still win.
        Action::configureUsing(fn (Action $action): Action => $action
            ->modalAlignment(Alignment::Start)
            ->modalFooterActionsAlignment(Alignment::Start));

        // Errors and warnings stay until they're closed: they usually need
        // something done, and six seconds isn't always enough to read them.
        // A notification's own ->duration() still wins.
        Notification::configureUsing(fn (Notification $notification): Notification => $notification
            ->duration(fn (): int|string => in_array($notification->getStatus(), ['danger', 'warning'], true) ? 'persistent' : 6000));

        // A table emptied by a search or filters says so, instead of "No
        // orders", and offers to clear them. A table's own empty state wins.
        Table::configureUsing(fn (Table $table): Table => $table
            ->emptyStateHeading(fn (Table $table): ?string => match (self::emptiedBy($table)) {
                'search', 'both' => __('kinetics::tables.empty.search.heading', ['search' => $table->getLivewire()->getTableSearch()]),
                'filters' => __('kinetics::tables.empty.filters.heading', ['model' => $table->getPluralModelLabel()]),
                default => null,
            })
            ->emptyStateDescription(fn (Table $table): ?string => match (self::emptiedBy($table)) {
                'search' => __('kinetics::tables.empty.search.description'),
                'filters', 'both' => __('kinetics::tables.empty.filters.description'),
                default => null,
            })
            ->emptyStateActions([
                Action::make('kineticsClearSearchAndFilters')
                    ->label(fn (HasTable $livewire): string => match (self::emptiedBy($livewire->getTable())) {
                        'search' => __('kinetics::tables.empty.actions.clear_search'),
                        'filters' => __('kinetics::tables.empty.actions.clear_filters'),
                        default => __('kinetics::tables.empty.actions.clear_search_and_filters'),
                    })
                    ->color('gray')
                    ->visible(fn (HasTable $livewire): bool => self::emptiedBy($livewire->getTable()) !== null)
                    ->action(fn (HasTable $livewire) => $livewire->removeTableFilters()),
            ]));
    }
}
