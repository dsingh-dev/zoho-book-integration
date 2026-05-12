<?php

namespace App\Services;

use App\Models\ZohoAccount;

class ZohoService
{
    /**
     * @param array<string, string> $response
     */
    public function saveAccessToken(array $response): void {
        ZohoAccount::updateOrCreate([
            'user_id' => auth()->id(),
        ], [
            'access_token' => $response['access_token'],
            'refresh_token' => $response['refresh_token'],
            'scope' => $response['scope'],
            'api_domain' => $response['api_domain'],
            'token_type' => $response['token_type'],
            'expires_at' => now()->addSeconds($response['expires_in']),
        ]);
    }
}