<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@show')->name('feed:admin');
Route::get('/statistics', 'HomeController@statistics');
Route::get('/feeds', 'FeedController@index');
Route::delete('/feeds/{feed}', 'FeedController@destroy')
    ->where('feed', '[0-9]+');
Route::patch('/feeds/{feed}/review', 'FeedController@reviewFeed');
Route::get('/comments', 'CommentController@show');
Route::delete('/comments/{comment}', 'CommentController@delete');

// 获取动态收费配置
Route::get('/paycontrol', 'PayControlController@getCurrentStatus');

// 更新动态收费配置
Route::patch('/paycontrol', 'PayControlController@updateStatus');

// 被软删除的动态
Route::get('/deleted-feeds', 'FeedController@deleted');
Route::get('/deleted-comments', 'CommentController@deleted');

// 恢复
Route::patch('/feeds', 'FeedController@restore');

// 真删除
Route::delete('/feeds', 'FeedController@delete');

// File
Route::get('/files/{file}', 'FileController@show');
