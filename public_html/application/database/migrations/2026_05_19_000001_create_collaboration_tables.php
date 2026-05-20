<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollaborationTables extends Migration
{
    public function up()
    {
        Schema::create('collaboration_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('chat_key', 64)->unique();
            $table->unsignedBigInteger('created_by');
            $table->string('legacy_access_code', 32)->nullable()->index();
            $table->timestamps();
        });

        Schema::create('collaboration_session_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('user_id');
            $table->string('role', 16)->default('member');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();

            $table->unique(['session_id', 'user_id']);
            $table->unique('user_id');
            $table->foreign('session_id')->references('id')->on('collaboration_sessions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('collaboration_invites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('inviter_id');
            $table->unsignedBigInteger('invitee_id');
            $table->string('status', 16)->default('pending');
            $table->string('token', 64)->unique();
            $table->timestamps();

            $table->index(['session_id', 'invitee_id']);
            $table->foreign('session_id')->references('id')->on('collaboration_sessions')->onDelete('cascade');
            $table->foreign('inviter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('invitee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('collaboration_invites');
        Schema::dropIfExists('collaboration_session_members');
        Schema::dropIfExists('collaboration_sessions');
    }
}
