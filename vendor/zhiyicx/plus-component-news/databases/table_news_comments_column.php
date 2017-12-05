<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$component_table_name = 'news_comments';

if (! Schema::hasColumn($component_table_name, 'comment_content')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('comment_content')->comment('评论内容');
    });
}

if (! Schema::hasColumn($component_table_name, 'user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('user_id')->comment('用户id');
    });
}

if (! Schema::hasColumn($component_table_name, 'reply_to_user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('reply_to_user_id')->default(0)->comment('被回复的用户id');
    });
}

if (! Schema::hasColumn($component_table_name, 'news_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('news_id')->default(0)->comment('新闻id');
    });
}

if (! Schema::hasColumn($component_table_name, 'comment_mark')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->bigInteger('comment_mark')->default(null)->comment('移动端存储标记');
    });
}
