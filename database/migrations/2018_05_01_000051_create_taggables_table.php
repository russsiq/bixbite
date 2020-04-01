<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaggablesTable extends Migration
{
    /**
     * Запустить миграции.
     * @return void
     */
    public function up()
    {
        Schema::create('taggables', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('tag_id');
            $table->morphs('taggable');

            $table->index('tag_id');
            $table->index('taggable_id');
            $table->index('taggable_type');
            $table->foreign('tag_id')->references('id')->on('tags')
                  ->onDelete('cascade');
        });
    }

    /**
     * Обратить миграции.
     * @return void
     */
    public function down()
    {
        Schema::table('taggables', function(Blueprint $table) {
            $table->dropForeign(['tag_id']);
            // $table->dropIndex(['tag_id']);
            // $table->dropIndex(['taggable_id']);
            // $table->dropIndex(['taggable_type']);
        });

        Schema::dropIfExists('taggables');
    }
}
