<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('image_id')->unsigned()->nullable();
            $table->unsignedInteger('parent_id')->default(0);
            $table->unsignedInteger('position')->length(10)->nullable();

            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('alt_url')->nullable();
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->text('info')->length(500)->nullable();

            $table->boolean('show_in_menu')->nullable()->default('1');

            $table->unsignedTinyInteger('paginate')->nullable();
            $table->string('order_by', 30)->nullable();
            $table->enum('direction', ['desc', 'asc'])->default('desc');

            $table->string('template', 20)->nullable();

            $table->index('parent_id');
            $table->index('position');
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
        Schema::table('categories', function(Blueprint $table) {
            $table->dropForeign(['image_id']);
        });

        Schema::dropIfExists('categories');
    }
}
