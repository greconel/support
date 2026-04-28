<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Exception;

class RecommandPeppolClient
{
    private Client $client;
    private string $apiKey;
    private string $apiSecret;
    private string $baseUrl;

    public function __construct()
    {
        $apiKey = config('services.recommand.api_key');
        $apiSecret = config('services.recommand.api_secret');
        $baseUrl = config('services.recommand.base_url', 'https://peppol.recommand.eu');

        if (!$apiKey || !$apiSecret) {
            throw new Exception('Recommand API credentials not configured');
        }

        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->baseUrl = $baseUrl;

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'auth' => [$this->apiKey, $this->apiSecret],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'timeout' => 30,
            'connect_timeout' => 10,
        ]);
    }

    private function parseResponse($response): array
    {
        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response from Recommand API: ' . json_last_error_msg());
        }

        return $data;
    }

    public function sendDocument(string $companyId, array $documentData): array
    {
        try {
            $response = $this->client->post("/api/peppol/{$companyId}/sendDocument", [
                'json' => $documentData
            ]);

            return $this->parseResponse($response);
        } catch (RequestException $e) {
            Log::error('Failed to send document via Recommand Peppol', [
                'company_id' => $companyId,
                'error' => $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    public function getDocuments(string $teamId, array $filters = []): array
    {
        try {
            $queryParams = http_build_query($filters);
            $url = "/api/peppol/{$teamId}/documents" . ($queryParams ? "?{$queryParams}" : '');

            $response = $this->client->get($url);

            return $this->parseResponse($response);
        } catch (RequestException $e) {
            Log::error('Failed to get documents from Recommand Peppol', [
                'team_id' => $teamId,
                'filters' => $filters,
                'error' => $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    public function getDocument(string $teamId, string $documentId): array
    {
        try {
            $response = $this->client->get("/api/peppol/{$teamId}/documents/{$documentId}");

            return $this->parseResponse($response);
        } catch (RequestException $e) {
            Log::error('Failed to get document from Recommand Peppol', [
                'team_id' => $teamId,
                'document_id' => $documentId,
                'error' => $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    public function getCompanies(string $teamId): array
    {
        try {
            $response = $this->client->get("/api/peppol/{$teamId}/companies");

            return $this->parseResponse($response);
        } catch (RequestException $e) {
            Log::error('Failed to get companies from Recommand Peppol', [
                'team_id' => $teamId,
                'error' => $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    public function createCompany(string $teamId, array $companyData): array
    {
        try {
            $response = $this->client->post("/api/peppol/{$teamId}/companies", [
                'json' => $companyData
            ]);

            return $this->parseResponse($response);
        } catch (RequestException $e) {
            Log::error('Failed to create company in Recommand Peppol', [
                'team_id' => $teamId,
                'error' => $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    public function updateCompany(string $teamId, string $companyId, array $companyData): array
    {
        try {
            $response = $this->client->put("/api/peppol/{$teamId}/companies/{$companyId}", [
                'json' => $companyData
            ]);

            return $this->parseResponse($response);
        } catch (RequestException $e) {
            Log::error('Failed to update company in Recommand Peppol', [
                'team_id' => $teamId,
                'company_id' => $companyId,
                'error' => $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    public function verifyRecipient(string $peppolAddress): array
    {
        try {
            $response = $this->client->post('/api/peppol/verify', [
                'json' => ['peppolAddress' => $peppolAddress]
            ]);

            return $this->parseResponse($response);
        } catch (RequestException $e) {
            Log::error('Failed to verify recipient in Recommand Peppol', [
                'peppol_address' => $peppolAddress,
                'error' => $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    public function searchPeppolDirectory(string $query): array
    {
        try {
            $response = $this->client->post('/api/peppol/searchPeppolDirectory', [
                'json' => ['query' => $query]
            ]);

            return $this->parseResponse($response);
        } catch (RequestException $e) {
            Log::error('Failed to search Peppol directory via Recommand', [
                'query' => $query,
                'error' => $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    public function getWebhooks(string $teamId): array
    {
        try {
            $response = $this->client->get("/api/peppol/{$teamId}/webhooks");

            return $this->parseResponse($response);
        } catch (RequestException $e) {
            Log::error('Failed to get webhooks from Recommand Peppol', [
                'team_id' => $teamId,
                'error' => $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    public function createWebhook(string $teamId, array $webhookData): array
    {
        try {
            $response = $this->client->post("/api/peppol/{$teamId}/webhooks", [
                'json' => $webhookData
            ]);

            return $this->parseResponse($response);
        } catch (RequestException $e) {
            Log::error('Failed to create webhook in Recommand Peppol', [
                'team_id' => $teamId,
                'error' => $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    public function updateWebhook(string $teamId, string $webhookId, array $webhookData): array
    {
        try {
            $response = $this->client->put("/api/peppol/{$teamId}/webhooks/{$webhookId}", [
                'json' => $webhookData
            ]);

            return $this->parseResponse($response);
        } catch (RequestException $e) {
            Log::error('Failed to update webhook in Recommand Peppol', [
                'team_id' => $teamId,
                'webhook_id' => $webhookId,
                'error' => $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    public function deleteWebhook(string $teamId, string $webhookId): bool
    {
        try {
            $response = $this->client->delete("/api/peppol/{$teamId}/webhooks/{$webhookId}");

            return $response->getStatusCode() === 204;
        } catch (RequestException $e) {
            Log::error('Failed to delete webhook in Recommand Peppol', [
                'team_id' => $teamId,
                'webhook_id' => $webhookId,
                'error' => $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    public function verifyDocumentSupport(string $peppolAddress, string $documentType): array
    {
        try {
            $response = $this->client->post('/api/peppol/verifyDocumentSupport', [
                'json' => [
                    'peppolAddress' => $peppolAddress,
                    'documentType' => $documentType
                ]
            ]);

            return $this->parseResponse($response);
        } catch (RequestException $e) {
            Log::error('Failed to verify document support in Recommand Peppol', [
                'peppol_address' => $peppolAddress,
                'document_type' => $documentType,
                'error' => $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    public function getTeams(): array
    {
        try {
            $response = $this->client->get('/api/peppol/teams');

            return $this->parseResponse($response);
        } catch (RequestException $e) {
            Log::error('Failed to get teams from Recommand Peppol', [
                'error' => $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }
}