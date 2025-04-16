<?php

namespace Darkpony\Fastly;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;

class FastlyCache
{

    public function purge(string $items)
    {
        $endpoint    = config('fastly.endpoint', 'https://api.fastly.com');
        $domain    = config('fastly.domain', '');
        $api_key     = config('fastly.api_key', '');
        $stale    = config('fastly.stale', 1);
        $service_id    = config('fastly.service_id', '');

        if($api_key != '' && $endpoint != '' && $domain != '') {

            if($items == '*') {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Fastly-Key' => $api_key
                ])->withBody(json_encode(['service_id' => $service_id]),'application/json')->post($endpoint.'/service/'.$service_id.'/purge_all');
            }
            elseif($items == '/') {
                $response = Http::withHeaders([
                    'Fastly-Key' => $api_key,
                    'fastly-soft-purge' => $stale,
                    'Accept' => 'application/json'
                ])->post($endpoint.'/purge/'.$domain.'/', []);
            }
            else {
                $urls = explode(',', $items);
                foreach ($urls as $url) {
                    $response = Http::withHeaders([
                            'Fastly-Key' => $api_key,
                            'fastly-soft-purge' => $stale,
                            'Accept' => 'application/json'
                        ])->post($endpoint.'/purge/'.$domain.$url, []);
                }
            }
            /*
            $response = $res->getBody()->getContents();
            $result = json_decode($response, true);

            if ( ! empty( $result['error'] ) ) {
                \Log::info('Fastly: Error while trying to purge cache: ' . $result['error']['message']);
            }

            if ( ! $result['success'] ) {
                \Log::info('Fastly: Unknown error while trying to purge cache.');
            }*/

            \Log::info('Fastly: Cache purged successfully');

        }
    }

    public function purgeAll()
    {
        return $this->purge('*');
    }

}
