<?php

namespace App\Traits;

use App\Models\Quotation;
use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait MediaTrait
{
    public function downloadMedia(Media $media): BinaryFileResponse
    {
        abort_if(! $this->canInteractWithMedia($media), 403);

        return response()->download(
            $media->getPath(),
            $media->name,
            [
                'Content-Type' => $media->mime_type
            ]
        );
    }

    public function displayMedia(Media $media): BinaryFileResponse
    {
        abort_if(! $this->canInteractWithMedia($media), 403);

        return response()->file($media->getPath(), [
            'Content-Type' => $media->mime_type
        ]);
    }

    public function canInteractWithMedia(Media $media): bool
    {
        if ($media->getCustomProperty('permission')){
            return auth()->user()->can($media->getCustomProperty('permission'), $media->model);
        }

        return auth()->user()->can('files', $media->model);
    }
}
