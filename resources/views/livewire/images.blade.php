<div>
    <p class="mb-2">{{ __('Images') }}</p>

    <table class="table table-bordered table-striped border rounded-3">
        <tbody>
            @foreach($model->getMedia('images') as $m)
                <tr>
                    <td>
                        <img src="{{ action(\App\Http\Controllers\Media\ShowMediaController::class, $m) }}" alt="image" width="150" height="150" class="d-block mx-auto" style="object-fit: contain">
                    </td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center center">
                            <button type="button" class="btn btn-sm btn-danger" wire:click="deleteExisting({{ $m->id }})">{{ __('Delete') }}</button>
                        </div>
                    </td>
                </tr>
            @endforeach

            @foreach($images as $index => $i)
                <tr>
                    <td>
                        <input type="hidden" name="images[{{ $index }}][path]" value="{{ $i->getFileName() }}">
                        <input type="hidden" name="images[{{ $index }}][name]" value="{{ $i->getClientOriginalName() }}">
                        <img src="{{ $i->temporaryUrl() }}" alt="image" width="150" height="150" class="d-block mx-auto" style="object-fit: contain">
                    </td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center center">
                            <button type="button" class="btn btn-sm btn-danger" wire:click="delete({{ $index }})">{{ __('Delete') }}</button>
                        </div>
                    </td>
                </tr>
            @endforeach

            <tr>
                <td>
                    <input type="file" wire:model="image" accept="image/*" id="upload_{{ collect($images)->count() }}">
                    @error('image')
                    <div class="small text-danger">{{ $message }}</div>
                    @enderror
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-success" wire:click="add">{{ __('Add image') }}</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
