<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('role', 100)->default('user');

            $table->string('avatar', 100)->nullable();
            $table->text('info')->length(500)->nullable();
            $table->string('where_from')->nullable();
            $table->rememberToken();
            $table->ipAddress('last_ip')->nullable();

            $table->index('name');

            $table->timestamps();
            $table->timestamp('logined_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
