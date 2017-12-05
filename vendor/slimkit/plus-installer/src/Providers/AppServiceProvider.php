<?php

namespace SlimKit\PlusInstaller\Providers;

use Zhiyi\Plus\Support\PackageHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \SlimKit\PlusInstaller\Console\Commands\PackageCreateCommand::class,
                \SlimKit\PlusInstaller\Console\Commands\InstallPasswordCommand::class,
            ]);
        }

        // Register view namespace.
        $this->loadViewsFrom($this->app->make('path.installer.views'), 'plus-installer');

        // Publish config file.
        $this->publishes([
            $this->app->make('path.installer.config').'/installer.php' => $this->app->configPath('installer.php'),
        ], 'installer-config');

        // Publish public resource.
        $this->publishes([
            $this->app->make('path.installer.assets') => $this->app->PublicPath().'/assets/installer',
        ], 'installer-public');
    }

    /**
     * Register the provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function register()
    {
        //Bind paths in container.
        $this->bindPathsInContainer();

        // Register the package class aliases in the container.
        $this->registerCoreContainerAliases();

        // Register singletons.
        $this->registerSingletions();

        // Register Plus package handlers.
        $this->registerPackageHandlers();
    }

    /**
     * Bind paths in container.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function bindPathsInContainer()
    {
        foreach ([
            'path.installer.root' => $root = dirname(dirname(__DIR__)),
            'path.installer.assets' => $root.'/assets',
            'path.installer.config' => $root.'/config',
            'path.installer.resources' => $resources = $root.'/resources',
            'path.installer.views' => $resources.'/views',
            'path.installer.stubs' => $resources.'/stubs',
        ] as $abstract => $instance) {
            $this->app->instance($abstract, $instance);
        }
    }

    /**
     * Register singletons.
     *
     * @return void
     */
    protected function registerSingletions()
    {
        // Owner handler.
        $this->app->singleton('package.installer.handler', function () {
            return new \SlimKit\PlusInstaller\Handlers\PackageHandler();
        });
    }

    /**
     * Register the package class aliases in the container.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerCoreContainerAliases()
    {
        foreach ([
            'package.installer.handler' => [
                \SlimKit\PlusInstaller\Handlers\PackageHandler::class,
            ],
        ] as $abstract => $aliases) {
            foreach ((array) $aliases as $alias) {
                $this->app->alias($abstract, $alias);
            }
        }
    }

    /**
     * Register Plus package handlers.
     *
     * @return void
     */
    protected function registerPackageHandlers()
    {
        $this->loadHandleFrom('installer', 'package.installer.handler');
    }

    /**
     * Register handler.
     *
     * @param string $name
     * @param \Zhiyi\Plus\Support\PackageHandler|string $handler
     * @return void
     */
    private function loadHandleFrom(string $name, $handler)
    {
        PackageHandler::loadHandleFrom($name, $handler);
    }
}
