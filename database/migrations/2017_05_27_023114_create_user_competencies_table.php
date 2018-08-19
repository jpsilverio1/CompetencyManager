<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCompetenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_competencies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('competency_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('competency_level');
            $table->foreign('competency_id')->references('id')->on('competencies');
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
        Schema::dropIfExists('user_competencies');
    }
}
