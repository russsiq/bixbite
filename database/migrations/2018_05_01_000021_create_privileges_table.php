<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrivilegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privileges', function (Blueprint $table) {

            $table->id();
            $table->string('privilege')->unique();
            $table->string('description')->nullable(); // ->default('No description available');

            $table->boolean('owner')->default(1);
            $table->boolean('admin')->nullable();
            $table->boolean('moder')->nullable();
            $table->boolean('user')->nullable();

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
        Schema::table('privileges', function(Blueprint $table) {
            // $table->dropUnique(['privilege']);
        });
        Schema::drop('privileges');
    }
}
