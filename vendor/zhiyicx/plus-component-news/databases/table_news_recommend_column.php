<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$component_table_name = 'news_recommend';

if (! Schema::hasColumn($component_table_name, 'type')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('type')->comment('类型');
    });
}

if (! Schema::hasColumn($component_table_name, 'title')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('title')->nullable()->comment('推荐标题');
    });
}

if (! Schema::hasColumn($component_table_name, 'data')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('data')->nullable()->comment('管理数据');
    });
}

if (! Schema::hasColumn($component_table_name, 'cate_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('cate_id')->default(0)->comment('所属分类  0-推荐');
    });
}

if (! Schema::hasColumn($component_table_name, 'cover')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('cover')->comment('缩略图');
    });
}

if (! Schema::hasColumn($component_table_name, 'sort')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('sort')->default(0)->comment('排序');
    });
}
