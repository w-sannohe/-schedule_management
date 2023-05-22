<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Application\Services\ScheduleService;
use App\Application\Services\ScheduleServiceInterface;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ScheduleServiceInterface::class, ScheduleService::class);
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
