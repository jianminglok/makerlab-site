<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$component_table_name = 'news_cates_links';

if (! Schema::hasColumn($component_table_name, 'news_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('news_id')->comment('资讯id');
    });
}

if (! Schema::hasColumn($component_table_name, 'cate_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('cate_id')->comment('分类id');
    });
}
