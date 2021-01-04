<?php

namespace App\Services;

use App\Exceptions\Monitor404Exception;
use App\Services\Interfaces\UrlMonitorServiceInterface;
use App\Repositories\Interfaces\LinkRepositoryInterface;
use App\Services\Interfaces\HttpServiceInterface;
use App\Http\Resources\UrlResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Jobs\LinkMeasureTimeAndRedirections;

class UrlMonitorService implements UrlMonitorServiceInterface
{
    private const HISTORY_MINUTE = 10;
    private const BREAK_TIME_SEC = 2;

    private LinkRepositoryInterface $linkRepository;
    private HttpServiceInterface $httpService;

    public function __construct(
        LinkRepositoryInterface $linkRepository,
        HttpServiceInterface $httpService
    )
    {
        $this->linkRepository = $linkRepository;
        $this->httpService = $httpService;
    }

    public function createLink(string $url): void
    {
        $this->linkRepository->create($url);
    }

    public function createManyLinks(array $urls): array
    {
        $links = [];

        foreach($urls as $url)
        {
            $link = $this->linkRepository->create($url);
            
            LinkMeasureTimeAndRedirections::dispatch($link)->onQueue('new'); 
            
            $links[] = $link->url;
        }

        return $links;
    }

    public function getSamplesByUrlWithTimeLimit(string $url): JsonResponse
    {
        $data = $this->linkRepository->getByUrlWithSamplesAndTimeLimit($url, self::HISTORY_MINUTE);

        if(!$data)
        {
            throw new Monitor404Exception('URL not exist!');
        }

        return UrlResource::make($data)->additional(['message' => 'hello there!'])->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function getXStats(array $links): string
    {
        $collection = $this->linkRepository->getByUrlsWithSamplesAndTimeLimit($links);
        
        return $this->httpService->prepareXStats($collection);
    }

    public function break(?int $seconds = null): void
    {
        $seconds ?? $seconds = self::BREAK_TIME_SEC;
        sleep($seconds);
    }
}