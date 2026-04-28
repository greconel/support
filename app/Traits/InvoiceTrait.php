<?php

namespace App\Traits;

use App\Models\User;
use Spatie\Browsershot\Browsershot;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\InvalidBase64Data;

trait InvoiceTrait
{
    public function generateOgm(): string
    {
        // OLD
        /*$customCode = $this->client_id . $this->custom_created_at->format('my') . str_pad($this->number, 2, "0", STR_PAD_LEFT);
        $number = str_pad(substr($customCode, 0, 10), 10, 0);

        $rest = $number % 97;

        if ($rest === 0) {
            $rest = 97;
        }

        if ($rest < 10) {
            $rest = "0" . $rest;
        }

        $ogm = $number.$rest;*/


        // +++.../..../.....+++
        // 1. client_id => 3 digits (pad with 0, left, substring 3 last digits)
        // 2. custom_created_at (my) => 4 digits (month and year)
        // 3. invoice number (3 last digits)
        // 4. mod97 => 2 digits 

        // 1. client_id => 3 digits (pad with 0, left, substring 3 last digits)
        $clientId = str_pad(substr($this->client_id, -3), 3, '0', STR_PAD_LEFT);

        // 2. custom_created_at (my) => 4 digits (month and year)
        $date = $this->custom_created_at->format('my'); // e.g. 0525 for May 2025

        // 3. invoice number (3 last digits)
        $invoiceNumber = str_pad(substr($this->number, -3), 3, '0', STR_PAD_LEFT);

        // Concatenate for base number (10 digits)
        $base = $clientId . $date . $invoiceNumber;
        
        // 4. mod97 => 2 digits
        $mod = intval($base) % 97;
        if ($mod === 0) {
            $mod = 97;
        }
        $mod = str_pad($mod, 2, '0', STR_PAD_LEFT);

        // Final OGM number
        $ogm = $base . $mod;

        return "+++".substr($ogm,0,3)."/".substr($ogm,3,4)."/".substr($ogm,7,5)."+++";
    }

    /**
     * @param ?array  $lines  use custom lines to generate on the PDF, defaults to quotation billing lines
     * @param  bool  $base64  return base64 value of the PDF
     * @param  bool  $reminder
     * @return string|void
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws InvalidBase64Data
     */
    public function generatePdf(?array $lines = null, bool $base64 = false, bool $reminder = false)
    {
        $html = view('ampp.invoices.pdf', [
            'invoice' => $this,
            'lines' => $lines ?? $this->billingLines->sortBy('order')->toArray(),
            'reminder' => $reminder
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
