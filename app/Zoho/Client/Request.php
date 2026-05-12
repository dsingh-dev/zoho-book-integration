<?php

namespace App\Zoho\Client;

use Illuminate\Support\Facades\Http;
use App\Zoho\Exceptions\ZohoException;

class Request
{

    public function __construct(protected TokenStore $token_store) {}

    public function send(string $method, string $endpoint, array $options = []): mixed {
        $token = $this->token_store->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $token,
            'Content-Type' => 'application/json',
        ])->send(
            $method,
            $this->token_store->getAuthEndpoint() . $endpoint,
            array_merge_recursive($options, [
                'query' => ['organization_id' => config('app.zoho.organization_id')]
            ])
        );

        if ($response->failed() || isset($response['error'])) {
            throw new ZohoException('Zoho API request failed: ' . $response->status());
        }

        return $response->json();
    }
}