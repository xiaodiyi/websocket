<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->unique()->comment('极号');
            $table->string('user_name',155)->nullable()->comment('昵称');
            $table->string('pwd')->nullable()->comment('密码');
            $table->string('sign')->nullable();
            $table->string('avatar')->nullable();

            $table->string('birth')->nullable()->comment('生日');
            $table->string('tel')->nullable()->comment('电话');
            $table->string('email')->nullable()->comment('邮箱');
            $table->string('constellation')->nullable()->comment('星座');
            $table->integer('QQ')->nullable()->comment('QQ');
            $table->string('blood_type')->nullable()->comment('血型');

            $table->string('city')->comment('城市');
            $table->tinyInteger('age')->default(18)->comment('年龄');
            $table->tinyInteger('sex')->default(1)->comment('性别 1男 -1女 0保密');
            $table->string('area')->default('北京市/北京市/东城区')->comment('所在区域描述');
            $table->tinyInteger('status')->default(0)->unsigned()->comment('0下线 1在线');
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
        Schema::dropIfExists('users');
    }
}
