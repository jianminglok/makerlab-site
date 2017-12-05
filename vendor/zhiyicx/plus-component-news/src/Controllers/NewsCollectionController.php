<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCollection;

class NewsCollectionController extends Controller
{
    public function addNewsCollection(Request $request, int $news_id)
    {
        $news = News::find($news_id);
        if (! $news) {
            return response()->json(static::createJsonData([
                'code' => 9002,
                'message' => '该资讯不存在或已删除',
            ]))->setStatusCode(403);
        }
        $newscollection['user_id'] = $request->user()->id;
        $newscollection['news_id'] = $news_id;
        if (NewsCollection::where($newscollection)->first()) {
            return response()->json(static::createJsonData([
                'code' => 9003,
                'status' => false,
                'message' => '已收藏该资讯',
            ]))->setStatusCode(400);
        }

        NewsCollection::create($newscollection);

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '收藏成功',
        ]))->setStatusCode(201);
    }

    public function delNewsCollection(Request $request, int $news_id)
    {
        $news = News::find($news_id);
        if (! $news) {
            return response()->json(static::createJsonData([
                'code' => 9002,
                'message' => '该资讯不存在或已删除',
            ]))->setStatusCode(403);
        }
        $newscollection['user_id'] = $request->user()->id;
        $newscollection['news_id'] = $news_id;
        if (! NewsCollection::where($newscollection)->first()) {
            return response()->json(static::createJsonData([
                'code' => 9004,
                'status' => false,
                'message' => '未收藏该资讯',
            ]))->setStatusCode(400);
        }

        NewsCollection::where($newscollection)->delete();

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '取消收藏成功',
        ]))->setStatusCode(204);
    }
}
