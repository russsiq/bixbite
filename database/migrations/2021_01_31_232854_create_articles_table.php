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
                ->onDelete('cascade');

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
            $table->boolean('on_mainpage')->default(1);
            $table->boolean('is_favorite')->default(0);
            $table->boolean('is_pinned')->default(0);

            // Счетчики.
            $table->unsignedInteger('views')->default(0);

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
