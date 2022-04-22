<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user');
            $table->string('avatar')->nullable();
            $table->text('info')->length(500)->nullable();
            $table->string('location')->nullable();
            $table->ipAddress('last_ip')->nullable();
            $table->timestamp('logined_at')->nullable();
            $table->timestamp('banned_until')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'avatar',
                'info',
                'location',
                'last_ip',
                'logined_at',
                'banned_until',
            ]);
        });
    }
};
