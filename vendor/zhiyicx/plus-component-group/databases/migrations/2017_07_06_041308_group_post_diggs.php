<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GroupPostDiggs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_post_diggs', function(Blueprint $table) {
            $table->increments('id')->comment('primary key');
            $table->unsignedInteger('post_id')->index()->comment('for post_id');
            $table->unsignedInteger('user_id')->index()->comment('who digged this post');
            $table->timestamps();
            $table->index(['post_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_post_diggs');
    }
}
