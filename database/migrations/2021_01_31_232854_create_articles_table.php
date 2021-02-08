<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            // Отношения и индексные поля.
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Основное содержимое.
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('teaser')->nullable();
            $table->text('content')->nullable();

            // SEO-поля для метатегов.
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->enum('meta_robots', ['all', 'noindex', 'nofollow', 'none'])
                ->default('all');

            // Поля с дополнительной информацией.
            $table->boolean('on_mainpage')->default(true);
            $table->boolean('is_favorite')->default(false);
            $table->boolean('is_pinned')->default(false);

            // Счетчики.
            $table->unsignedInteger('views')->default(false);

            // Временные метки.
            $table->timestamps();

            // Создание индексов.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
