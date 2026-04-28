<div
    x-data
    @click.prevent="window.location.href = '{{ action(\App\Http\Controllers\Ampp\Deals\ShowDealController::class, $deal) }}'"
    class="board-column-item"
    wire:sortable-group.item="{{ $deal->id }}"
>
    <div class="position-relative">
        <p @class(['fw-bolder', 'mb-0' => $deal->client_id])>
            {{ $deal->name }}
        </p>

        @if($deal->client_id)
            <p @class(['mb-0' => $deal->quotation_id])>
                <span>{{ $deal->client->full_name }}</span>
                @if($deal->client->company)
                    <span>- {{ $deal->client->company }}</span>
                @endif
            </p>
        @endif

        @if($deal->quotation_id)
            <p class="text-muted">
                {{ __('Has a quotation [:status]', ['status' => $deal->quotation->status->label()]) }}
            </p>
        @endif

        @if($deal->expected_revenue)
            <p>
                € {{ $deal->expected_revenue_formatted }}
            </p>
        @endif

        @if($deal->todos->count() > 0)
            <p class="mb-1">
                <i class="fas fa-check-double text-success"></i>
                {{ $deal->todos()->finished()->count() }}/{{ $deal->todos->count() }}
            </p>
        @endif

        @if($deal->expected_start_date)
            <p @class(['mb-1', 'text-muted' => $deal->expected_start_date->isFuture(), 'text-red-300' => $deal->expected_start_date->isPast()])>
                <i class="fas fa-traffic-light text-blue-700"></i>
                {{ $deal->expected_start_date->format('d/m/Y') }}
            </p>
        @endif

        @if($deal->due_date && $deal->due_date->isFuture())
            <p class="text-muted mb-0">
                <i class="fas fa-bell text-yellow-600"></i>
                {{ $deal->due_date->format('d/m/Y H:i') }}
            </p>
        @endif

        @if($deal->chance_of_success)
            <div @class(['position-absolute bottom-0 end-0', 'text-success' => $deal->chance_of_success > 50, 'text-danger' => $deal->chance_of_success <= 50])>
                {{ $deal->chance_of_success }} %
            </div>
        @endif
    </div>
</div>
