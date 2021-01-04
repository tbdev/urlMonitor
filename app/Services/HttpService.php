<?php

namespace App\Services;

use App\Services\Interfaces\HttpServiceInterface;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\TransferStats;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Database\Eloquent\Collection;

class HttpService implements HttpServiceInterface
{
    private CONST NUMBER_META_REDIRECTS = 100;
    private CONST TIME_OUT = 30;
    private const X_STATS = '"%s": [redirections: %d, time: %f],';
    private const X_STATS_FAIL = '"%s": null,';

    private Client $httpClient;

    public function __construct(
        Client $HttpClient
    )
    {
        $this->httpClient = $HttpClient;
    }

    public function getHttpResponse(string $url, float &$timeTotal): ?ResponseInterface
    {
        try
        {
            return $this->httpClient->request('GET', $url, [
                'allow_redirects' => [
                    'max'             => self::NUMBER_META_REDIRECTS,        
                    'referer'         => true,
                    'track_redirects' => true,
                    'timeout' => self::TIME_OUT,
                ],
                'on_stats' => function (TransferStats $stats) use (&$timeTotal)
                {
                    $timeTotal += $stats->getTransferTime();
                }
            ]); 
        } 
        catch (RequestException $e) 
        {
            Log::info($e->getRequest());
            return null;
        }
    }

    public function prepareXStats(Collection $collection): string
    {
        $prepared = null;

        $collection->each(function ($item, $key) use (&$prepared) {
           
            if($item->samples->count())
            {
                $prepared .= sprintf(self::X_STATS, 
                    $item->url, 
                    $item->samples->first()->redirections, 
                    $item->samples->first()->time);
            }
            else
            {
                $prepared .= sprintf(self::X_STATS_FAIL, $item->url);
            }
        });         

        return rtrim($prepared, ',');
    }
}
