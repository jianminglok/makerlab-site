<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\AdminContaollers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Support\Configuration;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class PayControlController extends Controller
{
    protected $config;

    // 获取当前付费配置
    public function getCurrentStatus(ConfigRepository $config)
    {
        return response()->json([
            'open' => $config->get('feed.paycontrol') ?? false,
        ])
        ->setStatusCode(200);
    }

    /**
     * 更新动态付费状态
     */
    public function updateStatus(Request $request, Configuration $config)
    {
        $open = $request->input('open');

        ! isset($open) && abort(400, '请设置付费状态');

        $config->set([
            'feed.paycontrol' => $open,
        ]);

        return response()->json(['message' => '设置成功'])->setStatusCode(201);
    }
}
