<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->string('content')->comment('消息内容');
            $table->integer('uid')->unsigned()->comment('接收人');
            $table->integer('from')->unsigned()->comment('发送人');
            $table->integer('from_group')->unsigned()->comment('来自哪个组');
            $table->integer('type')->unsigned()->comment('类型 私聊 群聊');
            $table->tinyInteger('read')->unsigned()->comment('是否已阅读');
            $table->tinyInteger('agree')->unsigned()->comment('是否已同意 0 未确定 1 已同意 2 已拒绝');
            $table->string('remark')->comment('附言');
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
        Schema::dropIfExists('messages');
    }
}
