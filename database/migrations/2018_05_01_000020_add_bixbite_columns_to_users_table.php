<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBixbiteColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('user');
            $table->string('avatar', 100)->nullable();
            $table->text('info')->length(500)->nullable();
            $table->string('where_from')->nullable();
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
                'where_from',
                'last_ip',
                'logined_at',
                'banned_until',
            ]);
        });
    }
}
