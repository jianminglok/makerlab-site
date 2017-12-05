<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\AdminControllers;

use DB;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\FileWith;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsDigg;
use function zhiyi\Component\ZhiyiPlus\PlusComponentNews\getShort;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsComment;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCateLink;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCollection;

/**
 * 后台资讯管理.
 */
class NewsController extends Controller
{
    /**
     * 资讯列表.
     * @param  $cate_id [分类ID]
     * @return mixed 返回结果
     */
    public function getNewsList(Request $request)
    {
        $cate_id = $request->cate_id ?? '';
        $max_id = $request->max_id;
        $limit = $request->limit ?? 15;
        $key = $request->key;
        $state = $request->state ?? null;

        // if ($cate_id) {
        $datas = News::where(function ($query) use ($key) {
            if ($key) {
                $query->where('news.title', 'like', '%'.$key.'%');
            }
        })
            ->when($cate_id > 0, function ($query) use ($cate_id) {
                $query->where('cate_id', $cate_id);
            })
            ->where(function ($query) use ($state) {
                if ($state !== null) {
                    $query->where('news.audit_status', $state);
                }
            })
            ->orderBy('id', 'desc')
            ->with('user')
            ->paginate($limit);

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas,
        ]))->setStatusCode(200);
    }

    /**
     * 回收站资讯列表.
     *
     * @param  $cate_id [分类ID]
     * @return mixed 返回结果
     */
    public function getRecycleList(Request $request)
    {
        $cate_id = $request->cate_id ?? '';
        $max_id = $request->max_id;
        $limit = $request->limit ?? 15;
        $key = $request->key;

        $datas = News::where(function ($query) use ($key) {
            if ($key) {
                $query->where('news.title', 'like', '%'.$key.'%');
            }
        })
            ->when($cate_id > 0, function ($query) use ($cate_id) {
                $query->where('cate_id', $cate_id);
            })
            ->where(function ($query) use ($state) {
                if ($state !== null) {
                    $query->where('news.audit_status', $state);
                }
            })
            ->where('audit_status', 4)
            ->orderBy('id', 'desc')
            ->with('user')
            ->paginate($limit);

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas,
        ]))->setStatusCode(200);
    }

    public function doSaveNews(Request $request)
    {
        $type = $request->type ?? 1; // 1 待审核 2 草稿

        // if (! $request->storage_id) {
        //     return response()->json(static::createJsonData([
        //         'status' => false,
        //         'message' => '没有上传封面图片',
        //     ]));
        // }
        if (mb_strlen($request->content, 'utf8') > 10000) {
            return response()->json(static::createJsonData([
                'status' => false,
                'message' => '内容不能大于10000字',
            ]));
        }

        if ($request->news_id) {
            $news = News::find($request->news_id);
            if ($news) {
                $news->title = $request->title;
                $news->subject = $request->subject ?: getShort($request->content, 60);
                $news->content = $request->content;
                $news->storage = $request->storage;
                $news->from = $request->source ?: '';
                $news->cate_id = $request->cate_id;
                $news->author = $request->author;
                // $news->audit_status = $type;
                $news->save();
            }
        } else {
            $news = new News();
            $news->title = $request->title;
            $news->subject = $request->subject ?: getShort($request->content, 60);
            $news->author = $request->user()->id;
            $news->content = $request->content;
            $news->storage = $request->storage;
            $news->from = $request->source ?: '';
            $news->cate_id = $request->cate_id;
            $news->author = $request->author;
            // $news->audit_status = $type;
            $news->save();
        }
        if ($request->storage_id) {
            $fileWith = FileWith::find($request->storage);
            if ($fileWith) {
                $fileWith->channel = 'news:storage';
                $fileWith->raw = $news->id;
                $fileWith->save();
            }
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '操作成功',
            'data'    => $news->id,
        ]))->setStatusCode(200);
    }

    public function recommend(Request $request, int $news_id)
    {
        $news = News::find($news_id);
        if ($news) {
            $news->is_recommend = abs($news->is_recommend - 1);
            $news->save();

            return response()->json(static::createJsonData([
                'status'  => true,
                'message' => '操作成功',
            ]))->setStatusCode(200);
        }
    }

    public function auditNews(Request $request, int $news_id)
    {
        $news = News::find($news_id);
        if ($news) {
            $news->audit_status = $request->state;
            $news->save();

            return response()->json(static::createJsonData([
                'status'  => true,
                'message' => '操作成功',
            ]))->setStatusCode(200);
        }
    }

    public function delNews(Request $request, int $news_id)
    {
        $is_del = $request->is_del ?? 0;
        $news = News::find($news_id);
        if (! $news) {
            return response()->json(static::createJsonData([
                'status'  => false,
                'message' => '参数错误',
            ]));
        }

        if (! $is_del) {
            $news->audit_status = 4;
            $news->save();

            return response()->json(static::createJsonData([
                'status'  => true,
                'message' => '已添加到回收站',
            ]));
        } else {
            DB::transaction(function () use ($news, $news_id) {
                $news->delete();
                NewsDigg::where('news_id', $news_id)->delete();
                NewsCateLink::where('news_id', $news_id)->delete();
                NewsCollection::where('news_id', $news_id)->delete();
                NewsComment::where('news_id', $news_id)->delete();
            });

            return response()->json(static::createJsonData([
                'status'  => true,
                'message' => '删除成功',
            ]));
        }
    }

    public function getNews(int $news_id)
    {
        $news = News::with('links')->find($news_id);

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '获取成功',
            'data' => $news,
        ]))->setStatusCode(200);
    }
}
