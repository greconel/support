@props([
    'id',
    'actions',
    'cancelActions',
    'title' => __('Wait a moment!')
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true" {{ $attributes->except('class') }}>
    <div {{ $attributes->only('class')->class(['modal-dialog modal-dialog-centered']) }}>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fw-bolder">
                    {{ $title }}
                </h4>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{ $content }}
            </div>

            <div class="modal-footer d-flex justify-content-between">
                @isset($cancelActions)
                    {{ $cancelActions }}
                @else
                    <button type="button" class="btn btn-gray-500 text-white" data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                @endisset

                <div>
                    {{ $actions }}
                </div>
            </div>
        </div>
    </div>
</div>
