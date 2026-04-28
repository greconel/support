@php
    /** @var \App\Models\Expense $expense */
@endphp

@component('mail::message')
# {{ __('We are done uploading your expenses to Clearfacts.') }}

{{ __('Below an overview of the expenses you selected to upload. If any of the expenses failed to upload, try to upload them manually.') }} <br> <br>

@component('mail::table')
| {{ __('Number') }}     | {{ __('Supplier') }}                | {{ __('Status') }}                                                                                                                                  |                                                                                                              |
| :--------------------- | :---------------------------------- | :-------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------ |
@foreach($expenses as $expense)
| {{ $expense->number }} | {{ $expense->supplier->full_name }} | @if($expense->sent_to_clearfacts_at) <span style="color: green">{{ __('success') }}</span> @else <span style="color: red">{{ __('failed') }}</span> @endif | [{{ __('open') }}]({{ action(\App\Http\Controllers\Ampp\Expenses\ShowExpenseController::class, $expense) }}) |
@endforeach
@endcomponent

{{ __('Thank you') }}, <br>
{{ config('app.name') }}
@endcomponent
