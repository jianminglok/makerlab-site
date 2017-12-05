<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$component_table_name = 'news';

if (! Schema::hasColumn($component_table_name, 'title')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('title')->comment('新闻标题');
    });
}

if (! Schema::hasColumn($component_table_name, 'storage')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('storage')->nullable()->default(0)->comment('缩略图id');
    });
}

if (! Schema::hasColumn($component_table_name, 'content')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->longtext('content')->comment('新闻内容');
    });
}

if (! Schema::hasColumn($component_table_name, 'digg_count')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('digg_count')->default(0)->comment('点赞数');
    });
}

if (! Schema::hasColumn($component_table_name, 'comment_count')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('comment_count')->default(0)->comment('评论数');
    });
}

if (! Schema::hasColumn($component_table_name, 'hits')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('hits')->default(0)->comment('点击数');
    });
}

if (! Schema::hasColumn($component_table_name, 'from')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('from')->nullable()->comment('来源');
    });
}

if (! Schema::hasColumn($component_table_name, 'is_recommend')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->tinyInteger('is_recommend')->default(0)->comment('是否推荐');
    });
}
if (! Schema::hasColumn($component_table_name, 'subject')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->text('subject')->comment('副标题');
    });
}

if (! Schema::hasColumn($component_table_name, 'author')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('author', 100)->comment('文章作者');
    });
}

if (! Schema::hasColumn($component_table_name, 'audit_status')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->tinyInteger('audit_status')->default(0)->comment('文章状态 0-正常 1-待审核 2-草稿 3-驳回 4-删除 5-退款中');
    });
}

// 投稿次数统计.
if (! Schema::hasColumn($component_table_name, 'audit_count')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->tinyInteger('audit_count')->nullable()->default(0)->comment('审核次数统计');
    });
}

// 创建新闻用户
if (! Schema::hasColumn($component_table_name, 'user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('user_id')->unsigned()->comment('用户ID');
    });
}

// 新闻分类
if (! Schema::hasColumn($component_table_name, 'cate_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('cate_id')->unsigned()->comment('分类');
    });
}

// 投稿款项
if (! Schema::hasColumn($component_table_name, 'contribute_amount')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('contribute_amount')->unsigned()->nullable()->default(0)->comment('投稿金额');
    });
}
