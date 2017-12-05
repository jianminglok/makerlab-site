<?php

namespace SlimKit\Plusinstaller\Providers;

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the service provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function boot()
    {
        $this->loadRoutesFrom(
            $this->app->make('path.installer.root').'/routes.php'
        );
    }
}
