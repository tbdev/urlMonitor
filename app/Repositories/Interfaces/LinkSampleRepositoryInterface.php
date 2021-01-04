<?php

namespace App\Repositories\Interfaces;

use App\Models\Link;
use App\Models\LinkSample;

interface LinkSampleRepositoryInterface
{
    public function createOne(Link $link, array $data): ?LinkSample;
}
