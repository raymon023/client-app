<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{

    public function store()
    {
        $this->resolveAuthorization();

        $response = Http::withHeaders([
            'Accept'    => 'application/json',
            'Authorization' => 'Bearer ' . auth()->user()->AccessToken->access_token
        ])->post('http://passport.test/v1/posts', [
            'name' => 'Este es un nombre de prueba',
            'slug' => 'esto-esun-nombre-de-prueba-33',
            'extract' => 'Este es un extract de prueba',
            'body' => 'Este es un body de prueba',
            'category_id' => 1
        ]);

        return $response->json();
    }
}
