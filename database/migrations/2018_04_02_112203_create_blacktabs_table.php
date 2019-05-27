<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlacktabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blacktabs', function (Blueprint $table) {
            $table->integer('owner_id')->unsigned()->comment('黑名单所有者');
            $table->integer('put_id')->unsigned()->comment('被添加到黑名单的人');
            $table->string('type')->comment('类别');
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
        Schema::dropIfExists('blacktabs');
    }
}
