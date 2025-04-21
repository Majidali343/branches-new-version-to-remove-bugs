<?php

namespace App\Providers;

use App\Repositories\HouseholdRepository;
use App\Repositories\GroupRepository;
use App\Interfaces\HouseholdRepositoryInterface;
use App\Interfaces\GroupRepositoryInterface;
use App\Repositories\MessagingRepository;
use App\Repositories\MessagingRepositoryInterface;
use App\Interfaces\NotificationRepositoryInterface;
use App\Repositories\NotificationRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind( HouseholdRepositoryInterface::class, HouseholdRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->bind(MessagingRepositoryInterface::class, MessagingRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
