<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Запустить миграции.
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('api_token', 80)->nullable()->unique();
            $table->rememberToken()->unique();
            $table->string('name', 100)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('role', 20)->default('user');

            $table->string('avatar', 100)->nullable();
            $table->text('info')->length(500)->nullable();
            $table->string('where_from')->nullable();
            $table->ipAddress('last_ip')->nullable();

            $table->timestamps(); // registered_at and logined_at
            $table->timestamp('logined_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->index('name');
            $table->index('password');
        });
    }

    /**
     * Обратить миграции.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
