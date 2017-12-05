<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$component_table_name = 'news_diggs';

if (! Schema::hasColumn($component_table_name, 'user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('user_id')->comment('用户id');
    });
}

if (! Schema::hasColumn($component_table_name, 'news_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('news_id')->comment('新闻id');
    });
}
