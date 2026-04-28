<?php

namespace App\Models;

use App\Http\Controllers\Media\DownloadMediaController;
use App\Http\Controllers\Media\ShowMediaController;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Connection extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile();
    }

    public function getLogoAttribute()
    {
        if ($logo = $this->getFirstMedia('logo')){
            return action(DownloadMediaController::class, $logo);
        }
        return '';
    }

    public function getFileNameAttribute()
    {
        if ($logo = $this->getFirstMedia('logo')){
            return $logo->file_name;
        }
        return '';
    }

    //public function registerMediaConversions(Media $media = null): void
    //{
    //    $this->addMediaConversion('logo')
    //        ->width(100)
    //        ->height(100);
    //}
}
