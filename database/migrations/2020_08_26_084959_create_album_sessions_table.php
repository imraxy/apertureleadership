<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('album_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('album_category_id');
            $table->foreign('album_category_id')->references('id')->on('album_categories')->onDelete('cascade');
            $table->string('title')->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('permalink')->unique()->nullable();
            $table->string('video_link')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('featured_image')->nullable();
            $table->text('gallery_images')->nullable();
            $table->integer('no_of_views')->nullable();
            $table->boolean('status')->default(true);
            $table->string('slug')->nullable();
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
        Schema::dropIfExists('album_sessions');
    }
}
