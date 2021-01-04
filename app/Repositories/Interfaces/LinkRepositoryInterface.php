<?php

namespace App\Repositories\Interfaces;

use App\Models\Link;
use Illuminate\Database\Eloquent\Collection;

interface LinkRepositoryInterface
{
    public function create(string $url): Link; 
    public function getAll(): Collection;
    public function getByUrlWithSamplesAndTimeLimit(string $url, int $minuteLimit): ?Link;
    public function getByUrlsWithSamplesAndTimeLimit(array $urls): Collection;
}
