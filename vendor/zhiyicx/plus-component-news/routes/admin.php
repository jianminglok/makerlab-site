<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@show')->name('news:admin');

Route::prefix('news')->group(function () {
    Route::get('/', 'NewsController@getNewsList');
    Route::get('/recycle', 'NewsController@getRecycleList');
    Route::get('/info/{news_id}', 'NewsController@getNews')->where(['news_id'=>'[0-9]+']);
    Route::get('/cates', 'NewsCateController@getCateList');
    Route::post('/audit/{news_id}', 'NewsController@auditNews')->where(['news_id'=>'[0-9]+']);
    Route::get('/recommend/{news_id}', 'NewsController@recommend')->where(['news_id'=>'[0-9]+']);
    Route::post('/handle_news', 'NewsController@doSaveNews');
    Route::post('/handle_cate', 'NewsCateController@doNewsCate');
    Route::post('/handle_rec', 'NewsRecommendController@doRecommendInfo');
    Route::delete('/del/{news_id}/news', 'NewsController@delNews')->where(['news_id'=>'[0-9]+']);
    Route::delete('/del/{cate_id}/cate', 'NewsCateController@delCate')->where(['cate_id'=>'[0-9]+']);
    Route::delete('/del/{rid}/recommend', 'NewsRecommendController@delNewsRecommend')->where(['cate_id'=>'[0-9]+']);
    Route::get('/recommend', 'NewsRecommendController@getRecommendList');
    Route::get('/rec/{rid}', 'NewsRecommendController@getNewsRecommend')->where(['rid'=>'[0-9]+']);
});
