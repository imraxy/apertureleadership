<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AllowMultipleCollaborationSessionsPerUser extends Migration
{
    public function up()
    {
        Schema::table('collaboration_session_members', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('collaboration_session_members', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('collaboration_session_members', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('collaboration_session_members', function (Blueprint $table) {
            $table->unique('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
