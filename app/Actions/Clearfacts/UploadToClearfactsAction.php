<?php

namespace App\Actions\Clearfacts;

use App\Enums\ClearfactsInvoiceType;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class UploadToClearfactsAction
{
    public function execute(
        ClearfactsInvoiceType $clearfactsInvoiceType,
        string $pdfPath,
        string $filename,
        string $clearfactsComment = null
    ): Response
    {
        $graphQlQuery = <<<GRAPHQL
        mutation upload(\$vatnumber: String!, \$filename: String!, \$invoicetype: InvoiceTypeArgument!, \$comment: String!) {
            uploadFile(vatnumber: \$vatnumber, filename: \$filename, invoicetype: \$invoicetype, comment: \$comment) {
                uuid,
                amountOfPages,
            }
        }
        GRAPHQL;

        $vat = config('services.clearfacts.vat');

        $graphQlVariables = <<<GRAPHQL
        {
            "vatnumber": "$vat",
            "filename": "$filename",
            "invoicetype": "$clearfactsInvoiceType->value",
            "comment": "$clearfactsComment"
        }
        GRAPHQL;

        return Http::attach('file', file_get_contents($pdfPath), $filename)
            ->withToken(config('services.clearfacts.token'))
            ->post(config('services.clearfacts.url'), [
                'query' => $graphQlQuery,
                'variables' => $graphQlVariables,
            ])
        ;
    }
}
