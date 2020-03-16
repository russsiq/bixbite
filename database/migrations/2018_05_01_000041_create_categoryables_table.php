<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categoryables', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->morphs('categoryable');

            $table->index('category_id');
            $table->index('categoryable_id');
            $table->index('categoryable_type');
            $table->foreign('category_id')->references('id')->on('categories')
                  ->onDelete('cascade');

            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categoryables', function(Blueprint $table) {
            $table->dropForeign(['category_id']);
            // $table->dropIndex(['tag_id']);
            // $table->dropIndex(['taggable_id']);
            // $table->dropIndex(['taggable_type']);
        });

        Schema::dropIfExists('categoryables');
    }
}
