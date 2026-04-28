@php
    /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $attachment */
    $mime = $attachment->mime_type ?? '';
    $isImage = str_starts_with($mime, 'image/');
    $isPdf = $mime === 'application/pdf';
    $isWord = in_array($mime, [
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ], true);
    $isExcel = in_array($mime, [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ], true);

    $name = $attachment->name ?? 'attachment';
    $formattedSize = $attachment->human_readable_size ?? '';

    $downloadUrl = action(\App\Http\Controllers\Media\DownloadMediaController::class, $attachment);
    $viewUrl = action(\App\Http\Controllers\Media\ShowMediaController::class, $attachment);
@endphp

<div style="display: flex; flex-direction: column; gap: 0.25rem;">
    <div class="hd-flex hd-items-center hd-gap-2">
        @if($isImage)
            <svg style="width: 0.875rem; height: 0.875rem; color: var(--blue-500); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        @elseif($isPdf)
            <svg style="width: 0.875rem; height: 0.875rem; color: var(--red-500); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
        @elseif($isWord)
            <svg style="width: 0.875rem; height: 0.875rem; color: var(--blue-700); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        @elseif($isExcel)
            <svg style="width: 0.875rem; height: 0.875rem; color: var(--green-600); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        @else
            <svg style="width: 0.875rem; height: 0.875rem; color: var(--gray-400); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
            </svg>
        @endif

        <a href="{{ $downloadUrl }}"
           class="hd-text-link hd-xs"
           style="max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
           title="{{ $name }}">
            {{ $name }}
        </a>

        @if($formattedSize)
            <span class="hd-xs hd-subtle">{{ $formattedSize }}</span>
        @endif

        @if($isImage || $isPdf)
            <a href="{{ $viewUrl }}" target="_blank"
               style="color: var(--gray-500); flex-shrink: 0;"
               title="{{ __('View in browser') }}">
                <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        @endif
    </div>

    @if($isImage)
        <div style="margin-left: 1.25rem; margin-top: 0.25rem;">
            <a href="{{ $viewUrl }}" target="_blank">
                <img src="{{ $viewUrl }}"
                     alt="{{ $name }}"
                     style="max-width: 280px; max-height: 180px; border-radius: 0.5rem; border: 1px solid var(--gray-200); object-fit: contain;">
            </a>
        </div>
    @endif
</div>
