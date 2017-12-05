<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Controllers;

use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;

class NewsDiggController extends Controller
{
    public function like(Request $request, News $news)
    {
        $user = $request->user()->id;
        if ($news->liked($user)) {
            return response()->json(static::createJsonData([
                'code' => 9005,
                'status' => false,
                'message' => '已赞过该资讯',
            ]))->setStatusCode(400);
        }

        DB::transaction(function () use ($news, $user) {
            $news->like($user);
            $news->increment('digg_count');
        });

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '点赞成功',
        ]))->setStatusCode(201);
    }

    /**
     * 取消点赞一个资讯.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @param  int     $news_id [description]
     * @return [type]           [description]
     */
    public function cancel(Request $request, News $news)
    {
        $user = $request->user()->id;
        if (! $news->liked($user)) {
            return response()->json(static::createJsonData([
                'code' => 9006,
                'status' => false,
                'message' => '未对该资讯点赞',
            ]))->setStatusCode(400);
        }

        DB::transaction(function () use ($news, $user) {
            $news->unlike($user);
            $news->decrement('digg_count');
        });

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '取消点赞成功',
        ]))->setStatusCode(204);
    }
}
