<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Icd11Controller extends defaultController
{

    public function __construct()
    {   
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }

    public function show(Request $request){
        return view('hisdb.icd11.icd11');
    }

    private function getWhoToken()
    {
        $cached = Cache::get('who_icd11_access_token');

        if ($cached) {
            return $cached;
        }

        $response = Http::asForm()
            ->withBasicAuth(
                config('services.who_icd.client_id'),
                config('services.who_icd.client_secret')
            )
            ->post(config('services.who_icd.token_url'), [
                'grant_type' => 'client_credentials',
                'scope' => 'icdapi_access',
            ]);

        $response->throw();

        $data = $response->json();

        if (empty($data['access_token'])) {
            throw new \RuntimeException('WHO token missing.');
        }

        $expiresIn = max(60, (int) ($data['expires_in'] ?? 3600) - 60);

        Cache::put(
            'who_icd11_access_token',
            $data['access_token'],
            now()->addSeconds($expiresIn)
        );

        return $data['access_token'];
    }

    public function token()
    {
        return response()->json([
            'token' => $this->getWhoToken(),
        ]);
    }
}