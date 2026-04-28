<?php

namespace App\Http\Requests\Ampp\ProjectReports;

use Illuminate\Foundation\Http\FormRequest;

class ShowDetailedTimeReportRequest extends FormRequest
{
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->count() > 0) {
                session()->flash('error_details', __('Something went wrong!'));
            }
        });
    }

    public function rules(): array
    {
        return [
            'from' => ['required', 'date_format:Y-m-d'],
            'till' => ['required', 'date_format:Y-m-d'],
            'type' => ['required', 'in:project,client'],
            'id' => ['required', 'int'],
        ];
    }
}
