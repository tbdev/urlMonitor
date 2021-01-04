<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\LinkRepository;
use App\Repositories\Interfaces\LinkRepositoryInterface;

use App\Repositories\LinkSampleRepository;
use App\Repositories\Interfaces\LinkSampleRepositoryInterface;

use App\Services\UrlMonitorService;
use App\Services\Interfaces\UrlMonitorServiceInterface;

use App\Services\LinkMeasureService;
use App\Services\Interfaces\LinkMeasureServiceInterface;

use App\Services\HttpService;
use App\Services\Interfaces\HttpServiceInterface;

use App\Services\DOMService;
use App\Services\Interfaces\DOMServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        LinkRepositoryInterface::class => LinkRepository::class,
        UrlMonitorServiceInterface::class => UrlMonitorService::class,
        LinkMeasureServiceInterface::class => LinkMeasureService::class,
        LinkSampleRepositoryInterface::class => LinkSampleRepository::class,
        HttpServiceInterface::class => HttpService::class,
        DOMServiceInterface::class => DOMService::class
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
