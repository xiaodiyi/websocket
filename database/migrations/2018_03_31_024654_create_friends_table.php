<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->comment('用户id');
            $table->integer('friend_id')->unsigned()->comment('朋友id');
            $table->integer('group_id')->unsigned()->comment('群组id');
            $table->string('group_name')->default('我的好友')->comment('好友分组');
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
        Schema::dropIfExists('friends');
    }
}
