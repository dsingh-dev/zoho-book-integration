<?php

namespace App\Zoho\Client;

use App\Models\ZohoAccount;
use App\Zoho\Exceptions\ZohoException;
use Illuminate\Support\Facades\Http;

class TokenStore
{
    protected const AUTH_ENDPOINT = 'https://accounts.zoho.in';

    protected ?ZohoAccount $account;

    public function __construct()
    {
        $this->account = ZohoAccount::where('user_id', auth()->id())->first();
    }

    public function getRefreshToken(): ?string
    {
        if (! $this->account) {
            throw new ZohoException('No Zoho account connected.');
        }

        return $this->account->refresh_token;
    }

    public function isExpired(): bool
    {
        if (! $this->account) {
            return true;
        }

        return now()->gte($this->account->expires_at);
    }

    public function hasToken(): bool
    {
        return $this->account !== null;
    }

    public function getApiEndpoint(): string
    {
        return $this->account->api_domain;
    }

    public function getAuthEndpoint(): string
    {
        return self::AUTH_ENDPOINT;
    }

    public function getAccessToken(): string
    {
        if (! $this->account) {
            throw new ZohoException('No Zoho account connected.');
        }

        if ($this->isExpired()) {
            $this->refreshToken();
        }

        return $this->account->access_token;
    }

    protected function refreshToken(): void
    {
        $parameters = [
            'client_id' => config('app.zoho.client_id'),
            'client_secret' => config('app.zoho.client_secret'),
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->account->refresh_token,
        ];

        $response = Http::asForm()->post(
            $this->getAuthEndpoint().'/oauth/v2/token',
            $parameters
        );

        $data = $response->json();

        if ($response->failed() || isset($data['error'])) {
            throw new ZohoException('Failed to refresh token: '.($data['error'] ?? $response->status()));
        }

        $this->account->access_token = $data['access_token'];
        $this->account->api_domain = $data['api_domain'];
        $this->account->expires_at = now()->addSeconds($data['expires_in']);
        $this->account->save();
    }
}
