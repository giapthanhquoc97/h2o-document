<?php

namespace H2o\Document;

use Illuminate\Support\ServiceProvider;

class DocumentServiceProvider extends ServiceProvider
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
    public function boot()
    {
        //
        include __DIR__ . '/routes/routes.php';
        $this->publishes([
            __DIR__ . '/Middleware/Document.php' => app_path().'\Http\Middleware'
        ], 'middleware');
    }
}
