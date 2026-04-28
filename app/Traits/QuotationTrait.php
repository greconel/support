<?php

namespace App\Traits;

use Spatie\Browsershot\Browsershot;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\InvalidBase64Data;

trait QuotationTrait
{
    /**
     * @param ?array $lines use custom lines to generate on the PDF, defaults to quotation billing lines
     * @param bool $base64 return base64 value of the PDF
     * @return string|void
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws InvalidBase64Data
     */
    public function generatePdf(?array $lines = null, bool $base64 = false)
    {
        abort_if(! auth()->user()->can('view', $this), 403);

        $html = view('ampp.quotations.pdf', [
            'quotation' => $this,
            'lines' => $lines ?? $this->billingLines->sortBy('order')->toArray()
        ])->render();

        $pdf = Browsershot::html($html)
            ->showBrowserHeaderAndFooter()
            ->hideHeader()
            ->footerHtml(view('pdf.pageNumber')->render())
            ->margins(10, 10, 10, 10)
            ->base64pdf();

        if ($base64){
            return $pdf;
        }

        $this->clearMediaCollection('pdf');

        $this
            ->addMediaFromBase64($pdf, 'application/pdf')
            ->usingFileName($this->file_name)
            ->usingName($this->file_name)
            ->toMediaCollection('pdf', 'private')
        ;
    }
}
