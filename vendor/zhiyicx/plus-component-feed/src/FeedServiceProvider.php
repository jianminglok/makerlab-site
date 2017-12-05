<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed;

use Zhiyi\Plus\Support\PackageHandler;
use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\ManageRepository;
use Illuminate\Database\Eloquent\Relations\Relation;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;

class FeedServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function boot()
    {
        $this->routeMap();
        $this->publishHandler();
        $this->registerObserves();

        // Load views.
        $this->loadViewsFrom(dirname(__DIR__).'/views/', 'feed:view');

        // Register migration files.
        $this->loadMigrationsFrom([
            dirname(__DIR__).'/database/migrations',
        ]);

        $this->publishes([
            dirname(__DIR__).'/config/feed.php' => $this->app->configPath('feed.php'),
        ], 'config');

        $this->publishes([
            dirname(__DIR__).'/assets' => $this->app->PublicPath().'/zhiyicx/plus-component-feed',
        ], 'public');
    }

    /**
     * register provided to provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function register()
    {
        $this->app->make(ManageRepository::class)->loadManageFrom('动态管理', 'feed:admin', [
            'route' => true,
            'icon' => asset('zhiyicx/plus-component-feed/feed-icon.png'),
        ]);

        $this->mergeConfigFrom(
            dirname(__DIR__).'/config/feed.php', 'feed'
        );

        Relation::morphMap([
            'feeds' => Models\Feed::class,
        ]);
    }

    /**
     * Register model events.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerObserves()
    {
        Feed::observe(Observers\FeedObserver::class);
    }

    /**
     * Publish handler.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function publishHandler()
    {
        PackageHandler::loadHandleFrom('feed', FeedPackageHandler::class);
    }

    /**
     * Register route.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function routeMap()
    {
        if (! $this->app->routesAreCached()) {
            $this->app->make(RouteRegistrar::class)->all();
        }
    }
}
