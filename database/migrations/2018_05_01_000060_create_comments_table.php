<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('authentificated user or guest');
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->morphs('commentable');
            $table->text('content');
            $table->string('author_name')->nullable()->comment('if guest add comment');
            $table->string('author_email')->nullable()->comment('if guest add comment');
            $table->ipAddress('author_ip')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            $table->index('user_id');
            $table->index('parent_id');
            $table->index('commentable_id');
            $table->index('commentable_type');
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
