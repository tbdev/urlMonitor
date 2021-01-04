<?php

namespace App\Services\Interfaces;

use App\Models\Link;

interface LinkMeasureServiceInterface
{
    public function getMeasureSample(Link $link): array;
}