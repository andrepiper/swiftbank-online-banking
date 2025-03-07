<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\IdObfuscator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Account;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        // Add custom route model binding for obfuscated account IDs
        $this->bindObfuscatedModels();
    }

    /**
     * Bind models with obfuscated IDs
     */
    protected function bindObfuscatedModels(): void
    {
        // Bind Account model with obfuscated ID
        $this->app['router']->bind('obfuscated_account', function ($value) {
            $id = IdObfuscator::decode($value);

            if (!$id) {
                throw new ModelNotFoundException();
            }

            $model = Account::find($id);

            if (!$model) {
                throw new ModelNotFoundException();
            }

            return $model;
        });

        // Add more model bindings here as needed
    }
}
