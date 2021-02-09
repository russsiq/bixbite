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

            // Отношения и индексные поля.
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->unsignedBigInteger('position')->default(0);

            // Основное содержимое.
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('alt_url')->nullable();
            $table->text('info')->nullable();

            // SEO-поля для метатегов.
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->enum('meta_robots', ['all', 'noindex', 'nofollow', 'none'])
                ->default('all');

            // Поля с дополнительной информацией.
            $table->boolean('show_in_menu')->default(true);
            $table->unsignedTinyInteger('paginate')->default(15);
            $table->string('template')->nullable();
            $table->string('order_by')->default('id');
            $table->enum('direction', ['desc', 'asc'])->default('desc');

            // Временные метки.
            $table->timestamps();

            // Создание индексов.
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
