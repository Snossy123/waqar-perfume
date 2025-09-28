<?php

namespace App\Providers;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Messaging;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use App\Interfaces\SavedSearchInterface;
use App\Repositories\SavedSearchRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(Messaging::class, function ($app) {
            $firebase = (new Factory)
                ->withServiceAccount(storage_path(env('FIREBASE_CREDENTIALS')));
            return $firebase->createMessaging();
        });

        $this->app->bind(
            \App\Interfaces\CarRepositoryInterface::class,
            \App\Repositories\CarRepository::class,
            \Spatie\Permission\PermissionServiceProvider::class,

        );

        $this->app->bind(
            \App\Interfaces\SavedSearchInterface::class,
            \App\Repositories\SavedSearchRepository::class
        );


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });
        Schema::defaultStringLength(191);
    }
}
