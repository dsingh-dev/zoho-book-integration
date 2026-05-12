<?php

namespace App\Zoho\Client;

class ZohoAPI
{
    protected Request $request;

    public function __construct(private TokenStore $tokenStore)
    {
        $this->request = new Request($this->tokenStore);
    }

    public function get(
        string $endpoint,
        array $query = []
    ) {
        return $this->request->send('GET', $endpoint, ['query' => $query]);
    }
}
