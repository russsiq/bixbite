<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('image_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_completed')->default(false);

            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('files')->onDelete('set null');

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
        Schema::table('notes', function(Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('notes');
    }
}
