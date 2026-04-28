<div class="card">
    <div class="card-header fw-bolder fs-4">
        {{ __('activityLogs.latest') }}
    </div>

    <div class="card-body">
        <livewire:ampp.billing.timeline :model="$quotation" />
    </div>
</div>
