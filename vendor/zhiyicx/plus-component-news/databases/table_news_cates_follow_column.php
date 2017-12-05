<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$component_table_name = 'news_cates_follow';

if (! Schema::hasColumn($component_table_name, 'user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('user_id')->comment('用户id');
    });
}

if (! Schema::hasColumn($component_table_name, 'follows')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('follows')->nullable()->comment('关注的分类');
    });
}
