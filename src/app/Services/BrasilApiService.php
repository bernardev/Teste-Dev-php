<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BrasilApiService
{
    public function getAddressByCep(string $cep)
    {
        $response = Http::get("https://brasilapi.com.br/api/cep/v1/{$cep}");

        if ($response->failed() || $response->status() === 404) {
            return null;
        }

        return $response->json();
    }
}
