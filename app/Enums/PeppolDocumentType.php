<?php

namespace App\Enums;

use App\Models\Invoice;

enum PeppolDocumentType: string
{
    case Invoice = 'invoice';
    case CreditNote = 'creditnote';

    /**
     * Determine the Peppol document type from an Invoice model
     */
    public static function fromInvoice(Invoice $invoice): self
    {
        $type = $invoice->type;

        // Handle both enum and string values
        if ($type instanceof InvoiceType) {
            return $type === InvoiceType::Credit ? self::CreditNote : self::Invoice;
        }

        return $type === InvoiceType::Credit->value ? self::CreditNote : self::Invoice;
    }

    public function label(): string
    {
        return match ($this) {
            self::Invoice => __('Invoice'),
            self::CreditNote => __('Credit Note'),
        };
    }

    /**
     * Get the Peppol API document type value
     */
    public function peppolValue(): string
    {
        return match ($this) {
            self::Invoice => 'invoice',
            self::CreditNote => 'creditNote',
        };
    }
}
