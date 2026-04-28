<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Push extends Component
{
    public function render()
    {
        return function($componentData){
            return <<<BLADE
                @push("{$componentData['attributes']->get('name')}")
                    {$componentData['slot']}
                @endpush
            BLADE;
        };
    }
}
