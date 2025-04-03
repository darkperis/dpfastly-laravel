<?php

namespace Darkpony\Fastly;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;

class FastlyServiceProvider extends ServiceProvider
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
    public function boot(Router $router, Kernel $kernel)
    {
        //$router->aliasMiddleware('fastly', FastlyMiddleware::class);
        //$kernel->pushMiddleware(FastlyMiddleware::class);

        $this->publishes([
            __DIR__ . '/../config/fastly.php' => config_path('fastly.php'),
        ], 'config');
    }
}
