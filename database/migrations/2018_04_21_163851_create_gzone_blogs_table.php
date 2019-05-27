<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGzoneBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gzone_blogs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->comment('发说说的人');
            $table->string('post_content')->comment('发说说的人');
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
        Schema::dropIfExists('gzone_blogs');
    }
}
