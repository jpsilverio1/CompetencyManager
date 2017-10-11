<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartedAtAndFinishedAtColumnsAtTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('tasks', function($table) {
        $table->timestamp('started_at')->nullable();
        $table->timestamp('ended_at')->nullable();
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
        Schema::table('tasks', function($table) {
            $table->dropColumn('started_at');
            $table->dropColumn('ended_at');
        });
    }
}
