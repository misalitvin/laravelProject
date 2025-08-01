<?php

namespace App\Interfaces;

use Illuminate\Http\Client\Response;

interface HttpClientInterface
{
    public function get(string $url): Response;
}
