<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEndorsementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_endorsements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('competency_id')->unsigned();
            $table->integer('endorser_id')->unsigned();
            $table->integer('endorsed_id')->unsigned();
            $table->string('competency_level');
            $table->foreign('competency_id')->references('id')->on('competencies');
            $table->foreign('endorser_id')->references('id')->on('users');
            $table->foreign('endorsed_id')->references('id')->on('users');
            //$table->primary(['competency_id', 'endorsed_id', 'endorser_id']);
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
        Schema::dropIfExists('user_endorsements');
    }
}
