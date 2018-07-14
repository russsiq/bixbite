<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('x_fields', function (Blueprint $table) {

            $table->increments('id');
            $table->string('extensible', 30);
            $table->string('name', 30);
            $table->string('type', 20)->default('string');
            $table->text('params')->nullable();
            $table->string('title', 125)->nullable();
            $table->text('descr')->length(500)->nullable();
            $table->text('html_flags')->length(500)->nullable();

            // disabled or except or used or activated.

            $table->index('extensible');
            $table->unique(['extensible', 'name']);

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
        Schema::dropIfExists('x_fields');
    }
}
