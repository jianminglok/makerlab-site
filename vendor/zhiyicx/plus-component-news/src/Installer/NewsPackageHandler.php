<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Installer;

use Carbon\Carbon;
use Zhiyi\Plus\Models\Ability;
use Zhiyi\Plus\Models\Comment;
use Illuminate\Support\Facades\Schema;
use Zhiyi\Plus\Support\PackageHandler;
use Zhiyi\Plus\Models\AdvertisingSpace;
use Illuminate\Database\Schema\Blueprint;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentNews\base_path as component_base_path;

class NewsPackageHandler extends PackageHandler
{
    public function removeHandle($command)
    {
        if ($command->confirm('This will delete your datas for news, continue?')) {
            Ability::whereIn('name', ['news-comment', 'news-digg', 'news-collection'])->delete();
            Comment::where('component', 'news')->delete();
            Schema::dropIfExists('news');
            Schema::dropIfExists('news_cates');
            Schema::dropIfExists('news_cates_links');
            Schema::dropIfExists('news_cates_follow');
            Schema::dropIfExists('news_comments');
            Schema::dropIfExists('news_diggs');
            Schema::dropIfExists('news_collections');
            Schema::dropIfExists('news_recommend');
            Schema::dropIfExists('news_pinneds');

            $command->info('The News Component has been removed');
        }
    }

    public function installHandle($command)
    {
        if (! Schema::hasTable('news')) {
            Schema::create('news', function (Blueprint $table) {
                $table->increments('id')->comment('主键');
                $table->timestamps();
                $table->softDeletes();
            });
            include component_base_path('/databases/table_news_column.php');
        }

        if (! Schema::hasTable('news_cates')) {
            Schema::create('news_cates', function (Blueprint $table) {
                $table->increments('id')->comment('主键');
            });
            include component_base_path('/databases/table_news_cates_column.php');
        }

        if (! Schema::hasTable('news_cates_links')) {
            Schema::create('news_cates_links', function (Blueprint $table) {
                $table->increments('id')->comment('主键');
            });
            include component_base_path('/databases/table_news_cates_links_column.php');
        }

        if (! Schema::hasTable('news_cates_follow')) {
            Schema::create('news_cates_follow', function (Blueprint $table) {
                $table->increments('id')->comment('主键');
            });
            include component_base_path('/databases/table_news_cates_follow_column.php');
        }

        // if (! Schema::hasTable('news_comments')) {
        //     Schema::create('news_comments', function (Blueprint $table) {
        //         $table->increments('id')->comment('主键');
        //         $table->timestamps();
        //     });
        //     include component_base_path('/databases/table_news_comments_column.php');
        // }

        // if (! Schema::hasTable('news_diggs')) {
        //     Schema::create('news_diggs', function (Blueprint $table) {
        //         $table->increments('id')->comment('主键');
        //         $table->timestamps();
        //     });
        //     include component_base_path('/databases/table_news_diggs_column.php');
        // }

        if (! Schema::hasTable('news_collections')) {
            Schema::create('news_collections', function (Blueprint $table) {
                $table->increments('id')->comment('主键');
                $table->timestamps();
            });
            include component_base_path('/databases/table_news_collections_column.php');
        }

        if (! Schema::hasTable('news_recommend')) {
            Schema::create('news_recommend', function (Blueprint $table) {
                $table->increments('id')->comment('主键');
                $table->timestamps();
            });
            include component_base_path('/databases/table_news_recommend_column.php');
        }

        if (! Schema::hasTable('news_pinneds')) {
            Schema::create('news_pinneds', function (Blueprint $table) {
                $table->increments('id')->comment('主键');
                $table->timestamps();
            });
            include component_base_path('/databases/create_newspinned_table.php');
        }

        $time = Carbon::now();

        Ability::insert([
            [
                'name' => 'news-comment',
                'display_name' => '评论资讯',
                'description' => '用户评论资讯权限',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'news-digg',
                'display_name' => '点赞资讯',
                'description' => '用户点赞资讯权限',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'news-collection',
                'display_name' => '收藏资讯',
                'description' => '用户收藏资讯权限',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ]);

        AdvertisingSpace::create([
            'channel' => 'news',
            'space' => 'news:list:top',
            'alias' => '资讯列表顶部广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string',
                    'link' => '链接|string',
                ],
            ],
        ]);
        AdvertisingSpace::create([
            'channel' => 'news',
            'space' => 'news:single',
            'alias' => '资讯详情广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string',
                    'link' => '链接|string',
                ],
            ],
        ]);
        AdvertisingSpace::create([
            'channel' => 'news',
            'space' => 'news:list:analog',
            'alias' => '资讯列表模拟数据广告',
            'allow_type' => 'news:analog',
            'format' => [
                'news:analog' => [
                    'title' => '标题|string',
                    'image' => '图片|image',
                    'from' => '来源|string',
                    'time' => '时间|date',
                    'link' => '链接|string',
                ],
            ],
        ]);
        $command->info('Install Successfully');
    }
}
