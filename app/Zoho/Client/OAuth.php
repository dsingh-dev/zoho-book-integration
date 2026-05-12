<?php

namespace App\Zoho\Client;

use App\Zoho\Exceptions\ZohoException;
use Illuminate\Support\Facades\Http;

class OAuth
{
    protected const AUTH_ENDPOINT = 'https://accounts.zoho.in';

    protected const scopes = [
        "ZohoBooks.invoices.READ",
        "ZohoBooks.bills.READ",
        "ZohoBooks.reports.READ",
    ];

    public function __construct() {}

    public function getAuthorizationUrl(): string
    {
        $parameters = http_build_query([
                'response_type' => 'code',
                'client_id' => config('app.zoho.client_id'),
                'scope' => implode(',', self::scopes),
                'redirect_uri' => config('app.zoho.redirect_uri'),
                'access_type' => 'offline',
                'prompt' => 'consent',
        ]);
        
        return self::AUTH_ENDPOINT . '/oauth/v2/auth?' . $parameters;
    }

    /**
     * @return array<string, string>
     */
    public function getAccessToken(string $code): array
    {
        $parameters = [
                'grant_type' => 'authorization_code',
                'client_id' => config('app.zoho.client_id'),
                'client_secret' => config('app.zoho.client_secret'),
                'redirect_uri' => config('app.zoho.redirect_uri'),
                'code' => $code,
        ];

        try {
            $response = Http::asForm()->post(self::AUTH_ENDPOINT . '/oauth/v2/token', $parameters)->json();
        } catch (ZohoException $e) {
            throw new ZohoException('OAuth failed: ' . $e->getMessage());
        }

        return $response;
    }
}