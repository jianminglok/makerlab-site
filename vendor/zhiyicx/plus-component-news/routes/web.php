<?php

Route::get('/admin', 'HomeController@index')
  ->middleware('auth:web')
  ->name('news:admin');

//Route::any('/component-example/admin', 'ExampleWebController@admin')
//    ->middleware('auth:web')
//    ->name('example.admin');
//    // ->middleware('role:admin');
