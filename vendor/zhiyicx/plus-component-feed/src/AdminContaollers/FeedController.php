<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\AdminContaollers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;

class FeedController extends Controller
{
    /**
     * Get feeds.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $model
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, Feed $model)
    {
        $limit = (int) $request->query('limit', 20);
        $before = (int) $request->query('before', 0);

        $feeds = $model->with([
                'user',
                'paidNode',
                'images',
                'images.paidNode',
            ])
            ->when($before, function ($query) use ($before) {
                return $query->where('id', '<', $before);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($feeds)->setStatusCode(200);
    }

    /**
     * Get deleted feeds.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $model
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function deleted(Request $request, Feed $model)
    {
        $limit = (int) $request->query('limit', 20);
        $before = (int) $request->query('before', 0);

        $feeds = $model->onlyTrashed()
            ->with([
                'user',
                'paidNode',
                'images',
                'images.paidNode',
            ])
            ->when($before, function ($query) use ($before) {
                return $query->where('id', '<', $before);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($feeds)->setStatusCode(200);
    }

    /**
     * 永久删除.
     */
    public function delete(Request $request)
    {
        $feed = $request->query('feed');
        ! $feed && abort(400, '动态传递错误');
        Feed::withTrashed()->find($feed)->forceDelete();

        return response()->json()->setStatusCode(204);
    }

    /**
     * 恢复.
     */
    public function restore(Request $request)
    {
        $feed = $request->query('feed');
        ! $feed && abort(400, '动态传递错误');

        Feed::withTrashed()->find($feed)->restore();

        return response()->json(['message' => '恢复成功'])->setStatusCode(201);
    }

    /**
     * Delete feed.
     *
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(CacheContract $cache, Feed $feed)
    {
        $feed->delete();
        $cache->forget(sprintf('feed:%s', $feed->id));

        return response(null, 204);
    }
}
