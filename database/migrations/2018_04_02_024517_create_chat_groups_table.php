<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_num')->unsigned()->comment('群号');
            $table->string('group_name')->comment('群组名');
            $table->string('group_avatar')->comment('群组头像');
            $table->string('group_profile')->comment('群组简介');
            $table->integer('owner_id')->unsigned()->comment('群组所有者ID');
            $table->tinyInteger('status')->unsigned()->comment('1审核通过 0待审核 -1审核不通过');
            $table->tinyInteger('setting')->unsigned()->comment('1直接加群 0需要验证 -1不允许加群');
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
        Schema::dropIfExists('chat_groups');
    }
}
