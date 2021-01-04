<?php

namespace App\Services;

use App\Services\Interfaces\DOMServiceInterface;
use DOMDocument;
use DOMXPath;

class DOMService implements DOMServiceInterface
{
    private CONST REGEX = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';

    private DOMDocument $domDocument;

    public function __construct(DOMDocument $domDocument)
    {
        $this->domDocument = $domDocument;
    }

    public function getMetaTagsRedirection(?string $body): ?string
    {
        if (!$body)
        {
            return null;
        } 

        @$this->domDocument->loadHTML($body);

        $xpath = new DOMXpath($this->domDocument);
        $nodeList = $xpath->query('//meta[@http-equiv="refresh"]/@content');

        if(empty($nodeList->item(0)->nodeValue))
        {
            return null;
        }
        
        preg_match(self::REGEX, $nodeList->item(0)->nodeValue, $matches);
        
        $urlRefresh = $matches[0];

        if(empty($urlRefresh))
        {
            return null;
        }

        return trim($urlRefresh);
    }
}