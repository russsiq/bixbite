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

            $table->increments('id');

            $table->boolean('is_approved')->nullable()->default(0);
            $table->integer('user_id')->unsigned()->nullable(); // if auth user add comment
            $table->integer('parent_id')->unsigned()->nullable(); // if not replies
            $table->morphs('commentable');

            $table->string('name')->nullable(); // if guest add comment
            $table->string('email')->nullable(); // if guest add comment
            $table->text('content');

            $table->index('user_id');
            $table->index('is_approved');
            $table->index('parent_id');
            $table->index('commentable_id');
            $table->index('commentable_type');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');

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
        Schema::table('comments', function(Blueprint $table) {
            $table->dropForeign(['user_id']);
            // $table->dropIndex(['user_id']);
            // $table->dropIndex(['is_approved']);
            // $table->dropIndex(['parent_id']);
            // $table->dropIndex(['commentable_id']);
            // $table->dropIndex(['commentable_type']);
        });
        Schema::dropIfExists('comments');
    }
}
