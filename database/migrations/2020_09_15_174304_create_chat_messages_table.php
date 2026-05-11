<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->default(1);
            //$table->foreign('admin_id')->references('id')->on('admins');
            $table->unsignedBigInteger('user_id');
            //$table->foreign('user_id')->references('id')->on('users');
            $table->text('data')->nullable();
            $table->tinyInteger('is_read')->default(0);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
}
