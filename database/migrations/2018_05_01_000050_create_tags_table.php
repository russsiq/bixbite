<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Запустить миграции.
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();

            // Timestamps.
            $table->timestamps();
        });

    }

    /**
     * Обратить миграции.
     * @return void
     */
    public function down()
    {
        // Schema::table('tags', function(Blueprint $table) {
        //     $table->dropUnique(['title']);
        // });
        Schema::dropIfExists('tags');
    }
}
