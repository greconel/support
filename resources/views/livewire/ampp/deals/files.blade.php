<div class="card">
    <div class="card-header fw-bolder fs-4">
        {{ __('Files') }}
    </div>

    <div class="card-body">
        <table class="mb-4 w-100">
            <tbody>
                @foreach($deal->getMedia('files') as $file)
                    <tr>
                        <td class="pb-2" style="max-width: 25px; min-width: 20px">
                            @if(str_starts_with($file->mime_type, 'image'))
                                <i class="far fa-image"></i>
                            @elseif(str_contains($file->mime_type, 'pdf'))
                                <i class="far fa-file-pdf"></i>
                            @elseif(str_contains($file->mime_type, 'document'))
                                <i class="far fa-file-word"></i>
                            @elseif(str_contains($file->mime_type, 'octet-stream'))
                                <i class="far fa-file-excel"></i>
                            @elseif(str_contains($file->mime_type, 'presentation'))
                                <i class="far fa-file-powerpoint"></i>
                            @else
                                <i class="far fa-file-alt"></i>
                            @endif
                        </td>

                        <td class="pb-2">
                            <a href="#" x-data @click.prevent="$wire.emit('openMediaPreviewModal', {{ $file->id }})">
                                {{ $file->name }}
                            </a>
                        </td>

                        <td class="d-flex justify-content-end">
                            <div class="dropdown">
                                <a href="#" class="text-decoration-none link-secondary ps-4" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li>
                                        <button class="dropdown-item" wire:click="download('{{ $file->id }}')">{{ __('Download') }}</button>
                                    </li>

                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <button class="dropdown-item" wire:click="delete('{{ $file->id }}')">{{ __('Remove') }}</button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div
            wire:ignore
            x-data="{ pond: null }"
            x-init="
                this.pond = FilePond.create($refs.file, {
                    allowMultiple: true,
                    server: {
                        process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                            $wire.upload('files', file, load, error, progress);
                        },
                        revert: (uniqueFileId, load, error) => {
                            $wire.removeUpload('files', uniqueFileId, load);
                        }
                    },
                    onprocessfiles(){
                        $wire.call('save');
                        this.pond.removeFiles();
                    }
                });
            "
        >
            <input type="file" x-ref="file">
        </div>

        <x-forms.error-message for="file" />
    </div>
</div>
