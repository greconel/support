<?php

namespace App\Http\Controllers\Media;

use App\Traits\MediaTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ShowMediaController
{
    use MediaTrait;

    public function __invoke(Media $media)
    {
        return $this->displayMedia($media);
    }
}
