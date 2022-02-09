<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function resolveAuthorization(){

        if( auth()->user()->AccessToken->expires_at <= now()){

            $response =  Http::withHeaders([
                'Accept' => 'aplication/json'
            ])->post('http://passport.test/oauth/token', [

                'grant_type' => 'refresh_token',
                'refresh_token' => auth()->user()->AccessToken->refresh_token,
                'client_id' => config('services.api-passport.client_id'),
                'client_secret' => config('services.api-passport.client_secret'),

            ]);
            $access_token = $response->json();

            auth()->user()->AccessToken->update([
                'access_token' => $access_token['access_token'],
                'refresh_token' => $access_token['refresh_token'],
                'expires_at' => now()->addSecond($access_token['expires_in'])
            ]);

        }
    }
}
