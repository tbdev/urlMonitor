<?php

namespace App\Services\Interfaces;

use Illuminate\Http\JsonResponse;
use Exception;

interface UrlMonitorServiceInterface
{
    public function createLink(string $url): void;
    public function createManyLinks(array $urls): array;
    public function getSamplesByUrlWithTimeLimit(string $url): JsonResponse;
    public function getXStats(array $links): string;
    public function break(?int $seconds = null): void;
}