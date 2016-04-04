<?php

namespace Olm\Perseo;

use Queue;
use Illuminate\Support\ServiceProvider;
use Olm\Perseo\Contracts\PropertyManager;
use Olm\Perseo\Implementation\IdGenerator;
use Olm\Perseo\Implementation\ConfigPropertyManager;
use Olm\Perseo\Contracts\IdGenerator as IdGeneratorContract;

class PerseoServiceProvider extends ServiceProvider
{
   /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/perseo.php', 'perseo');

        //register the queue finish
        Queue::after(function ($connection, $job, $data) {
            //
        });

        Queue::failing(function ($connection, $job, $data) {
            // Notify team of failing job...
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindIf(IdGeneratorContract::class, IdGenerator::class);
        $this->app->bindIf(PropertyManager::class, ConfigPropertyManager::class);
        $this->app->singleton('operation', Operation::class);
    }
}
