@php
    /** @var \App\Models\Invoice $invoice */
@endphp

@component('mail::message')
# {{ __('We are done uploading your invoices to Clearfacts.') }}

{{ __('Below an overview of the invoices you selected to upload. If any of the invoices failed to upload, try to upload them manually.') }} <br> <br>

@component('mail::table')
| {{ __('Number') }}     | {{ __('Type') }}              | {{ __('Status') }}                                                                                                                                         |                                                                                                              |
| :--------------------- | :---------------------------- | :--------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------ |
@foreach($invoices as $invoice)
| {{ $invoice->number }} | {{ $invoice->type->label() }} | @if($invoice->sent_to_clearfacts_at) <span style="color: green">{{ __('success') }}</span> @else <span style="color: red">{{ __('failed') }}</span> @endif | [{{ __('open') }}]({{ action(\App\Http\Controllers\Ampp\Invoices\ShowInvoiceController::class, $invoice) }}) |
@endforeach
@endcomponent

{{ __('Thank you') }}, <br>
{{ config('app.name') }}
@endcomponent
