<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\AdminContaollers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Zhiyi\Plus\Repository\WalletRatio;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;

class HomeController extends Controller
{
    /**
     * feed management background entry.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(WalletRatio $walletRatioRepository)
    {
        return view('feed:view::admin', [
            'base_url' => route('feed:admin'),
            'csrf_token' => csrf_token(),
            'wallet_ratio' => $walletRatioRepository->get(),
        ]);
    }

    /**
     * 获取分享统计信息.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function statistics()
    {
        $expiresAt = Carbon::now()->addDay(1);

        $feedsCount = Cache::remember('feeds-count', $expiresAt, function () {
            return Feed::count();
        });

        $commentsCount = Cache::remember('feeds-comments-count', $expiresAt, function () {
            return 0;
        });

        return response()->json([
            'feedsCount' => $feedsCount,
            'commentsCount' => $commentsCount,
        ])->setStatusCode(200);
    }
}
