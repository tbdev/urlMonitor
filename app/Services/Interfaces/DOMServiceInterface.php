<?php

namespace App\Services\Interfaces;

interface DOMServiceInterface
{
    public function getMetaTagsRedirection(?string $body): ?string;
}