@if($invoice->sent_to_clearfacts_at)
    <i class="fas fa-check text-success"></i>
@elseif($invoice->is_disabled_for_clearfacts)
    <i class="fas fa-minus text-muted"></i>
@else
    <i class="fas fa-times text-warning"></i>
@endif
