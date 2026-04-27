<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_packages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique()->nullable();
            $table->double('price', 8, 2)->nullable();
            $table->string('currency')->nullable();
            $table->integer('discount')->nullable();
            $table->text('details')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('session_packages');
    }
}
