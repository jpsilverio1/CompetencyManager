<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTeamsAndTeamMembersToGroupsAndGroupMembers extends Migration
{
    public function up()
    {
        Schema::dropIfExists('team_competencies');
        Schema::table('team_members', function (Blueprint $table) {
            $table->renameColumn('team_id', 'group_id');

        });
        Schema::rename('team_members','group_members');
        Schema::rename('teams','groups');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('team_competencies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('competency_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->foreign('competency_id')->references('id')->on('competencies');
            $table->foreign('team_id')->references('id')->on('teams');
            //$table->primary(['competency_id', 'team_id']);
        });
        Schema::table('team_members', function (Blueprint $table) {
            $table->renameColumn('group_id', 'team_id');

        });
        Schema::rename('group_members','team_members');
        Schema::rename('groups','teams');
    }
}
