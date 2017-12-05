<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Installer;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Support\PackageHandler;
use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\ManageRepository;
use Zhiyi\Plus\Support\BootstrapAPIsEventer;
use Illuminate\Database\Eloquent\Relations\Relation;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentNews\asset;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentNews\base_path as component_base_path;

class NewsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__).'/../resource' => $this->app->PublicPath().'/zhiyicx/plus-component-news',
        ], 'public');

        $this->loadRoutesFrom(
            dirname(__DIR__).'/../router.php'
        );

        $this->publishes([
            component_base_path('/config/news.php') => $this->app->configPath('news.php'),
        ], 'config');

        PackageHandler::loadHandleFrom('news', NewsPackageHandler::class);

        // Register Bootstraper API event.
        $this->app->make(BootstrapAPIsEventer::class)->listen('v2', function () {
            return [
                'news:contribute' => $this->app->make(ConfigRepository::class)->get('news.contribute'),
                'news:pay_conyribute' => (int) $this->app->make(ConfigRepository::class)->get('news.pay_conyribute'),
            ];
        });
    }

    /**
     * register provided to provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function register()
    {
        $this->app->make(ManageRepository::class)->loadManageFrom('资讯', 'news:admin', [
            'route' => true,
            'icon' => asset('news-icon.png'),
        ]);

        $this->mergeConfigFrom(
            component_base_path('/config/news.php'), 'news'
        );

        User::macro('newsCollections', function () {
            return $this->belongsToMany(News::class, 'news_collections', 'user_id', 'news_id');
        });

        Relation::morphMap([
            'news' => News::class,
        ]);
    }

    /**
     * Register route.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function routeMap()
    {
        $this->app->make(RouteRegistrar::class)->all();
    }
}
