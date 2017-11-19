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
        Schema::create('learning_aids_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('learning_aid_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('learning_aid_id')->references('id')->on('learning_aids');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamp('completed_on');
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
        Schema::dropIfExists('learning_aids_user');
    }
}
