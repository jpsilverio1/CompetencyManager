<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearningaidsCompetenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('learningaids_competencies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('competency_id')->unsigned();
            $table->integer('learningaids_id')->unsigned();
            $table->foreign('competency_id')->references('id')->on('competencies');
            $table->foreign('learningaids_id')->references('id')->on('learningaids');
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
        Schema::dropIfExists('learningaids_competencies');
    }
}
