<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Currency;
use App\Interfaces\CurrencyClientInterface;
use App\Interfaces\HttpClientInterface;
use RuntimeException;

final class EcbCurrencyClient implements CurrencyClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient
    ) {}

    public function fetchRates(): array
    {
        $url = config('services.ecb.url');
        $response = $this->httpClient->get($url);

        if (! $response->ok()) {
            throw new RuntimeException('Failed to fetch exchange rates.');
        }

        $xml = simplexml_load_string($response->body());
        $xml->registerXPathNamespace('ns', 'http://www.ecb.int/vocabulary/2002-08-01/eurofxref');
        $nodes = $xml->xpath('//ns:Cube/ns:Cube/ns:Cube');

        $result = [Currency::EUR->value => 1.0];
        foreach ($nodes as $node) {
            $currency = (string) $node['currency'];
            $rate = (float) $node['rate'];

            if (in_array($currency, Currency::all(), true)) {
                $result[$currency] = $rate;
            }
        }

        return $result;
    }
}
