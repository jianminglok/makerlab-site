<?php

use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Middleware as MusicMiddleware;

// Route::any('/component-example', 'ExampleApiController@example');
Route::get('/news/', 'NewsController@getNewsList');
// 资讯详情
Route::get('/news/{news}', 'NewsController@newsDetail')->where(['news' => '[0-9]+']);
// 评论列表
Route::get('/news/{news_id}/comments', 'NewsCommentController@getCommentList')->where(['news_id' => '[0-9]+']);
// 根据id获取评论
Route::get('/news/comments', 'NewsCommentController@searchComment');
Route::group([
    'middleware' => [
        'auth:api',
    ],
], function () {
    // 获取分类
    Route::get('/news/cates', 'NewsCateController@getNewsCatesList');

    // 搜索资讯
    Route::get('/news/search', 'NewsController@searchNewsList');

    // 收藏资讯列表
    Route::get('/news/collections', 'NewsController@getCollectionList');

    // 添加评论
    Route::post('/news/{news_id}/comment', 'NewsCommentController@addComment')
        ->middleware('role-permissions:news-comment,你没有评论资讯的权限')
        ->middleware(MusicMiddleware\VerifyCommentContent::class); // 验证评论内容

    // 删除评论
    Route::delete('/news/{news_id}/comment/{comment_id}', 'NewsCommentController@delComment');

    // 收藏
    Route::post('/news/{news_id}/collection', 'NewsCollectionController@addNewsCollection')
        ->middleware('role-permissions:news-collection,你没有收藏资讯的权限');

    // 取消收藏
    Route::delete('/news/{news_id}/collection', 'NewsCollectionController@delNewsCollection');

    // 订阅
    Route::post('/news/cates/follow', 'NewsCateFollowController@catesFollow');

    // 点赞
    Route::post('/news/{news}/digg', 'NewsDiggController@like')
        ->middleware('role-permissions:news-digg,你没有点赞资讯的权限');

    // 取消点赞
    Route::delete('/news/{news}/digg', 'NewsDiggController@cancel');
});
