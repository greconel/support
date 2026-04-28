<?php

namespace App\Services;

use App\Enums\InvoiceType;
use App\Models\Invoice;
use App\Models\BillingLines;

class InvoiceToPeppolMapper
{
    /**
     * The identifier mode to use for recipient addressing
     *
     * @var string 'vat' or 'alternative'
     */
    protected string $identifierMode;

    /**
     * Create a new InvoiceToPeppolMapper instance
     *
     * @param string|null $identifierMode The identifier mode ('vat' or 'alternative'), defaults to config value
     */
    public function __construct(?string $identifierMode = null)
    {
        $this->identifierMode = $identifierMode ?? config('services.recommand.identifier_mode', 'alternative');
    }

    /**
     * Map an Invoice model to Peppol document format
     */
    public function mapInvoiceToPeppolDocument(Invoice $invoice): array
    {
        $invoice->load(['client', 'billingLines']);

        if (!$invoice->client->validated_vat) {
            throw new \InvalidArgumentException('Client VAT number is required for Peppol documents');
        }

        $isCreditNote = $invoice->type === InvoiceType::Credit
            || $invoice->type === InvoiceType::Credit->value;

        $data = [
            'recipient' => $this->formatRecipientAddress($invoice->client->validated_vat, $this->identifierMode),
            'documentType' => $isCreditNote ? 'creditNote' : 'invoice',
            'document' => [
                'issueDate' => $invoice->custom_created_at->format('Y-m-d'),
                'dueDate' => $invoice->expiration_date->format('Y-m-d'),
                'note' => $invoice->notes ?: '',

                'seller' => $this->mapSellerInformation(),
                'buyer' => $this->mapBuyerInformation($invoice),
                'lines' => $this->mapInvoiceLines($invoice->billingLines, $isCreditNote),
                'paymentMeans' => $this->mapPaymentMeans($invoice),
                'totals' => $this->mapInvoiceTotals($invoice, $isCreditNote),
            ]
        ];

        if ($isCreditNote) {
            $data['document']['creditNoteNumber'] = $invoice->custom_name;
        } else {
            $data['document']['invoiceNumber'] = $invoice->custom_name;
        }

        if ($invoice->po_number) {
            // $data['document']['buyerReference'] = $invoice->po_number;
            $data['document']['purchaseOrderReference'] = $invoice->po_number;
        }

        return $data;
    }

    public function formatRecipientAddress(string $vatNumber, string $mode = 'vat'): string
    {
        // Map of country codes to their official Peppol EAS VAT scheme identifiers
        $vatSchemeMap = [
            'AT' => '9914', // Austria VAT number
            'BE' => '9925', // Belgium VAT number
            'BG' => '9926', // Bulgaria VAT number
            'CY' => '9931', // Cyprus VAT number
            'CZ' => '9929', // Czech Republic VAT number
            'DE' => '9930', // Germany VAT number
            'DK' => '9915', // Denmark VAT number
            'EE' => '9928', // Estonia VAT number
            'ES' => '9920', // Spain VAT number
            'FI' => '9917', // Finland VAT number
            'FR' => '9918', // France VAT number
            'GB' => '9932', // United Kingdom VAT number
            'GR' => '9933', // Greece VAT number
            'HR' => '9934', // Croatia VAT number
            'HU' => '9910', // Hungary VAT number
            'IE' => '9935', // Ireland VAT number
            'IT' => '9921', // Italy VAT number
            'LT' => '9936', // Lithuania VAT number
            'LU' => '9937', // Luxembourg VAT number
            'LV' => '9938', // Latvia VAT number
            'MT' => '9939', // Malta VAT number
            'NL' => '9944', // Netherlands VAT number
            'PL' => '9912', // Poland VAT number
            'PT' => '9911', // Portugal VAT number
            'RO' => '9913', // Romania VAT number
            'SE' => '9919', // Sweden VAT number
            'SI' => '9916', // Slovenia VAT number
            'SK' => '9940', // Slovakia VAT number
        ];

        // Map of country codes to their alternative national identifier schemes
        $alternativeSchemeMap = [
            'AT' => ['scheme' => '9914', 'strip_country' => false], // Austria: Use VAT as fallback
            'BE' => ['scheme' => '0208', 'strip_country' => true],  // Belgium: Ondernemingsnummer/Numero d'entreprise
            'BG' => ['scheme' => '9926', 'strip_country' => false], // Bulgaria: Use VAT as fallback
            'CY' => ['scheme' => '9931', 'strip_country' => false], // Cyprus: Use VAT as fallback
            'CZ' => ['scheme' => '9929', 'strip_country' => false], // Czech Republic: Use VAT as fallback
            'DE' => ['scheme' => '0088', 'strip_country' => true],  // Germany: GLN (Global Location Number) preferred
            'DK' => ['scheme' => '0096', 'strip_country' => true], // Denmark: Use VAT as fallback
            'EE' => ['scheme' => '9928', 'strip_country' => false], // Estonia: Use VAT as fallback
            'ES' => ['scheme' => '9920', 'strip_country' => false], // Spain: Use VAT as fallback
            'FI' => ['scheme' => '9917', 'strip_country' => false], // Finland: Use VAT as fallback
            'FR' => ['scheme' => '9918', 'strip_country' => false], // France: Use VAT as fallback
            'GB' => ['scheme' => '9932', 'strip_country' => false], // United Kingdom: Use VAT as fallback
            'GR' => ['scheme' => '9933', 'strip_country' => false], // Greece: Use VAT as fallback
            'HR' => ['scheme' => '9934', 'strip_country' => false], // Croatia: Use VAT as fallback
            'HU' => ['scheme' => '9910', 'strip_country' => false], // Hungary: Use VAT as fallback
            'IE' => ['scheme' => '9935', 'strip_country' => false], // Ireland: Use VAT as fallback
            'IT' => ['scheme' => '9921', 'strip_country' => false], // Italy: Use VAT as fallback
            'LT' => ['scheme' => '9936', 'strip_country' => false], // Lithuania: Use VAT as fallback
            'LU' => ['scheme' => '9937', 'strip_country' => false], // Luxembourg: Use VAT as fallback
            'LV' => ['scheme' => '9938', 'strip_country' => false], // Latvia: Use VAT as fallback
            'MT' => ['scheme' => '9939', 'strip_country' => false], // Malta: Use VAT as fallback
            'NL' => ['scheme' => '0106', 'strip_country' => true],  // Netherlands: KvK (Chamber of Commerce) number
            'PL' => ['scheme' => '9912', 'strip_country' => false], // Poland: Use VAT as fallback
            'PT' => ['scheme' => '9911', 'strip_country' => false], // Portugal: Use VAT as fallback
            'RO' => ['scheme' => '9913', 'strip_country' => false], // Romania: Use VAT as fallback
            'SE' => ['scheme' => '9919', 'strip_country' => false], // Sweden: Use VAT as fallback
            'SI' => ['scheme' => '9916', 'strip_country' => false], // Slovenia: Use VAT as fallback
            'SK' => ['scheme' => '9940', 'strip_country' => false], // Slovakia: Use VAT as fallback
        ];

        $countryCode = strtoupper(substr($vatNumber, 0, 2));

        if (!preg_match('/^[A-Z]{2}/', $vatNumber)) {
            return $vatNumber; // Return as-is if no country code detected
        }

        if ($mode === 'alternative' && isset($alternativeSchemeMap[$countryCode])) {
            $config = $alternativeSchemeMap[$countryCode];
            $schemeId = $config['scheme'];

            $identifier = $config['strip_country'] ? substr($vatNumber, 2) : $vatNumber;

            return $schemeId . ':' . $identifier;
        } elseif ($mode === 'vat' && isset($vatSchemeMap[$countryCode])) {
            $schemeId = $vatSchemeMap[$countryCode];
            return $schemeId . ':' . $vatNumber;
        }

        return $vatNumber;
    }

    /**
     * Map seller information from configuration
     */
    protected function mapSellerInformation(): array
    {
        $vatNumber = config('services.recommand.seller.vat_number');

        $seller = [
            'name' => config('services.recommand.seller.name'),
            'street' => config('services.recommand.seller.street'),
            'city' => config('services.recommand.seller.city'),
            'postalZone' => config('services.recommand.seller.postal_zone'),
            'country' => config('services.recommand.seller.country'),
            'vatNumber' => $vatNumber,
        ];

        $countryCode = strtoupper(substr($vatNumber, 0, 2));
        if ($countryCode === 'BE') {
            $seller['enterpriseNumber'] = substr($vatNumber, 2);
        }

        return $seller;
    }

    protected function mapBuyerInformation(Invoice $invoice): array
    {
        $client = $invoice->client;

        return [
            'vatNumber' => $client->validated_vat,
            'name' => $client->company ?: $client->name,
            'street' => trim($client->street . ' ' . $client->number),
            'city' => $client->city,
            'postalZone' => $client->postal,
            'country' => $client->country_code ?: 'BE',
        ];
    }

    protected function mapInvoiceLines($lines, bool $isCreditNote = false): array
    {
        return $lines->map(function (BillingLines $line) use ($isCreditNote) {
            $price = $isCreditNote ? abs($line->price) : $line->price;
            $subtotal = $isCreditNote ? abs($line->subtotal) : $line->subtotal;
            $quantity = $isCreditNote ? abs($line->amount ?: 1) : ($line->amount ?: 1);

            return [
                'name' => $line->text ?: '',
                'description' => $line->description,
                'quantity' => (string) $quantity,
                'unitCode' => 'C62', // Default unit code (one/piece)
                'netPriceAmount' => "" . $price,
                'netAmount' => $subtotal,
                'vat' => [
                    'category' => 'S', // Standard rate
                    'percentage' => $line->vat
                ]
            ];
        })->toArray();
    }

    protected function mapPaymentMeans(Invoice $invoice): array
    {
        $paymentMeans = [
            'paymentMethod' => 'credit_transfer',
            'reference' => $invoice->ogm ?? "",
        ];

        $iban = config('services.recommand.seller.iban');
        if ($iban) {
            $paymentMeans['iban'] = $iban;
        }

        return [$paymentMeans];
    }

    protected function mapInvoiceTotals(Invoice $invoice, bool $isCreditNote = false): array
    {
        $taxExclusive = $isCreditNote ? abs($invoice->amount) : $invoice->amount;
        $taxInclusive = $isCreditNote ? abs($invoice->amount_with_vat) : $invoice->amount_with_vat;

        return [
            'taxExclusiveAmount' => $taxExclusive,
            'taxInclusiveAmount' => $taxInclusive,
            'payableAmount' => $taxInclusive,
        ];
    }

    public function addPdfAttachment(array &$peppolData, string $pdfBase64, string $filename): void
    {
        if ($pdfBase64 && $pdfBase64 !== '') {
            $peppolData['document']['attachments'][] = [
                'id' => 'invoice_' . basename($filename, '.pdf'),
                'filename' => $filename,
                'embeddedDocument' => $pdfBase64,
            ];
        }
    }

    public function validatePeppolData(array $peppolData): array
    {
        $errors = [];

        if (empty($peppolData['recipient'])) {
            $errors[] = 'recipient → Required field is missing';
        }

        if (empty($peppolData['documentType'])) {
            $errors[] = 'documentType → Required field is missing';
        }

        if (empty($peppolData['document'])) {
            $errors[] = 'document → Required field is missing';
            return $errors;
        }

        $document = $peppolData['document'];
        $isCreditNote = ($peppolData['documentType'] ?? '') === 'creditNote';

        if ($isCreditNote) {
            if (empty($document['creditNoteNumber'])) {
                $errors[] = 'document.creditNoteNumber → Credit note number is required';
            }
        } else {
            if (empty($document['invoiceNumber'])) {
                $errors[] = 'document.invoiceNumber → Invoice number is required';
            }
        }

        if (empty($document['buyer'])) {
            $errors[] = 'document.buyer → Buyer information is required';
        } else {
            $buyer = $document['buyer'];

            if (empty($buyer['name'])) {
                $errors[] = 'document.buyer.name → Buyer name is required';
            }

            if (empty($buyer['street'])) {
                $errors[] = 'document.buyer.street → Buyer street address is required';
            }

            if (empty($buyer['city'])) {
                $errors[] = 'document.buyer.city → Buyer city is required';
            }

            if (empty($buyer['postalZone'])) {
                $errors[] = 'document.buyer.postalZone → Buyer postal code is required';
            }

            if (empty($buyer['country'])) {
                $errors[] = 'document.buyer.country → Buyer country code is required';
            } elseif (strlen($buyer['country']) !== 2) {
                $errors[] = 'document.buyer.country → Must be 2-character ISO code (e.g., BE, NL, DE)';
            }
        }

        if (isset($document['seller'])) {
            $seller = $document['seller'];

            if (empty($seller['name'])) {
                $errors[] = 'document.seller.name → Seller name is required';
            }

            if (empty($seller['street'])) {
                $errors[] = 'document.seller.street → Seller street address is required';
            }

            if (empty($seller['city'])) {
                $errors[] = 'document.seller.city → Seller city is required';
            }

            if (empty($seller['postalZone'])) {
                $errors[] = 'document.seller.postalZone → Seller postal code is required';
            }

            if (empty($seller['country'])) {
                $errors[] = 'document.seller.country → Seller country code is required';
            } elseif (strlen($seller['country']) !== 2) {
                $errors[] = 'document.seller.country → Must be 2-character ISO code (e.g., BE, NL, DE)';
            }
        }

        if (empty($document['lines']) || !is_array($document['lines'])) {
            $errors[] = 'document.lines → At least one invoice line is required';
        } else {
            foreach ($document['lines'] as $index => $line) {
                $lineNum = $index + 1;

                if (!isset($line['netPriceAmount'])) {
                    $errors[] = "document.lines[$lineNum].netPriceAmount → Net price is required";
                }

                if (empty($line['vat'])) {
                    $errors[] = "document.lines[$lineNum].vat → VAT information is required";
                } else {
                    if (!isset($line['vat']['category'])) {
                        $errors[] = "document.lines[$lineNum].vat.category → VAT category is required (e.g., S, AE, E)";
                    }

                    if (!isset($line['vat']['percentage'])) {
                        $errors[] = "document.lines[$lineNum].vat.percentage → VAT percentage is required";
                    }
                }
            }
        }

        if (isset($document['totals'])) {
            $totals = $document['totals'];

            if (!isset($totals['taxExclusiveAmount'])) {
                $errors[] = 'document.totals.taxExclusiveAmount → Amount excluding VAT is required';
            }

            if (!isset($totals['taxInclusiveAmount'])) {
                $errors[] = 'document.totals.taxInclusiveAmount → Amount including VAT is required';
            }
        }

        if (isset($document['paymentMeans']) && is_array($document['paymentMeans'])) {
            foreach ($document['paymentMeans'] as $index => $paymentMean) {
                $pmNum = $index + 1;

                if (empty($paymentMean['iban'])) {
                    $errors[] = "document.paymentMeans[$pmNum].iban → Bank account number (IBAN) is required";
                }
            }
        }

        if (isset($document['vat']['subtotals']) && is_array($document['vat']['subtotals'])) {
            foreach ($document['vat']['subtotals'] as $index => $subtotal) {
                $subNum = $index + 1;

                if (!isset($subtotal['category'])) {
                    $errors[] = "document.vat.subtotals[$subNum].category → VAT category is required";
                }

                if (!isset($subtotal['percentage'])) {
                    $errors[] = "document.vat.subtotals[$subNum].percentage → VAT percentage is required";
                }

                if (!isset($subtotal['taxableAmount'])) {
                    $errors[] = "document.vat.subtotals[$subNum].taxableAmount → Taxable amount is required";
                }

                if (!isset($subtotal['vatAmount'])) {
                    $errors[] = "document.vat.subtotals[$subNum].vatAmount → VAT amount is required";
                }
            }

            if (!isset($document['vat']['totalVatAmount'])) {
                $errors[] = 'document.vat.totalVatAmount → Total VAT amount is required';
            }
        }

        if (isset($document['attachments']) && is_array($document['attachments'])) {
            foreach ($document['attachments'] as $index => $attachment) {
                $attNum = $index + 1;

                if (empty($attachment['id'])) {
                    $errors[] = "document.attachments[$attNum].id → Attachment ID is required";
                }

                if (empty($attachment['filename'])) {
                    $errors[] = "document.attachments[$attNum].filename → Attachment filename is required";
                }
            }
        }

        return $errors;
    }
}
