<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Запустить миграции.
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            // Relation and other indexed keys.
            $table->unsignedBigInteger('user_id')->nullable(); // if auth user add comment
            $table->unsignedBigInteger('parent_id')->nullable(); // if not replies
            $table->morphs('commentable');

            // Main content.
            $table->text('content');
            $table->string('name')->nullable(); // if guest add comment
            $table->string('email')->nullable(); // if guest add comment
            $table->ipAddress('user_ip')->nullable();

            // Aditional.
            $table->boolean('is_approved')->nullable()->default(0);

            // Timestamps.
            $table->timestamps();

            // Make indexes.
            $table->index('is_approved');
            $table->index('parent_id');
            $table->index('user_id');
            $table->index('commentable_id');
            $table->index('commentable_type');
            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('SET NULL');
        });
    }

    /**
     * Обратить миграции.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
