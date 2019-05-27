<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendsGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends_group', function (Blueprint $table) {
            $table->integer('fid')->unsigned()->comment('父级id');
            $table->integer('group_id')->unsigned()->comment('好友分组');
            $table->string('group_name')->default('我的好友')->comment('好友分组id名称');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friends_group');
    }
}
