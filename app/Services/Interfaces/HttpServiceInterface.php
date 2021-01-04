<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Psr\Http\Message\ResponseInterface;

interface HttpServiceInterface
{
    public function getHttpResponse(string $url, float &$timeTotal): ?ResponseInterface;
    public function prepareXStats(Collection $collection): string;
}