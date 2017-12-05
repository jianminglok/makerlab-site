<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$component_table_name = 'news_cates';

if (! Schema::hasColumn($component_table_name, 'name')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('name')->comment('分类名称');
    });
}

if (! Schema::hasColumn($component_table_name, 'rank')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('rank')->default(0)->comment('排序');
    });
}
