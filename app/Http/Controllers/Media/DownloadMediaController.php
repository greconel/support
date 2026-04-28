<?php

namespace App\Http\Controllers\Media;

use App\Traits\MediaTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DownloadMediaController
{
    use MediaTrait;

    public function __invoke(Media $media)
    {
        return $this->downloadMedia($media);
    }
}
