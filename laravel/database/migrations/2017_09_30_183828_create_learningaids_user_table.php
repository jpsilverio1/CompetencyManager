<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearningaidsUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('learningaids_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('learningaids_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('learningaids_id')->references('id')->on('learningaids');
            $table->foreign('user_id')->references('id')->on('users');
            //$table->primary(['competency_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('learningaids_user');
    }
}
