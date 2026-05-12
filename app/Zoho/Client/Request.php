<?php

namespace App\Zoho\Client;

use App\Zoho\Exceptions\ZohoException;
use Illuminate\Support\Facades\Http;

class Request
{
    public function __construct(protected TokenStore $token_store) {}

    public function send(string $method, string $endpoint, array $options = []): mixed
    {
        $token = $this->token_store->getAccessToken();

        $url = $this->token_store->getApiEndpoint().'/books/v3/'.$endpoint;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken '.$token,
                'Content-Type' => 'application/json',
            ])->send(
                $method,
                $url,
                array_merge_recursive($options, [
                    'query' => ['organization_id' => config('app.zoho.organization_id')],
                ])
            );
        } catch (ZohoException $e) {
            throw new ZohoException('Zoho API request failed: '.$e->getMessage());
        }

        return $response->json();
    }
}
