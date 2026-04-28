@aware([
    'currentReleaseTagName' => null
])

<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ action(\App\Http\Controllers\Ampp\DashboardController::class) }}">
            <img src="{{ asset('images/logos/ampp_logo_sidenav.png') }}" alt="logo" class="d-block mx-auto img-fluid">
        </a>

        <ul class="sidebar-nav">
            {{-- Dashboard --}}
            <x-ui.sidebar-item :href="action(\App\Http\Controllers\Ampp\DashboardController::class)">
                <i class="fas fa-tachometer-alt align-middle" style="color: #8B5CF6"></i>
                <span class="align-middle">{{ __('Dashboard') }}</span>
            </x-ui.sidebar-item>

            <li class="sidebar-header">
                {{ __('Automation') }}
            </li>

            {{-- Connections --}}
            <x-ui.sidebar-item
                :href="action(\App\Http\Controllers\Ampp\Connections\IndexConnectionController::class)"
                :show="auth()->user()->can('viewAny', \App\Models\Connection::class)"
            >
                <i class="fas fa-plug align-middle" style="color: #FCA5A5"></i>
                <span class="align-middle">{{ __('Connections') }}</span>
            </x-ui.sidebar-item>

            {{-- API --}}
            @can('api documentation')
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('scribe') }}" target="_blank">
                        <i class="fas fa-link align-middle" style="color: #F9A8D4"></i>
                        <span class="align-middle">{{ __('API') }}</span>
                    </a>
                </li>
            @endcan

            <li class="sidebar-header">
                {{ __('Management') }}
            </li>

            {{-- Clients --}}
            <x-ui.sidebar-dropdown
                id="clientsNav"
                icon="fas fa-user-tag"
                icon-color="#7DD3FC"
                :text="__('Clients')"
                :show="auth()->user()->can('viewAny', \App\Models\Client::class)"
            >
                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\Clients\IndexClientController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\Client::class)"
                >
                    {{ __('All clients') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\ClientContacts\IndexClientContactController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\ClientContact::class)"
                >
                    {{ __('Client contacts') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\Deals\IndexDealBoardController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\Deal::class)"
                >
                    {{ __('Leads') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\Maps\ClientMapController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\Client::class)"
                >
                    {{ __('Map') }}
                </x-ui.sidebar-item>
            </x-ui.sidebar-dropdown>

            {{-- Webshop --}}
            <x-ui.sidebar-dropdown
                id="webshopNav"
                icon="fas fa-store"
                icon-color="#A3E635"
                :text="__('Webshop')"
                :show="auth()->user()->can('viewAny', \App\Models\Product::class)"
            >
                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\Products\IndexProductController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\Product::class)"
                >
                    {{ __('Products') }}
                </x-ui.sidebar-item>
            </x-ui.sidebar-dropdown>

            {{-- Billing --}}
            <x-ui.sidebar-dropdown
                id="billingNav"
                icon="fas fa-file-invoice-dollar"
                icon-color="#16A34A"
                :text="__('Billing')"
                :show="
                    auth()->user()->can('viewAny', \App\Models\Quotation::class) ||
                    auth()->user()->can('viewAny', \App\Models\Invoice::class) ||
                    auth()->user()->can('viewAny', \App\Models\Expense::class) ||
                    auth()->user()->can('view billing reports') ||
                    auth()->user()->can('viewAny', \App\Models\Supplier::class)
                "
            >
                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\Quotations\IndexQuotationController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\Quotation::class)"
                >
                    {{ __('Quotations') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\Invoices\IndexInvoiceController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\Invoice::class)"
                >
                    {{ __('Invoices') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\Expenses\IndexExpenseController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\Expense::class)"
                >
                    {{ __('Expenses') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\BillingReports\IndexBillingReportController::class)"
                    :show="auth()->user()->can('view billing reports')"
                >
                    {{ __('Billing reports') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\Suppliers\IndexSupplierController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\Supplier::class)"
                >
                    {{ __('Suppliers') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\InvoiceCategories\IndexInvoiceCategoryController::class)"
                >
                    {{ __('Invoice categories') }}
                </x-ui.sidebar-item>
            </x-ui.sidebar-dropdown>

            <li class="sidebar-header">
                {{ __('Productivity') }}
            </li>

            {{-- Projects + reports --}}
            <x-ui.sidebar-dropdown
                id="projectsNav"
                icon="fas fa-clipboard-list"
                icon-color="#818CF8"
                :text="__('Projects')"
                :show="
                    auth()->user()->can('viewAny', \App\Models\Project::class) &&
                    auth()->user()->can('reports', \App\Models\Project::class)
                "
            >
                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\Projects\IndexProjectController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\Project::class)"
                >
                    {{ __('All projects') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\ProjectReports\IndexProjectReportsController::class)"
                    :show="auth()->user()->can('reports', \App\Models\Project::class)"
                >
                    {{ __('Project reports') }}
                </x-ui.sidebar-item>
            </x-ui.sidebar-dropdown>

            <x-ui.sidebar-item {{-- show only when user can not see reports --}}
                               :href="action(\App\Http\Controllers\Ampp\Projects\IndexProjectController::class)"
                               :show="
                    auth()->user()->can('viewAny', \App\Models\Project::class) &&
                    auth()->user()->cannot('reports', \App\Models\Project::class)
                "
            >
                <i class="fas fa-clipboard-list align-middle" style="color: #818CF8"></i>
                <span class="align-middle">{{ __('Projects') }}</span>
            </x-ui.sidebar-item>

            {{-- Time registrations --}}
            <x-ui.sidebar-item
                :href="action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexDayTimeRegistrationController::class)"
                :show="auth()->user()->can('viewAny', \App\Models\TimeRegistration::class)"
                :extra-active-urls="[
                    action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexWeekTimeRegistrationController::class),
                    action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexMonthTimeRegistrationController::class),
                    action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexTableTimeRegistrationController::class),
                ]"
            >
                <i class="fas fa-stopwatch align-middle" style="color: #22D3EE"></i>
                <span class="align-middle">{{ __('Time registrations') }}</span>
            </x-ui.sidebar-item>

            {{-- Invoicing --}}
            <x-ui.sidebar-dropdown
                id="invoicingNav"
                icon="fas fa-file-invoice"
                icon-color="#F59E0B"
                :text="__('Invoicing')"
                :show="auth()->user()->can('viewAny', \App\Models\Invoice::class)"
            >
                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\RecurringInvoices\IndexRecurringInvoiceController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\Invoice::class)"
                >
                    {{ __('Recurring invoices') }}
                </x-ui.sidebar-item>
            </x-ui.sidebar-dropdown>

            <x-ui.sidebar-item
                :href="action(\App\Http\Controllers\Ampp\CompanyPolicies\IndexCompanyPolicyController::class)"
                :show="auth()->user()->can('viewAny', \App\Models\TimeRegistration::class)"
            >
                <i class="fas fa-handshake align-middle" style="color: #0055c5ff"></i>
                <span class="align-middle">{{ __('Life @ BMK') }}</span>
            </x-ui.sidebar-item>

            {{-- Tools --}}
            <x-ui.sidebar-dropdown
                id="toolsNav"
                icon="fas fa-toolbox"
                icon-color="#FDBA74"
                :text="__('Tools')"
                :show="
                    auth()->user()->can('viewAny', \App\Models\GdprAudit::class) ||
                    auth()->user()->can('viewAny', \App\Models\GdprMessage::class) ||
                    auth()->user()->can('viewAny', \App\Models\GdprChecklist::class) ||
                    auth()->user()->can('viewAny', \App\Models\GdprRegister::class) ||
                    auth()->user()->can('viewAny', \App\Models\QrCode::class)
                "
            >
                <x-ui.sidebar-item href="#">
                    {{ __('Mail') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\QrCodes\IndexQrCodeController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\QrCode::class)"
                >
                    {{ __('QR codes') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-dropdown
                    id="gdprNav"
                    :text="__('PrivacyPro')"
                    parent="toolsNav"
                    :show="auth()->user()->canAny('viewAny', [
                        \App\Models\GdprAudit::class,
                        \App\Models\GdprMessage::class,
                        \App\Models\GdprChecklist::class,
                        \App\Models\GdprRegister::class
                    ])"
                >
                    <x-ui.sidebar-item
                        :href="action(\App\Http\Controllers\Ampp\GdprChecklists\IndexGdprChecklistController::class)"
                        :show="auth()->user()->can('viewAny', \App\Models\GdprChecklist::class)"
                    >
                        {{ __('Checklist') }}
                    </x-ui.sidebar-item>

                    <x-ui.sidebar-item
                        :href="action(\App\Http\Controllers\Ampp\GdprRegisters\IndexGdprRegisterController::class)"
                        :show="auth()->user()->can('viewAny', \App\Models\GdprRegister::class)"
                    >
                        {{ __('GDPR register') }}
                    </x-ui.sidebar-item>

                    <x-ui.sidebar-item
                        :href="action(\App\Http\Controllers\Ampp\GdprMessages\IndexGdprMessageController::class)"
                        :show="auth()->user()->can('viewAny', \App\Models\GdprMessage::class)"
                    >
                        {{ __('Messages') }}
                    </x-ui.sidebar-item>

                    <x-ui.sidebar-item
                        :href="action(\App\Http\Controllers\Ampp\GdprAudits\IndexGdprAuditController::class)"
                        :show="auth()->user()->can('viewAny', \App\Models\GdprAudit::class)"
                    >
                        {{ __('Audit') }}
                    </x-ui.sidebar-item>
                </x-ui.sidebar-dropdown>
            </x-ui.sidebar-dropdown>
            <x-ui.sidebar-dropdown
                id="helpdeskNav"
                icon="fas fa-headset"
                icon-color="#F87171"
                :text="__('Helpdesk')"
                :show="auth()->user()->can('view helpdesk')"
            >
                <x-ui.sidebar-item :href="route('overview')">
                    {{ __('Overview') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item :href="route('status-board')">
                    {{ __('Status Board') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item :href="route('agents-board')">
                    {{ __('Agents Board') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item :href="route('tickets.index')">
                    {{ __('All tickets') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item :href="route('tickets.create')">
                    {{ __('New ticket') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\Clients\IndexClientController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\Client::class)"
                >
                    {{ __('Clients') }}
                </x-ui.sidebar-item>

                <x-ui.sidebar-item
                    :href="route('ai-skill.index')"
                    :show="auth()->user()->can('manage ai skill')"
                >
                    {{ __('AI Skill Management') }}
                </x-ui.sidebar-item>
            </x-ui.sidebar-dropdown>

            @can('view admin zone')
                <li class="sidebar-header">
                    {{ __('Administration') }}
                </li>

                {{-- Users --}}
                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Admin\Users\IndexUserController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\User::class)"
                >
                    <i class="fas fa-users align-middle" style="color: #3B82F6"></i>
                    <span class="align-middle">{{ __('Users') }}</span>
                </x-ui.sidebar-item>

                {{-- Createn connection --}}
                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Admin\Connections\CreateConnectionController::class)"
                    :show="auth()->user()->can('create', \App\Models\Connection::class)"
                >
                    <i class="fas fa-plug align-middle" style="color: #FCA5A5"></i>
                    <span class="align-middle">{{ __('Create connection') }}</span>
                </x-ui.sidebar-item>

                {{-- Security --}}
                <x-ui.sidebar-dropdown
                    id="security"
                    icon="fas fa-lock"
                    icon-color="#FBBF24"
                    :text="__('Security')"
                    :show="
                        auth()->user()->can('viewAny', \Spatie\Permission\Models\Role::class) ||
                        auth()->user()->can('viewAny', \Spatie\Permission\Models\Permission::class) ||
                        auth()->user()->can('viewAny', \Laravel\Passport\Client::class)
                    "
                >
                    <x-ui.sidebar-item
                        :href="action(\App\Http\Controllers\Admin\Roles\IndexRoleController::class)"
                        :show="auth()->user()->can('viewAny', \Spatie\Permission\Models\Role::class)"
                    >
                        {{ __('Roles') }}
                    </x-ui.sidebar-item>

                    <x-ui.sidebar-item
                        :href="action(\App\Http\Controllers\Admin\Permissions\IndexPermissionController::class)"
                        :show="auth()->user()->can('viewAny', \Spatie\Permission\Models\Permission::class)"
                    >
                        {{ __('Permissions') }}
                    </x-ui.sidebar-item>

                    <x-ui.sidebar-item
                        :href="action(\App\Http\Controllers\Admin\PassportClients\IndexPassportClientController::class)"
                        :show="auth()->user()->can('viewAny', \Laravel\Passport\Client::class)"
                    >
                        {{ __('Passport clients') }}
                    </x-ui.sidebar-item>
                </x-ui.sidebar-dropdown>

                {{-- Logs --}}
                <x-ui.sidebar-dropdown
                    id="logs"
                    icon="fas fa-book"
                    icon-color="#A8A29E"
                    :text="__('Logs')"
                    :show="
                        auth()->user()->can('viewAny', \Spatie\Activitylog\Models\Activity::class) ||
                        auth()->user()->can('viewAny', \App\Models\LoginLog::class)
                    "
                >
                    <x-ui.sidebar-item
                        :href="action(\App\Http\Controllers\Admin\ActivityLogs\IndexActivityLogController::class)"
                        :show="auth()->user()->can('viewAny', \Spatie\Activitylog\Models\Activity::class)"
                    >
                        {{ __('Activities') }}
                    </x-ui.sidebar-item>

                    <x-ui.sidebar-item
                        :href="action(\App\Http\Controllers\Admin\LoginLogs\IndexLoginLogController::class)"
                        :show="auth()->user()->can('viewAny', \App\Models\LoginLog::class)"
                    >
                        {{ __('Login logs') }}
                    </x-ui.sidebar-item>

                    <x-ui.sidebar-item
                        :href="action([\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])"
                        :show="auth()->user()->can('view system log')"
                    >
                        {{ __('System logs') }}
                    </x-ui.sidebar-item>
                </x-ui.sidebar-dropdown>

                {{-- Artisan --}}
                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Admin\Artisan\IndexArtisanController::class)"
                    :show="auth()->user()->can('artisan console')"
                >
                    <i class="fas fa-terminal align-middle" style="color: #818CF8"></i>
                    <span class="align-middle">{{ __('Artisan') }}</span>
                </x-ui.sidebar-item>

                {{-- Implementations --}}
                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Ampp\Implementations\IndexImplementationController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\Implementation::class)"
                >
                    <i class="fas fa-satellite-dish align-middle" style="color: #34d399"></i>
                    <span class="align-middle">{{ __('Implementations') }}</span>
                </x-ui.sidebar-item>

                {{-- Helpcenter --}}
                <x-ui.sidebar-item
                    :href="action(\App\Http\Controllers\Admin\HelpMessages\IndexHelpMessageController::class)"
                    :show="auth()->user()->can('viewAny', \App\Models\HelpMessage::class)"
                >
                    <i class="fas fa-hands-helping align-middle" style="color: #FCD34D;"></i>
                    <span class="align-middle">{{ __('Help center') }}</span>
                </x-ui.sidebar-item>
            @endcan
        </ul>

        <div class="text-gray-300 text-center small mb-1">
            {{ __('AMPP :version', ['version' => $currentReleaseTagName]) }}
            -
            <a href="{{ action(\App\Http\Controllers\Ampp\Releases\IndexReleaseController::class) }}">
                {{ __('Release notes') }}
            </a>
        </div>
    </div>
</nav>
