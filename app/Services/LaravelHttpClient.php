<?php

namespace App\Services;

use App\Interfaces\HttpClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class LaravelHttpClient implements HttpClientInterface
{
    public function get(string $url): Response
    {
        return Http::get($url);
    }
}
