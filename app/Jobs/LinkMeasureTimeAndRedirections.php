<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Interfaces\LinkMeasureServiceInterface;
use App\Repositories\Interfaces\LinkSampleRepositoryInterface;
use App\Models\Link;

class LinkMeasureTimeAndRedirections implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Link $link;
    private LinkSampleRepositoryInterface $linkSampleRepository;

    public function __construct(Link $link)
    {
        $this->link = $link;
    }

    public function handle(
        LinkMeasureServiceInterface $linkMeasureServiceInterface,
        LinkSampleRepositoryInterface $linkSampleRepository
    )
    {
        $data = $linkMeasureServiceInterface->getMeasureSample($this->link);

        $linkSampleRepository->createOne($this->link, $data);
    }
}
