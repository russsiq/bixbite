<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowsUsersTable extends Migration
{
    /**
     * Запустить миграции.
     * @return void
     */
    public function up()
    {
        Schema::create('follows_users', function (Blueprint $table) {
            $table->unsignedBigInteger('follow_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')
                ->references('id')->on('users')->onDelete('cascade');
            $table->foreign('follow_id')
                ->references('id')->on('users')->onDelete('cascade');

            $table->primary(['user_id', 'follow_id']);

            $table->timestamps();
        });
    }

    /**
     * Обратить миграции.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('follows_users');
    }
}
