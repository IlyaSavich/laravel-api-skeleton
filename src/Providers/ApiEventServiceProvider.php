<?php
namespace Savich\ApiSkeleton\Providers;

use Event;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Savich\ApiSkeleton\Response\ResponseApi;

class ApiEventServiceProvider extends ServiceProvider
{
    /**
     * @var ResponseApi
     */
    protected $response;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->response = new ResponseApi();
    }

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        Event::listen('tymon.jwt.absent', function () {
            return $this->response->send([], ['Token not provided'], ResponseApi::STATUS_NOT_PROVIDED);
        });

        Event::listen('tymon.jwt.expired', function () {
            return $this->response->send([], ['Token expired'], ResponseApi::STATUS_EXPIRED);
        });

        Event::listen('tymon.jwt.invalid', function () {
            return $this->response->send([], ['Token invalid'], ResponseApi::STATUS_INVALID);
        });
    }
}
