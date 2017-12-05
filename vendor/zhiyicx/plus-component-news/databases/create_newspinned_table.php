<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// use Illuminate\Database\Migrations\Migration;

// class CreateNewspinnedTable extends Migration
// {
//     /**
//      * Run the migrations.
//      *
//      * @return void
//      */
//     public function up()
//     {
//         Schema::create('news_pinneds', function (Blueprint $table) {
//             $table->increments('id');
//             $table->string('channel')->comment('频道: 资讯或资讯评论');
//             $table->integer('raw')->unsigned()->nullable()->default(0)->comment('如果存在则为评论id');
//             $table->integer('target')->unsigned()->comment('资讯ID');
//             $table->integer('user_id')->unsigned()->comment('申请用户');
//             $table->integer('target_user')->unsigned()->nullable()->default(null)->comment('资讯作者');
//             $table->integer('amount')->unsigned()->comment('金额');
//             $table->integer('day')->comment('固定期限，单位 天');
//             $table->unsignedInteger('cate_id')->default(0)->comment('如果存在则为资讯置顶所属分类');
//             $table->timestamp('expires_at')->nullable()->comment('到期时间，固定后设置该时间');
//             $table->timestamps();
//         });
//     }

//     /**
//      * Reverse the migrations.
//      *
//      * @return void
//      */
//     public function down()
//     {
//         Schema::dropIfExits('news_pinneds');
//     }
// }

$component_table_name = 'news_pinneds';

if (! Schema::hasColumn($component_table_name, 'channel')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('channel')->comment('频道: 资讯或资讯评论');
    });
}

if (! Schema::hasColumn($component_table_name, 'raw')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->unsignedInteger('raw')->nullable()->default(0)->comment('如果存在则为评论id');
    });
}

if (! Schema::hasColumn($component_table_name, 'target')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->unsignedInteger('target')->comment('资讯id');
    });
}

if (! Schema::hasColumn($component_table_name, 'user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->unsignedInteger('user_id')->comment('申请者id');
    });
}

if (! Schema::hasColumn($component_table_name, 'state')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->unsignedTinyInteger('state')->default(0)->comment('审核状态0: 待审核, 1审核通过, 2被拒');
    });
}

if (! Schema::hasColumn($component_table_name, 'target_user')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->unsignedInteger('target_user')->unllable()->default(0)->comment('资讯作者');
    });
}

if (! Schema::hasColumn($component_table_name, 'amount')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->unsignedInteger('amount')->comment('金额');
    });
}

if (! Schema::hasColumn($component_table_name, 'day')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->unsignedInteger('day')->comment('天数');
    });
}

if (! Schema::hasColumn($component_table_name, 'cate_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->unsignedInteger('cate_id')->nullable()->default(null)->comment('资讯置顶所属分类');
    });
}

if (! Schema::hasColumn($component_table_name, 'expires_at')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->timestamp('expires_at')->nullable()->comment('到期时间，固定后设置该时间');
    });
}
