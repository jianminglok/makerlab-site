<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCateFollow;

class NewsCateFollowController extends Controller
{
    /**
     * Follow news cate.
     * @param  $follows [分类字符串]
     * @return mixed 返回结果
     */
    public function catesFollow(Request $request)
    {
        $uid = $request->user()->id;
        $follows = $request->input('follows', null);

        $cate_follow = new NewsCateFollow();
        $follow = $cate_follow->where('user_id', $uid)->first();
        if ($follow) {
            $cate_follow->where('user_id', $uid)->update(['follows' => $follows]);
        } else {
            $cate_follow->insert(['user_id'=> $uid, 'follows' => $follows]);
        }

        return response()->json(static::createJsonData([
            'status' => true,
            'code' => 0,
            'message' => '订阅成功',
        ]))->setStatusCode(201);
    }
}
