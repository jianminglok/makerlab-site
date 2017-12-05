<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Jobs\PushMessage;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsComment;

class NewsCommentController extends Controller
{
    public function getCommentList(Request $request, int $news_id)
    {
        $limit = $request->get('limit', 15);
        $max_id = intval($request->get('max_id'));
        if (! $news_id) {
            return response()->json([
                'status' => false,
                'code' => 9001,
                'message' => '资讯ID不能为空',
            ])->setStatusCode(400);
        }
        $comments = NewsComment::byNewsId($news_id)->take($limit)->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('id', '<', $max_id);
            }
        })->select(['id', 'created_at', 'comment_content', 'user_id', 'reply_to_user_id', 'comment_mark'])->orderBy('id', 'desc')->get();

        return response()->json(static::createJsonData([
            'status' => true,
            'data' => $comments,
        ]))->setStatusCode(200);
    }

    public function addComment(Request $request, int $news_id)
    {
        $uid = $request->user()->id;
        $comment = new NewsComment();
        $comment->comment_content = $request->input('comment_content');
        $comment->user_id = $uid;
        $comment->news_id = $news_id;
        $comment->reply_to_user_id = $request->input('reply_to_user_id', 0);
        $comment->comment_mark = $request->input('comment_mark', ($request->user()->id.Carbon::now()->timestamp) * 1000); //默认uid+毫秒时间戳

        DB::transaction(function () use ($comment, $news_id) {
            $comment->save();
            News::where('id', $news_id)->increment('comment_count'); //增加评论数量
        });

        if ($comment->reply_to_user_id > 0 && $comment->reply_to_user_id != $uid) {
            $extras = ['action' => 'comment', 'type' => 'news', 'uid' => $uid, 'news_id' => $news_id, 'comment_id' => $comment->id];
            $alert = '有人评论了你，去看看吧';
            $alias = $comment->reply_to_user_id;

            dispatch(new PushMessage($alert, (string) $alias, $extras));
        }

        return response()->json(static::createJsonData([
            'status' => true,
            'code' => 0,
            'message' => '评论成功',
            'data' => $comment->id,
        ]))->setStatusCode(201);
    }

    public function delComment(Request $request, int $news_id, int $comment_id)
    {
        $uid = $request->user()->id;
        $comment = NewsComment::find($comment_id);
        if ($comment && $uid == $comment->user_id) {
            DB::transaction(function () use ($comment, $news_id) {
                $comment->delete();
                News::where('id', $news_id)->decrement('comment_count'); //减少评论数量
            });
        }

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '操作成功',
        ]))->setStatusCode(204);
    }

    /**
     * 根据id 搜索评论接口.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function searchComment(Request $request)
    {
        $comment_ids = $request->input('comment_ids');
        is_string($comment_ids) && $comment_ids = explode(',', $comment_ids);

        $comments = NewsComment::where(function ($query) use ($comment_ids) {
            if (count($comment_ids) > 0) {
                $query->whereIn('id', $comment_ids);
            }
        })->select(['id', 'created_at', 'comment_content', 'user_id', 'reply_to_user_id', 'comment_mark'])->orderBy('id', 'desc')->get();

        return response()->json(static::createJsonData([
            'status' => true,
            'data' => $comments,
        ]))->setStatusCode(200);
    }
}
