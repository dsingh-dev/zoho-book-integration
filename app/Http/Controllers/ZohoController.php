<?php

namespace App\Http\Controllers;

use App\Models\ZohoAccount;
use App\Services\ZohoService;
use App\Zoho\Client\OAuth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZohoController extends Controller
{
    public function __construct(private OAuth $oauth, private ZohoService $zohoService) {}

    public function connect() {
        $authorizationUrl = $this->oauth->getAuthorizationUrl();

       return redirect()->away($authorizationUrl);
    }

    public function callback(Request $request) {
        $code = $request->input('code');

        try {
            $response = $this->oauth->getAccessToken($code);
            
            if(!isset($response['error'])) {
                $this->zohoService->saveAccessToken($response);
            }
            
            return redirect()->route('dashboard');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
