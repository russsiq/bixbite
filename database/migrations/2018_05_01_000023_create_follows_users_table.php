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
            $table->primary(['user_id', 'follow_id']);
            $table->foreignId('user_id')->constrained()
                  ->onDelete('cascade');
            $table->foreignId('follow_id')->constrained('users')
                  ->onDelete('cascade');
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
