<?php

namespace App\Zoho\Client;

class ZohoAPI
{
    public function __construct(private TokenStore $tokenStore, private Request $request) {}

    public function get(
        string $endpoint,
        array $query = []
    ) {
        return $this->request->send('GET', $endpoint, ['query' => $query]);
    }

}