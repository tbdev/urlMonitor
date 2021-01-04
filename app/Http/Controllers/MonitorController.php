<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\UrlMonitorServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Requests\UrlMonitorRequest;

class MonitorController extends Controller
{
    private UrlMonitorServiceInterface $urlMonitorService;

    public function __construct(UrlMonitorServiceInterface $urlMonitorService)
    {
        $this->urlMonitorService = $urlMonitorService;
    }

    public function getSamplesByUrl(string $url): JsonResponse 
    {
        return $this->urlMonitorService->getSamplesByUrlWithTimeLimit($url);
    }

    public function store(UrlMonitorRequest $request): JsonResponse
    {
        $links = $this->urlMonitorService->createManyLinks($request->get('urls'));

        $this->urlMonitorService->break();
        
        $response = new JsonResponse(null, Response::HTTP_OK);
        
        if($request->has('stats'))
        {
            return $response->header('X-Stats', $this->urlMonitorService->getXStats($links));
        }
        
        return $response; 
    }
}
