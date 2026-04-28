<?php

namespace App\Actions\Clearfacts;

use App\Enums\ClearfactsInvoiceType;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class DownloadFromClearfactsAction
{
    public function execute(
        string $administrationId,
        ?string $afterCursor = null
    ): Response {
        $graphQlQuery = <<<GRAPHQL
            query AdministrationsWithPurchaseDocuments {
                administrations(companyNumber: \$adminId) {
                    companyNumber
                    purchaseDocuments(first:50, after: \$after, status: NEW) {
                    edges {
                        node {
                        documentNumber
                        documentDate
                        totalAmount
                        status
                        supplier {
                            name
                            vatNumber
                        }
                        attachments {
                            id
                            fileName
                            mimeType
                        }
                        }
                        cursor
                    }
                    pageInfo {
                        hasNextPage
                        endCursor
                    }
                    }
                }
                }
        GRAPHQL;

        $variables = [
            'adminId' => $administrationId,
            'after'   => $afterCursor,
        ];

        return Http::withToken(config('services.clearfacts.token'))
            ->post(config('services.clearfacts.url'), [
                'query'     => $graphQlQuery,
                'variables' => $variables,
            ]);
    }
}
