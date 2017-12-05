<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\API2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\FormRequest\API2\StoreFeedComment as CommentFormRequest;

class FeedCommentController extends Controller
{
    /**
     * List comments of the feed.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseFactoryContract $response, FeedModel $feed)
    {
        $limit = $request->query('limit', 20);
        $after = $request->query('after', false);

        $comments = $feed->comments()
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $response->json([
            'pinneds' => ! $after ? app()->call([$this, 'pinneds'], ['feed' => $feed]) : null,
            'comments' => $comments,
        ])->setStatusCode(200);
    }

    public function pinneds(Request $request, Carbon $dateTime, FeedModel $feed)
    {
        if ($request->query('after')) {
            return null;
        }

        return $feed->pinnedComments()
            ->where('expires_at', '>', $dateTime)
            ->get();
    }

    /**
     * Get a comment.
     *
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param mixed $feed
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(ResponseFactoryContract $response, $feed, CommentModel $comment)
    {
        unset($feed);

        return $response->json($comment, 200);
    }

    /**
     * destroy the comment.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(Request $request, ResponseFactoryContract $response, FeedModel $feed, CommentModel $comment)
    {
        $user = $request->user();
        if ($comment->user_id !== $user->id) {
            return $response->json(['message' => ['没有权限']], 403);
        }

        $feed->getConnection()->transaction(function () use ($user, $feed, $comment) {
            $feed->decrement('feed_comment_count', 1);
            $user->extra()->decrement('comments_count', 1);
            $comment->delete();
        });

        return $response->make('', 204);
    }

    /**
     * Send comment of the feed.
     *
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\FormRequest\API2\StoreFeedComment $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(CommentFormRequest $request,
                          ResponseFactoryContract $response,
                          FeedModel $feed,
                          CommentModel $comment)
    {
        $replyUser = intval($request->input('reply_user', 0));
        $body = $request->input('body');
        $user = $request->user();

        $comment->user_id = $user->id;
        $comment->reply_user = $replyUser;
        $comment->target_user = $feed->user_id;
        $comment->body = $body;

        $feed->getConnection()->transaction(function () use ($feed, $user, $comment) {
            $feed->comments()->save($comment);
            $feed->increment('feed_comment_count', 1);
            $user->extra()->firstOrCreate([])->increment('comments_count', 1);
        });

        $message = sprintf('%s 评论了你的动态', $user->name);
        $feed->user->sendNotifyMessage('feed:comment', $message, [
            'feed' => $feed,
            'user' => $user,
        ]);

        if ($replyUser) {
            $replyUser = $user->newQuery()->where('id', $replyUser)->first();
            $message = sprintf('%s 回复了您的评论', $user->name);
            $replyUser->sendNotifyMessage('feed:comment-reply', $message, [
                'feed' => $feed,
                'user' => $user,
            ]);
        }

        return $response->json([
            'message' => ['操作成功'],
            'comment' => $comment,
        ])->setStatusCode(201);
    }
}