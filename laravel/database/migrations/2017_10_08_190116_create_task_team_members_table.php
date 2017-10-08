<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTeamMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('task_teams', function (Blueprint $table) {
            $table->integer('task_team_member_id')->unsigned();
            $table->integer('task_id')->unsigned();
            $table->foreign('task_team_member_id')->references('id')->on('task_team_members');
            $table->foreign('task_id')->references('id')->on('tasks');

            //$table->primary(['user_id', 'team_id']);
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
        Schema::dropIfExists('task_teams');
    }
}
