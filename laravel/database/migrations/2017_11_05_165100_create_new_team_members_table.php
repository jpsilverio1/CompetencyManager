<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewTeamMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('team_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_id_fk')->unsigned();
            $table->integer('member_id_fk')->unsigned();
            $table->foreign('team_id_fk')->references('id')->on('teams');
            $table->foreign('member_id_fk')->references('id')->on('users');
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
        Schema::dropIfExists('team_members');
    }
}
