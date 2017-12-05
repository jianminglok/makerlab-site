<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\API2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\WalletCharge;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;

class RewardController extends Controller
{
    /**
     * 打赏一条资讯.
     *
     * @author bs<414606094@qq.com>
     * @param  Request      $request
     * @param  Feed         $feed
     * @param  WalletCharge $charge
     * @return mix
     */
    public function reward(Request $request, Feed $feed, WalletCharge $charge)
    {
        $amount = $request->input('amount');
        if (! $amount || $amount < 0) {
            return response()->json([
                'amount' => ['请输入正确的打赏金额'],
            ], 422);
        }
        $user = $request->user();
        $user->load('wallet');
        $feed->load('user');
        $current_user = $feed->user;

        if (! $user->wallet || $user->wallet->balance < $amount) {
            return response()->json([
                'message' => ['余额不足'],
            ], 403);
        }

        $user->getConnection()->transaction(function () use ($user, $feed, $charge, $current_user, $amount) {
            // 扣除操作用户余额
            $user->wallet()->decrement('balance', $amount);

            $feed_title = str_limit($feed->feed_content, 100, '...');

            // 扣费记录
            $userCharge = clone $charge;
            $userCharge->channel = 'user';
            $userCharge->account = $current_user->id;
            $userCharge->subject = '打赏动态';
            $userCharge->action = 0;
            $userCharge->amount = $amount;
            $userCharge->body = sprintf('打赏动态《%s》', $feed_title);
            $userCharge->status = 1;
            $user->walletCharges()->save($userCharge);

            // 添加打赏通知
            $user->sendNotifyMessage('feed:reward', sprintf('你对动态《%s》进行%s元打赏', $feed_title, $amount / 100), [
                    'feed' => $feed,
                    'user' => $current_user,
                ]);

            if ($current_user->wallet) {
                // 增加对应用户余额
                $current_user->wallet()->increment('balance', $amount);

                $charge->user_id = $current_user->id;
                $charge->channel = 'user';
                $charge->account = $user->id;
                $charge->subject = '动态被打赏';
                $charge->action = 1;
                $charge->amount = $amount;
                $charge->body = sprintf('动态《%s》被打赏', $feed_title);
                $charge->status = 1;
                $charge->save();

                // 添加被打赏通知
                $current_user->sendNotifyMessage('feed:reward', sprintf('你的资讯《%s》被%s打赏%s元', $feed_title, $user->name, $amount / 100), [
                    'feed' => $feed,
                    'user' => $user,
                ]);
            }

            // 打赏记录
            $feed->reward($user, $amount);
        });

        return response()->json([
            'message' => ['打赏成功'],
        ], 201);
    }

    /**
     * 一条动态的打赏列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Feed    $feed
     * @return mix
     */
    public function index(Request $request, Feed $feed)
    {
        $limit = max(1, min(30, $request->query('limit', 20)));
        $since = $request->query('since', 0);
        $order = in_array($order = $request->query('order', 'desc'), ['asc', 'desc']) ? $order : 'desc';
        $order_type = in_array($order_type = $request->query('order_type'), ['amount', 'date']) ? $order_type : 'date';
        $fieldMap = [
            'date' => 'id',
            'amount' => 'amount',
        ];
        $rewardables = $feed->rewards()
            ->with('user')
            ->when($since, function ($query) use ($since, $order, $order_type, $fieldMap) {
                return $query->where($fieldMap[$order_type], $order === 'asc' ? '>' : '<', $since);
            })
            ->limit($limit)
            ->orderBy($fieldMap[$order_type], $order)
            ->get();

        return response()->json($rewardables, 200);
    }
}