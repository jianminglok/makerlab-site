<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\AdminControllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\JWTCache;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentNews\view;

class HomeController extends Controller
{
    use AuthenticatesUsers {
        login as traitLogin;
    }

    public function show(Request $request)
    {
        if (! $request->user()) {
            return redirect(route('admin'), 302);
        }

        // token
        $token = JWTCache::where('user_id', $request->user()->id)
            ->where('status', 0)
            ->value('value');

        $token = 'Bearer '.trim($token);

        return view('admin', [
            'token' => $token,
            'base_url' => route('news:admin'),
            'csrf_token' => csrf_token(),
            'api'        => url('api/v1'),
        ]);
    }

    protected function menus()
    {
        $components = config('component');
        $menus = [];

        foreach ($components as $component => $info) {
            $info = (array) $info;
            $installer = array_get($info, 'installer');
            $installed = array_get($info, 'installed', false);

            if (! $installed || ! $installer) {
                continue;
            }

            $componentInfo = app($installer)->getComponentInfo();

            if (! $componentInfo) {
                continue;
            }

            $menus[$component] = [
                'name'  => $componentInfo->getName(),
                'icon'  => $componentInfo->getIcon(),
                'logo'  => $componentInfo->getLogo(),
                'admin' => $componentInfo->getAdminEntry(),
            ];
        }

        return $menus;
    }
}
