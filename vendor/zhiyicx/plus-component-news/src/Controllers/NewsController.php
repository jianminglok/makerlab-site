<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentNews\view;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsRecommend;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCollection;

class NewsController extends Controller
{
    /**
     * 资讯列表.
     * @param  $cate_id [分类ID]
     * @return mixed 返回结果
     */
    public function getNewsList(Request $request)
    {
        $uid = Auth::guard('api')->user()->id ?? 0;
        $cate_id = $request->cate_id;
        $max_id = $request->max_id;
        $limit = $request->limit ?? 15;

        switch ($cate_id) {
            // 推荐
            case -1:
                $datas['list'] = News::where('is_recommend', 1)
                        ->where(function ($query) use ($max_id) {
                            if ($max_id > 0) {
                                $query->where('news.id', '<', $max_id);
                            }
                        })
                        ->orderBy('news.id', 'desc')
                        ->take($limit)
                        ->select('id', 'title', 'created_at', 'updated_at', 'storage', 'from')
                        ->with('image')
                        ->get();
                $datas['recommend'] = $max_id > 0 ? $this->getRecommendList() : [];
                break;

            // 分类
            default:
                $datas['list'] = News::where('cate_id', $cate_id)
                        ->where(function ($query) use ($max_id) {
                            if ($max_id > 0) {
                                $query->where('id', '<', $max_id);
                            }
                        })
                        ->orderBy('id', 'desc')
                        ->select('id', 'title', 'created_at', 'updated_at', 'from', 'storage')
                        ->with('image')
                        ->take($limit)
                        ->get();
                $datas['recommend'] = $max_id > 0 ? $this->getRecommendList($cate_id) : [];
                break;
        }

        foreach ($datas['list'] as $data) {
            $data->is_collection_news = $uid ? NewsCollection::where('user_id', $uid)->where('news_id', $data['id'])->count() : 0;
            $data->is_digg_news = $data->liked($uid) ? 1 : 0;
            unset($data->category, $data->pinned);
        }

        // 转为数组跳过模型对storage字段的隐藏
        $datas['list'] = $datas['list']->toArray();
        foreach ($datas['list'] as &$data) {
            $data['storage'] = $data['image'];
            unset($data['image']);
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas,
        ]))->setStatusCode(200);
    }

    /**
     * 搜索资讯.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function searchNewsList(Request $request)
    {
        $uid = Auth::guard('api')->user()->id ?? 0;
        $max_id = $request->max_id;
        $limit = $request->limit ?? 15;
        $key = $request->key;
        $news_ids = $request->input('news_ids');
        is_string($news_ids) && $news_ids = explode(',', $news_ids);
        $datas = News::where(function ($query) use ($key) {
            if ($key) {
                $query->where('title', 'like', '%'.$key.'%');
            }
        })
        ->where(function ($query) use ($news_ids) {
            if (count($news_ids)) {
                $query->whereIn('id', $news_ids);
            }
        })
        ->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('news.id', '<', $max_id);
            }
        })
        ->orderBy('news.id', 'desc')
        ->select('news.id', 'news.title', 'news.created_at', 'news.updated_at', 'news.storage', 'news.from')
        ->take($limit)
        ->get();

        foreach ($datas as $data) {
            $data->is_collection_news = $uid ? NewsCollection::where('user_id', $uid)->where('news_id', $data['id'])->count() : 0;
            $data->is_digg_news = $data->liked($uid) ? 1 : 0;
            unset($data->category, $data->pinned);
        }

        // 转为数组跳过模型对storage字段的隐藏
        $datas = $datas->toArray();
        foreach ($datas as &$data) {
            $data['storage'] = $data['image'];
            unset($data['image']);
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas,
        ]))->setStatusCode(200);
    }

    /**
     * 获取用户收藏资讯列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getCollectionList(Request $request)
    {
        $uid = $request->user()->id;
        $max_id = $request->max_id;
        $limit = $request->limit ?? 15;
        $datas = News::whereIn('id', NewsCollection::where('user_id', $uid)->pluck('news_id'))
        ->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('news.id', '<', $max_id);
            }
        })
        ->orderBy('news.id', 'desc')
        ->select('news.id', 'news.title', 'news.created_at', 'news.updated_at', 'news.storage', 'news.from')
        ->take($limit)
        ->get();

        foreach ($datas as $data) {
            $data->is_collection_news = $uid ? NewsCollection::where('user_id', $uid)->where('news_id', $data['id'])->count() : 0;
            $data->is_digg_news = $data->liked($uid) ? 1 : 0;
            unset($data->category, $data->pinned);
        }

        // 转为数组跳过模型对storage字段的隐藏
        $datas = $datas->toArray();
        foreach ($datas as &$data) {
            $data['storage'] = $data['image'];
            unset($data['image']);
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas,
        ]))->setStatusCode(200);
    }

    /**
     * Get single news info.
     *
     * @author Foreach<missu082500@163.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function newsDetail(Request $request, News $news)
    {
        $news->increment('hits'); // 增加点击数

        if ($request->input('accept') == 'json') {
            $uid = Auth::guard('api')->user()->id ?? 0;
            $data = $news;

            $data->is_collection_news = $uid ? $news->collections()->where('user_id', $uid)->count() : 0;
            $data->is_digg_news = $data->liked($uid) ? 1 : 0;

            return response()->json(static::createJsonData([
                'status'  => true,
                'code'    => 0,
                'message' => '获取成功',
                'data'    => $data,
            ]))->setStatusCode(200);
        }

        return view('detail', $news->toArray());
    }

    /**
     * 获取推荐banner.
     *
     * @author bs<414606094@qq.com>
     * @param  int|int $cate_id [description]
     * @return [type]               [description]
     */
    public function getRecommendList(int $cate_id = 0)
    {
        $data = NewsRecommend::where('cate_id', $cate_id)->with('cover')->get()->toArray();

        return $data;
    }
}
