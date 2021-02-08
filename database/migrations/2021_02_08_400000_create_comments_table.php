<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            // Отношения и индексные поля.
            $table->morphs('commentable');
            $table->unsignedBigInteger('parent_id')
                ->nullable(); // if not replies
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Основное содержимое.
            $table->text('content');
            $table->string('name')->nullable(); // if guest add comment
            $table->string('email')->nullable(); // if guest add comment
            $table->ipAddress('user_ip')->nullable();

            // Поля с дополнительной информацией.
            $table->boolean('is_approved')->default(false);

            // Временные метки.
            $table->timestamps();

            // Создание индексов.
            $table->index('parent_id');
            $table->index('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
