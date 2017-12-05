<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\AdvertisingSpace;

class FeedAdvertisingSpaceSeeder extends Seeder
{
    public function run()
    {
        AdvertisingSpace::create([
            'channel' => 'feed',
            'space' => 'feed:list:top',
            'alias' => '动态列表顶部广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string',
                    'link' => '链接|string',
                ],
            ],
        ]);
        AdvertisingSpace::create([
            'channel' => 'feed',
            'space' => 'feed:single',
            'alias' => '动态详情广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string',
                    'link' => '链接|string',
                ],
            ],
        ]);
        AdvertisingSpace::create([
            'channel' => 'feed',
            'space' => 'feed:list:analog',
            'alias' => '动态列表模拟数据广告',
            'allow_type' => 'feed:analog',
            'format' => [
                'feed:analog' => [
                    'avatar' => '头像图|image',
                    'name' => '用户名|string',
                    'content' => '内容|string',
                    'image' => '图片|image',
                    'time' => '时间|date',
                    'link' => '链接|string',
                ],
            ],
        ]);
    }
}
