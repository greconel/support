<?php

use App\Http\Controllers\Admin\Connections\CreateConnectionController;
use App\Http\Controllers\Admin\Connections\StoreConnectionController;
use App\Http\Controllers\Ampp\BillingReports\IndexBillingReportController;
use App\Http\Controllers\Ampp\ClientContacts\CreateClientContactController;
use App\Http\Controllers\Ampp\ClientContacts\DestroyClientContactController;
use App\Http\Controllers\Ampp\ClientContacts\EditClientContactController;
use App\Http\Controllers\Ampp\ClientContacts\ExportClientContactsController;
use App\Http\Controllers\Ampp\ClientContacts\IndexClientContactController;
use App\Http\Controllers\Ampp\ClientContacts\ShowClientContactController;
use App\Http\Controllers\Ampp\ClientContacts\StoreClientContactController;
use App\Http\Controllers\Ampp\ClientContacts\UpdateClientContactController;
use App\Http\Controllers\Ampp\Clients\ArchiveClientController;
use App\Http\Controllers\Ampp\Clients\CreateClientController;
use App\Http\Controllers\Ampp\Clients\DestroyClientController;
use App\Http\Controllers\Ampp\Clients\EditClientController;
use App\Http\Controllers\Ampp\Clients\ExportClientsController;
use App\Http\Controllers\Ampp\Clients\IndexClientController;
use App\Http\Controllers\Ampp\Clients\RestoreClientController;
use App\Http\Controllers\Ampp\Clients\ShowClientController;
use App\Http\Controllers\Ampp\Clients\StoreClientController;
use App\Http\Controllers\Ampp\Clients\UpdateClientController;
use App\Http\Controllers\Ampp\Connections\IndexConnectionController;
use App\Http\Controllers\Ampp\Connections\UpdateConnectionController;
use App\Http\Controllers\Ampp\DashboardController;
use App\Http\Controllers\Ampp\Deals\DestroyDealController;
use App\Http\Controllers\Ampp\Deals\EditDealController;
use App\Http\Controllers\Ampp\Deals\IndexDealController;
use App\Http\Controllers\Ampp\Deals\ShowDealController;
use App\Http\Controllers\Ampp\Deals\UpdateDealController;
use App\Http\Controllers\Ampp\Deals\UpdateDealDueDateController;
use App\Http\Controllers\Ampp\Expenses\CreateExpenseController;
use App\Http\Controllers\Ampp\Expenses\DestroyExpenseController;
use App\Http\Controllers\Ampp\Expenses\EditExpenseController;
use App\Http\Controllers\Ampp\Expenses\ExportExpensesController;
use App\Http\Controllers\Ampp\Expenses\IndexClearfactsBulkExpenseController;
use App\Http\Controllers\Ampp\Expenses\IndexExpenseController;
use App\Http\Controllers\Ampp\Expenses\ShowExpenseController;
use App\Http\Controllers\Ampp\Expenses\StoreExpenseController;
use App\Http\Controllers\Ampp\Expenses\UpdateExpenseController;
use App\Http\Controllers\Ampp\Expenses\UploadClearfactsBulkExpenseController;
use App\Http\Controllers\Ampp\Expenses\UploadExpenseToClearfactsController;
use App\Http\Controllers\Ampp\GdprAudits\CreateGdprAuditController;
use App\Http\Controllers\Ampp\GdprAudits\DestroyGdprAuditController;
use App\Http\Controllers\Ampp\GdprAudits\EditGdprAuditController;
use App\Http\Controllers\Ampp\GdprAudits\IndexGdprAuditController;
use App\Http\Controllers\Ampp\GdprAudits\StoreGdprAuditController;
use App\Http\Controllers\Ampp\GdprAudits\UpdateGdprAuditController;
use App\Http\Controllers\Ampp\GdprChecklists\EditGdprChecklistController;
use App\Http\Controllers\Ampp\GdprChecklists\IndexGdprChecklistController;
use App\Http\Controllers\Ampp\GdprChecklists\UpdateGdprChecklistController;
use App\Http\Controllers\Ampp\GdprMessages\CreateGdprMessageController;
use App\Http\Controllers\Ampp\GdprMessages\DestroyGdprMessageController;
use App\Http\Controllers\Ampp\GdprMessages\EditGdprMessageController;
use App\Http\Controllers\Ampp\GdprMessages\IndexGdprMessageController;
use App\Http\Controllers\Ampp\GdprMessages\StoreGdprMessageController;
use App\Http\Controllers\Ampp\GdprMessages\UpdateGdprMessageController;
use App\Http\Controllers\Ampp\GdprRegisters\CreateGdprRegisterController;
use App\Http\Controllers\Ampp\GdprRegisters\DestroyGdprRegisterController;
use App\Http\Controllers\Ampp\GdprRegisters\EditGdprRegisterController;
use App\Http\Controllers\Ampp\GdprRegisters\IndexGdprRegisterController;
use App\Http\Controllers\Ampp\GdprRegisters\StoreGdprRegisterController;
use App\Http\Controllers\Ampp\GdprRegisters\UpdateGdprRegisterController;
use App\Http\Controllers\Ampp\HelpMessages\IndexHelpMessageController;
use App\Http\Controllers\Ampp\HelpMessages\ShowHelpMessageController;
use App\Http\Controllers\Ampp\Invoices\BulkDisableClearfactsController;
use App\Http\Controllers\Ampp\Invoices\ConvertInvoiceToCreditNoteController;
use App\Http\Controllers\Ampp\Invoices\CreateInvoiceController;
use App\Http\Controllers\Ampp\Invoices\DestroyInvoiceController;
use App\Http\Controllers\Ampp\Invoices\DuplicateInvoiceController;
use App\Http\Controllers\Ampp\Invoices\EditInvoiceLinesController;
use App\Http\Controllers\Ampp\Invoices\ExportInvoicesController;
use App\Http\Controllers\Ampp\Invoices\IndexClearfactsBulkInvoiceController;
use App\Http\Controllers\Ampp\Invoices\IndexInvoiceController;
use App\Http\Controllers\Ampp\Invoices\ShowInvoiceController;
use App\Http\Controllers\Ampp\Invoices\StoreInvoiceController;
use App\Http\Controllers\Ampp\Invoices\UploadClearfactsBulkInvoiceController;
use App\Http\Controllers\Ampp\Invoices\UploadInvoiceToClearfactsController;
use App\Http\Controllers\Ampp\Maps\ClientMapController;
use App\Http\Controllers\Ampp\Products\CreateProductController;
use App\Http\Controllers\Ampp\Products\DestroyProductController;
use App\Http\Controllers\Ampp\Products\EditProductController;
use App\Http\Controllers\Ampp\Products\ExportProductsController;
use App\Http\Controllers\Ampp\Products\IndexProductController;
use App\Http\Controllers\Ampp\Products\StoreProductController;
use App\Http\Controllers\Ampp\Products\UpdateProductController;
use App\Http\Controllers\Ampp\Profile\EditProfileController;
use App\Http\Controllers\Ampp\Profile\UpdatePasswordController;
use App\Http\Controllers\Ampp\Profile\UpdateProfileController;
use App\Http\Controllers\Ampp\ProjectLinks\DestroyProjectLinkController;
use App\Http\Controllers\Ampp\ProjectLinks\ShowProjectLinkController;
use App\Http\Controllers\Ampp\ProjectLinks\StoreProjectLinkController;
use App\Http\Controllers\Ampp\ProjectReports\IndexProjectReportsController;
use App\Http\Controllers\Ampp\ProjectReports\ShowDetailedTimeReportController;
use App\Http\Controllers\Ampp\Projects\ArchiveProjectController;
use App\Http\Controllers\Ampp\Projects\CreateProjectController;
use App\Http\Controllers\Ampp\Projects\DestroyProjectController;
use App\Http\Controllers\Ampp\Projects\EditProjectController;
use App\Http\Controllers\Ampp\Projects\ExportProjectsController;
use App\Http\Controllers\Ampp\Projects\IndexProjectController;
use App\Http\Controllers\Ampp\Projects\JoinProjectController;
use App\Http\Controllers\Ampp\Projects\LeaveProjectController;
use App\Http\Controllers\Ampp\Projects\RestoreProjectController;
use App\Http\Controllers\Ampp\Projects\ShowProjectCalendarController;
use App\Http\Controllers\Ampp\Projects\ShowProjectEmailController;
use App\Http\Controllers\Ampp\Projects\ShowProjectFileController;
use App\Http\Controllers\Ampp\Projects\ShowProjectNoteController;
use App\Http\Controllers\Ampp\Projects\ShowProjectOverviewController;
use App\Http\Controllers\Ampp\Projects\ShowProjectTimeRegistrationsController;
use App\Http\Controllers\Ampp\Projects\ShowProjectTodoController;
use App\Http\Controllers\Ampp\Projects\StoreProjectController;
use App\Http\Controllers\Ampp\Projects\UpdateProjectController;
use App\Http\Controllers\Ampp\QrCodes\CreateQrCodeController;
use App\Http\Controllers\Ampp\QrCodes\DestroyQrCodeController;
use App\Http\Controllers\Ampp\QrCodes\IndexQrCodeController;
use App\Http\Controllers\Ampp\QrCodes\ShowQrCodeController;
use App\Http\Controllers\Ampp\Quotations\ConvertQuotationToInvoiceController;
use App\Http\Controllers\Ampp\Quotations\CreateQuotationController;
use App\Http\Controllers\Ampp\Quotations\DestroyQuotationController;
use App\Http\Controllers\Ampp\Quotations\EditQuotationLinesController;
use App\Http\Controllers\Ampp\Quotations\ExportQuotationsController;
use App\Http\Controllers\Ampp\Quotations\IndexQuotationController;
use App\Http\Controllers\Ampp\Quotations\ShowQuotationController;
use App\Http\Controllers\Ampp\Quotations\StoreQuotationController;
use App\Http\Controllers\Ampp\Releases\ImportReleasesController;
use App\Http\Controllers\Ampp\Releases\IndexReleaseController;
use App\Http\Controllers\Ampp\Releases\MarkReleaseAsCurrentReleaseController;
use App\Http\Controllers\Ampp\Suppliers\CreateSupplierController;
use App\Http\Controllers\Ampp\Suppliers\DestroySupplierController;
use App\Http\Controllers\Ampp\Suppliers\EditSupplierController;
use App\Http\Controllers\Ampp\Suppliers\ExportSupplierController;
use App\Http\Controllers\Ampp\Suppliers\IndexSupplierController;
use App\Http\Controllers\Ampp\Suppliers\ShowSupplierController;
use App\Http\Controllers\Ampp\Suppliers\StoreSupplierController;
use App\Http\Controllers\Ampp\Suppliers\ToggleClearfactsController;
use App\Http\Controllers\Ampp\Suppliers\UpdateSupplierController;
use App\Http\Controllers\Ampp\TimeRegistrations\ExportTimeRegistrationsController;
use App\Http\Controllers\Ampp\TimeRegistrations\ExportTimeRegistrationsForProjectController;
use App\Http\Controllers\Ampp\TimeRegistrations\IndexDayTimeRegistrationController;
use App\Http\Controllers\Ampp\TimeRegistrations\IndexMonthTimeRegistrationController;
use App\Http\Controllers\Ampp\TimeRegistrations\IndexTableTimeRegistrationController;
use App\Http\Controllers\Ampp\TimeRegistrations\IndexWeekTimeRegistrationController;
use App\Http\Controllers\Ampp\CompanyPolicies\IndexCompanyPolicyController;
use App\Http\Controllers\Ampp\RecurringInvoices\CreateRecurringInvoiceController;
use App\Http\Controllers\Ampp\RecurringInvoices\DestroyRecurringInvoiceController;
use App\Http\Controllers\Ampp\RecurringInvoices\EditRecurringInvoiceController;
use App\Http\Controllers\Ampp\RecurringInvoices\EditRecurringInvoiceLinesController;
use App\Http\Controllers\Ampp\RecurringInvoices\GenerateInvoicesController;
use App\Http\Controllers\Ampp\RecurringInvoices\IndexRecurringInvoiceController;
use App\Http\Controllers\Ampp\RecurringInvoices\ShowRecurringInvoiceController;
use App\Http\Controllers\Ampp\RecurringInvoices\StoreRecurringInvoiceController;
use App\Http\Controllers\Ampp\RecurringInvoices\UpdateRecurringInvoiceController;
use App\Http\Controllers\Ampp\Helpdesk\AiSkill\IndexAiSkillController;
use App\Http\Controllers\Ampp\Helpdesk\AiSkill\TriggerUpdateAiSkillController;
use App\Http\Controllers\Ampp\Helpdesk\AiSkill\UpdateAiSkillController;
use App\Http\Controllers\Ampp\Helpdesk\Corrections\ExportCorrectionController;
use App\Http\Controllers\Ampp\Helpdesk\Corrections\ToggleIgnoreCorrectionController;
use App\Http\Controllers\Ampp\Helpdesk\Dashboard\AgentsBoardController;
use App\Http\Controllers\Ampp\Helpdesk\Dashboard\OverviewController;
use App\Http\Controllers\Ampp\Helpdesk\Dashboard\StatusBoardController;
use App\Http\Controllers\Ampp\Helpdesk\Tickets\CreateTicketController;
use App\Http\Controllers\Ampp\Helpdesk\Tickets\IndexTicketController;
use App\Http\Controllers\Ampp\Helpdesk\Tickets\MoveTicketController;
use App\Http\Controllers\Ampp\Helpdesk\Tickets\ShowTicketController;
use App\Http\Controllers\Ampp\Helpdesk\Tickets\StoreTicketController;
use App\Http\Controllers\Ampp\Helpdesk\Tickets\StoreTicketReplyController;
use App\Http\Controllers\Ampp\Helpdesk\Tickets\UpdateTicketController;

Route::get('dashboard', DashboardController::class);

Route::prefix('profile')->name('profile.')->group(function (){
    Route::get('/', EditProfileController::class);
    Route::patch('/', UpdateProfileController::class);
    Route::patch('password', UpdatePasswordController::class);
});

Route::prefix('help-messages')->group(function (){
    Route::get('/', IndexHelpMessageController::class);
    Route::get('{helpMessage}', ShowHelpMessageController::class);
});

Route::prefix('connections')->group(function (){
    Route::get('/', IndexConnectionController::class);
    Route::get('create', CreateConnectionController::class);
    Route::post('/', StoreConnectionController::class);
    Route::patch('connections/{connection}', UpdateConnectionController::class);
});

Route::prefix('clients')->group(function (){
    Route::get('/', IndexClientController::class);
    Route::get('create', CreateClientController::class);
    Route::post('/', StoreClientController::class);
    Route::get('export', ExportClientsController::class);
    Route::get('{client}', ShowClientController::class);
    Route::get('{client}/edit', EditClientController::class);
    Route::patch('{client}', UpdateClientController::class);
    Route::delete('{client}', DestroyClientController::class);
    Route::patch('{client}/archive', ArchiveClientController::class);
    Route::patch('{client}/restore', RestoreClientController::class);

    Route::get('{client}/contacts/create', CreateClientContactController::class);
    Route::post('{client}/contacts', StoreClientContactController::class);
});

Route::prefix('contacts')->group(function (){
    Route::get('/', IndexClientContactController::class);
    Route::get('export', ExportClientContactsController::class);
    Route::get('{clientContact}', ShowClientContactController::class);
    Route::get('{clientContact}/edit', EditClientContactController::class);
    Route::patch('{clientContact}', UpdateClientContactController::class);
    Route::delete('{clientContact}', DestroyClientContactController::class);
});

Route::prefix('leads')->group(function (){
    Route::get('board', \App\Http\Controllers\Ampp\Deals\IndexDealBoardController::class);
    Route::get('table', \App\Http\Controllers\Ampp\Deals\IndexDealTableController::class);
    Route::get('{deal}', \App\Http\Controllers\Ampp\Deals\ShowDealController::class);
    Route::get('{deal}/edit', \App\Http\Controllers\Ampp\Deals\EditDealController::class);
    Route::patch('{deal}/edit', \App\Http\Controllers\Ampp\Deals\UpdateDealController::class);
    Route::patch('{deal}/update-due-date', \App\Http\Controllers\Ampp\Deals\UpdateDealDueDateController::class);
    Route::delete('{deal}', \App\Http\Controllers\Ampp\Deals\DestroyDealController::class);
});

Route::prefix('maps')->group(function (){
    Route::get('clients', ClientMapController::class);
});

Route::prefix('products')->group(function (){
    Route::get('/', IndexProductController::class);
    Route::get('create', CreateProductController::class);
    Route::post('/', StoreProductController::class);
    Route::get('export', ExportProductsController::class);
    Route::get('{product}/edit', EditProductController::class);
    Route::patch('{product}', UpdateProductController::class);
    Route::delete('{product}', DestroyProductController::class);
});

Route::prefix('quotations')->group(function (){
    Route::get('/', IndexQuotationController::class);
    Route::get('create', CreateQuotationController::class);
    Route::get('export', ExportQuotationsController::class);
    Route::get('{quotation}', ShowQuotationController::class);
    Route::post('/', StoreQuotationController::class);
    Route::delete('{quotation}', DestroyQuotationController::class);
    Route::get('{quotation}/lines', EditQuotationLinesController::class);
    Route::get('{quotation}/convert-to-invoice', ConvertQuotationToInvoiceController::class);
});

Route::prefix('invoices')->group(function (){
    Route::get('/', IndexInvoiceController::class);
    Route::get('create', CreateInvoiceController::class);
    Route::get('export', ExportInvoicesController::class);
    Route::get('{invoice}', ShowInvoiceController::class);
    Route::post('/', StoreInvoiceController::class);
    Route::delete('{invoice}', DestroyInvoiceController::class);
    Route::get('{invoice}/lines', EditInvoiceLinesController::class);
    Route::get('{invoice}/duplicate', DuplicateInvoiceController::class);
    Route::get('{invoice}/convert-to-credit-note', ConvertInvoiceToCreditNoteController::class);
    Route::get('{invoice}/upload-to-clearfacts', UploadInvoiceToClearfactsController::class);
    Route::patch('{invoice}/toggle-clearfacts', \App\Http\Controllers\Ampp\Invoices\ToggleClearfactsController::class);
    Route::patch('{invoice}/confirm-exists-in-clearfacts', \App\Http\Controllers\Ampp\Invoices\ConfirmInvoiceExistsInClearfactsController::class);

    Route::prefix('clearfacts/bulk')->group(function (){
        Route::get('/', IndexClearfactsBulkInvoiceController::class);
        Route::post('/', UploadClearfactsBulkInvoiceController::class);
        Route::post('disable-selected', BulkDisableClearfactsController::class);
    });

    Route::prefix('clearfactsadministrations')->group(function (){ // TODO: url not working?? (https://ampp.dev/ampp/invoices/clearfactsadministrations
        Route::get('/', \App\Http\Controllers\Ampp\Invoices\FetchClearfactsAdministrationsController::class);
    });
});

Route::get('/test-clearfacts', \App\Http\Controllers\Ampp\Invoices\FetchClearfactsAdministrationsController::class);

Route::prefix('recurring-invoices')->group(function () {
    Route::get('/', IndexRecurringInvoiceController::class);
    Route::get('create', CreateRecurringInvoiceController::class);
    Route::post('/', StoreRecurringInvoiceController::class);
    Route::post('generate', GenerateInvoicesController::class);
    Route::get('{recurringInvoice}', ShowRecurringInvoiceController::class);
    Route::get('{recurringInvoice}/edit', EditRecurringInvoiceController::class);
    Route::patch('{recurringInvoice}', UpdateRecurringInvoiceController::class);
    Route::delete('{recurringInvoice}', DestroyRecurringInvoiceController::class);
    Route::get('{recurringInvoice}/lines', EditRecurringInvoiceLinesController::class);
});

Route::prefix('expenses')->group(function (){
    Route::get('/', IndexExpenseController::class);
    Route::get('create', CreateExpenseController::class);
    Route::post('/', StoreExpenseController::class);
    Route::get('export', ExportExpensesController::class);
    Route::get('{expense}', ShowExpenseController::class);
    Route::get('{expense}/edit', EditExpenseController::class);
    Route::patch('{expense}', UpdateExpenseController::class);
    Route::delete('{expense}', DestroyExpenseController::class);
    Route::get('{expense}/upload-to-clearfacts', UploadExpenseToClearfactsController::class);
    Route::get('clearfacts/bulk', IndexClearfactsBulkExpenseController::class);
    Route::post('clearfacts/bulk', UploadClearfactsBulkExpenseController::class);
    Route::patch('{expense}/confirm-exists-in-clearfacts', \App\Http\Controllers\Ampp\Expenses\ConfirmExpenseExistsInClearfactsController::class);
    Route::get('clearfacts/download', \App\Http\Controllers\Ampp\Expenses\DownloadClearfactsExpensesController::class);
});

Route::prefix('billing-reports')->group(function (){
    Route::get('/', IndexBillingReportController::class);
});

Route::prefix('invoice-categories')->group(function (){
    Route::get('/', \App\Http\Controllers\Ampp\InvoiceCategories\IndexInvoiceCategoryController::class);
    Route::get('create', \App\Http\Controllers\Ampp\InvoiceCategories\CreateInvoiceCategoryController::class);
    Route::post('/', \App\Http\Controllers\Ampp\InvoiceCategories\StoreInvoiceCategoryController::class);
    Route::get('{invoiceCategory}/edit', \App\Http\Controllers\Ampp\InvoiceCategories\EditInvoiceCategoryController::class);
    Route::patch('{invoiceCategory}', \App\Http\Controllers\Ampp\InvoiceCategories\UpdateInvoiceCategoryController::class);
    Route::delete('{invoiceCategory}', \App\Http\Controllers\Ampp\InvoiceCategories\DestroyInvoiceCategoryController::class);
});

Route::prefix('suppliers')->group(function (){
    Route::get('/', IndexSupplierController::class);
    Route::get('create', CreateSupplierController::class);
    Route::post('/', StoreSupplierController::class);
    Route::get('export', ExportSupplierController::class);
    Route::get('{supplier}', ShowSupplierController::class);
    Route::get('{supplier}/edit', EditSupplierController::class);
    Route::patch('{supplier}', UpdateSupplierController::class);
    Route::delete('{supplier}', DestroySupplierController::class);
    Route::patch('{supplier}/toggle-clearfacts', ToggleClearfactsController::class);
});

Route::prefix('projects')->group(function (){
    Route::get('/', IndexProjectController::class);
    Route::get('create', CreateProjectController::class);
    Route::post('/', StoreProjectController::class);
    Route::get('export', ExportProjectsController::class);
    Route::get('{project}/overview', ShowProjectOverviewController::class);
    Route::get('{project}/to-dos', ShowProjectTodoController::class);
    Route::get('{project}/calendar', ShowProjectCalendarController::class);
    Route::get('{project}/time-registrations', ShowProjectTimeRegistrationsController::class);
    Route::get('{project}/time-registrations/export', ExportTimeRegistrationsForProjectController::class);
    Route::get('{project}/files', ShowProjectFileController::class);
    Route::get('{project}/emails', ShowProjectEmailController::class);
    Route::get('{project}/notes', ShowProjectNoteController::class);
    Route::get('{project}/edit', EditProjectController::class);
    Route::patch('{project}', UpdateProjectController::class);
    Route::delete('{project}', DestroyProjectController::class);
    Route::patch('{project}/archive', ArchiveProjectController::class);
    Route::patch('{project}/restore', RestoreProjectController::class);
    Route::get('{project}/join', JoinProjectController::class);
    Route::get('{project}/leave', LeaveProjectController::class);

    Route::get('{project}/links', \App\Http\Controllers\Ampp\ProjectLinks\ShowProjectLinkController::class);
    Route::post('{project}/links', \App\Http\Controllers\Ampp\ProjectLinks\StoreProjectLinkController::class);
    Route::delete('{project}/links/{projectLink}', \App\Http\Controllers\Ampp\ProjectLinks\DestroyProjectLinkController::class);

    Route::get('{project}/activities', \App\Http\Controllers\Ampp\ProjectActivities\IndexProjectActivityController::class);
    Route::get('{project}/activities/create', \App\Http\Controllers\Ampp\ProjectActivities\CreateProjectActivityController::class);
    Route::post('{project}/activities/create', \App\Http\Controllers\Ampp\ProjectActivities\StoreProjectActivityController::class);
    Route::get('{project}/activities/{projectActivity}/edit', \App\Http\Controllers\Ampp\ProjectActivities\EditProjectActivityController::class);
    Route::patch('{project}/activities/{projectActivity}/edit', \App\Http\Controllers\Ampp\ProjectActivities\UpdateProjectActivityController::class);
    Route::delete('activities/{projectActivity}/delete', \App\Http\Controllers\Ampp\ProjectActivities\DeleteProjectActivityController::class);
});

Route::prefix('project-reports')->group(function (){
    Route::get('/', IndexProjectReportsController::class);
    Route::get('detailed-time-report', ShowDetailedTimeReportController::class);
    Route::post('process-invoicing', \App\Http\Controllers\Ampp\ProjectReports\ProcessInvoicingController::class);
    Route::post('prepare-invoicing', \App\Http\Controllers\Ampp\ProjectReports\PrepareInvoicingController::class);
    Route::post('store-invoice', \App\Http\Controllers\Ampp\ProjectReports\StoreInvoiceFromTimeReportController::class);
});


Route::prefix('time-registrations')->group(function () {
    Route::get('day', IndexDayTimeRegistrationController::class);
    Route::get('week', IndexWeekTimeRegistrationController::class);
    Route::get('month', IndexMonthTimeRegistrationController::class);
    Route::get('table', IndexTableTimeRegistrationController::class);
    Route::get('export', ExportTimeRegistrationsController::class);
});


Route::prefix('qr-codes')->group(function(){
    Route::get('/', IndexQrCodeController::class);
    Route::get('create', CreateQrCodeController::class);
    Route::get('{qrCode}', ShowQrCodeController::class);
    Route::delete('{qrCode}', DestroyQrCodeController::class);
});

Route::prefix('gdpr-checklist')->group(function(){
    Route::get('/', IndexGdprChecklistController::class);
    Route::get('{gdprChecklist}/edit', EditGdprChecklistController::class);
    Route::patch('{project}', UpdateGdprChecklistController::class);
});

Route::prefix('gdpr-registers')->group(function (){
    Route::get('/', IndexGdprRegisterController::class);
    Route::get('create', CreateGdprRegisterController::class);
    Route::post('/', StoreGdprRegisterController::class);
    Route::get('{gdprRegister}/edit', EditGdprRegisterController::class);
    Route::patch('{gdprRegister}', UpdateGdprRegisterController::class);
    Route::delete('{gdprRegister}', DestroyGdprRegisterController::class);
});

Route::prefix('gdpr-messages')->group(function (){
    Route::get('/', IndexGdprMessageController::class);
    Route::get('create', CreateGdprMessageController::class);
    Route::post('/', StoreGdprMessageController::class);
    Route::get('{gdprMessage}/edit', EditGdprMessageController::class);
    Route::patch('{gdprMessage}', UpdateGdprMessageController::class);
    Route::delete('{gdprMessage}', DestroyGdprMessageController::class);
});

Route::prefix('gdpr-audits')->group(function (){
    Route::get('/', IndexGdprAuditController::class);
    Route::get('create', CreateGdprAuditController::class);
    Route::post('/', StoreGdprAuditController::class);
    Route::get('{gdprAudit}/edit', EditGdprAuditController::class);
    Route::patch('{gdprAudit}', UpdateGdprAuditController::class);
    Route::delete('{gdprAudit}', DestroyGdprAuditController::class);
});

Route::prefix('releases')->group(function (){
    Route::get('/', IndexReleaseController::class);
    Route::get('import', ImportReleasesController::class);
    Route::get('{release}/mark-as-current-release', MarkReleaseAsCurrentReleaseController::class);
});

Route::prefix('company-policies')->group(function (){
    Route::get('/', IndexCompanyPolicyController::class);
});

Route::prefix('implementations')->group(function (){
    Route::get('/', \App\Http\Controllers\Ampp\Implementations\IndexImplementationController::class);
    Route::get('create', \App\Http\Controllers\Ampp\Implementations\CreateImplementationController::class);
    Route::post('/', \App\Http\Controllers\Ampp\Implementations\StoreImplementationController::class);
    Route::get('{implementation}', \App\Http\Controllers\Ampp\Implementations\ShowImplementationController::class);
    Route::get('{implementation}/edit', \App\Http\Controllers\Ampp\Implementations\EditImplementationController::class);
    Route::put('{implementation}', \App\Http\Controllers\Ampp\Implementations\UpdateImplementationController::class);
    Route::delete('{implementation}', \App\Http\Controllers\Ampp\Implementations\DestroyImplementationController::class);
});

Route::get('/', OverviewController::class)->name('overview');
Route::get('status-board', StatusBoardController::class)->name('status-board');
Route::get('agents-board', AgentsBoardController::class)->name('agents-board');

Route::prefix('tickets')->name('tickets.')->group(function (){
    Route::get('/', IndexTicketController::class)->name('index');
    Route::get('create', CreateTicketController::class)->name('create');
    Route::post('/', StoreTicketController::class)->name('store');
    Route::get('{ticket}', ShowTicketController::class)->name('show');
    Route::patch('{ticket}', UpdateTicketController::class)->name('update');
    Route::patch('{ticket}/move', MoveTicketController::class)->name('move');
    Route::post('{ticket}/reply', StoreTicketReplyController::class)->name('reply');
});

Route::prefix('ai-skill')->name('ai-skill.')->group(function (){
    Route::get('/', IndexAiSkillController::class)->name('index');
    Route::patch('/', UpdateAiSkillController::class)->name('update');
    Route::post('trigger-update', TriggerUpdateAiSkillController::class)->name('trigger-update');
});

Route::prefix('corrections')->name('corrections.')->group(function (){
    Route::get('export', ExportCorrectionController::class)->name('export');
    Route::patch('{log}/ignore', ToggleIgnoreCorrectionController::class)->name('ignore');
});
