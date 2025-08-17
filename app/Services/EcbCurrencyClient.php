<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Currency;
use App\Exceptions\EcbFetchRatesException;
use App\Interfaces\CurrencyClientInterface;
use App\Interfaces\HttpClientInterface;

final class EcbCurrencyClient implements CurrencyClientInterface
{
    private string $ecbUrl;

    private string $ecbNamespace;

    public function __construct(private HttpClientInterface $httpClient)
    {
        $this->ecbUrl = config('services.ecb.url');
        $this->ecbNamespace = config('services.ecb.namespace');
    }

    public function fetchRates(): array
    {
        $response = $this->httpClient->get($this->ecbUrl);

        if (! $response->ok()) {
            throw new EcbFetchRatesException();
        }

        $xml = simplexml_load_string($response->body());
        $xml->registerXPathNamespace('ns', $this->ecbNamespace);

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
