<?php

use Illuminate\Support\Facades\Route;
use SlimKit\PlusInstaller\Web\Middleware as WebMiddleware;
use SlimKit\PlusInstaller\Web\Controllers as WebControllers;
use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;

/*
|--------------------------------------------------------------------------
| The app routes.
|--------------------------------------------------------------------------
|
| Define the root definitions for all routes here.
|
*/

Route::group(['middleware' => 'web', 'prefix' => 'installer'], function (RouteRegisterContract $route) {
    $route->view('/', 'plus-installer::installer', [
        'logo' => asset('/assets/installer/logo.png'),
        'version' => Zhiyi\Plus\VERSION,
    ]);

    // Verify install password.
    $route->post('/password', WebControllers\InstallController::class.'@verifyPassword');

    // Get LICENSE.
    $route->get('/license', WebControllers\InstallController::class.'@license');
    $route->group(['middleware' => WebMiddleware\VerifyInstallationPassword::class], function (RouteRegisterContract $route) {
        $route->post('/check', WebControllers\InstallController::class.'@check');
        $route->post('/info', WebControllers\InstallController::class.'@getInfo');
        $route->put('/info', WebControllers\InstallController::class.'@setInfo');
    });
});
