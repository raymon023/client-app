<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait Token
{

    public function setAcessToken($user, $service)
    {

        $response =  Http::withHeaders([
            'Accept' => 'aplication/json'
        ])->post('http://passport.test/oauth/token', [

            'grant_type' => 'password',
            'client_id' => config('services.api-passport.client_id'),
            'client_secret' => config('services.api-passport.client_secret'),
            'username' => request('email'),
            'password' =>  request('password'),
            'scopes' => [
                'create_post',
                'read_post',
                ' update_post',
                ' delete_post'
            ]
        ]);
        $access_token = $response->json();

        $user->AccessToken()->create([
            'service_id' => $service['data']['id'],
            'access_token' => $access_token['access_token'],
            'refresh_token' => $access_token['refresh_token'],
            'expires_at' => now()->addSecond($access_token['expires_in'])
        ]);
    }
}
