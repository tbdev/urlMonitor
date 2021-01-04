<?php

namespace App\Services;

use App\Models\Link;
use App\Services\Interfaces\LinkMeasureServiceInterface;
use App\Services\Interfaces\DOMServiceInterface;
use App\Services\Interfaces\HttpServiceInterface;

class LinkMeasureService implements LinkMeasureServiceInterface
{
    private DOMServiceInterface $domService;
    private HttpServiceInterface $httpService;

    public function __construct(
        DOMServiceInterface $domService,
        HttpServiceInterface $httpService
    )
    {
        $this->domService = $domService;
        $this->httpService = $httpService;
    }

    public function getMeasureSample(Link $link): array
    {
        return $this->prepare($this->exploreLink($link->url));
    }

    private function exploreLink(string $url, int $redirectionsCounter = 0, float $timeTotal = 0): ?array
    {
        $response = $this->httpService->getHttpResponse($url, $timeTotal);

        if(!$response)
        {
            return null;
        }

        $redirectionsCounter += count($response->getHeader('X-Guzzle-Redirect-Status-History'));

        if($metaRefreshUrl = $this->domService->getMetaTagsRedirection($response->getBody()))
        {
            return $this->exploreLink(trim($metaRefreshUrl), $redirectionsCounter + 1, $timeTotal);
        }

        return ['redirects' => $redirectionsCounter, 'totalTime' => $timeTotal];
    }

    private function prepare(array $data): array
    {
        return [
            'redirections' => $data['redirects'] ?? 0,
            'time' => $data['totalTime'] ?? 0
        ];
    }
}