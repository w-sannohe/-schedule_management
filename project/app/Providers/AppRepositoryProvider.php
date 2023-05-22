<?php

namespace App\Providers;

use App\Domain\Repositories\HitosaraEventRepositoryInterface;
use App\Domain\Repositories\ScheduleRepositoryInterface;
use App\Infrastructure\Repositories\HitosaraEventRepository;
use App\Infrastructure\Repositories\ScheduleRepository;
use Illuminate\Support\ServiceProvider;

class AppRepositoryProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
        $this->app->bind(HitosaraEventRepositoryInterface::class, HitosaraEventRepository::class);
    }
}
