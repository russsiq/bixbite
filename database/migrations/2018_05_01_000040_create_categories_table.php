<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->foreignId('image_id')->nullable()->constrained('attachments')->onDelete('set null');
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->unsignedBigInteger('position')->default(1);
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('alt_url')->nullable();
            $table->text('info')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_robots')->default('all');
            $table->boolean('show_in_menu')->default(true);
            $table->string('order_by')->default('id');
            $table->enum('direction', ['desc', 'asc'])->default('desc');
            $table->unsignedTinyInteger('paginate')->default(8);
            $table->string('template')->nullable();
            $table->timestamps();

            $table->index('parent_id');
            $table->index('position');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
