<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCateFollow;

class NewsCateController extends Controller
{
    /**
     * 分类列表.
     * @param  $cate_id [分类ID]
     * @return mixed 返回结果
     */
    public function getNewsCatesList(Request $request)
    {
        $user_id = $request->user()->id;

        // 所有分类
        $cates = NewsCate::orderBy('rank', 'desc')->select('id', 'name')->get();

        // 我订阅的分类
        $follows = NewsCateFollow::where('user_id', $user_id)->first();
        if (! $follows) {
            $follows_array = NewsCate::orderBy('rank', 'desc')->take(5)->pluck('id')->toArray();
        } else {
            $follows_array = explode(',', $follows->follows);
        }
        // 更多分类
        $datas = ['my_cates' => [], 'more_cates' => []];
        foreach ($cates as $cate) {
            if (in_array($cate['id'], $follows_array)) {
                $datas['my_cates'][] = $cate;
            } else {
                $datas['more_cates'][] = $cate;
            }
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas,
        ]))->setStatusCode(200);
    }
}
