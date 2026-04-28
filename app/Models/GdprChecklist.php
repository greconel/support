<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GdprChecklist extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'step_1' => 'collection',
        'step_2' => 'collection',
        'step_3' => 'collection',
        'step_4' => 'collection',
        'step_5' => 'collection',
        'step_6' => 'collection',
        'step_7' => 'collection',
        'step_8' => 'collection',
        'step_9' => 'collection',
        'step_10' => 'collection',
    ];
}
