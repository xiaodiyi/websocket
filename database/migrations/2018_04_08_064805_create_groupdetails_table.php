<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('组员ID');
            $table->integer('user_number')->unsigned()->comment('组员账号');
            $table->tinyInteger('power')->unsigned()->comment('群员权限 4普通成员 6管理员 9群主');
            $table->integer('group_id')->comment('群ID');
            $table->integer('group_number')->unsigned()->comment('群号');
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
        Schema::dropIfExists('group_details');
    }
}
