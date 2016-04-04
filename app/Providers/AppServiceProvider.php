<?php

namespace App\Providers;

use App\Property;
use Illuminate\Support\ServiceProvider;
use Olm\Perseo\Contracts\PropertyManager;
use Olm\Perseo\Contracts\EloquentPropertyModel;
use Olm\Perseo\Implementation\EloquentPropertyManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(PropertyManager::class, EloquentPropertyManager::class);
        // $this->app->bind(EloquentPropertyModel::class, Property::class);
    }
}
