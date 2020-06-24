<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Запустить миграции.
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->unique();
            $table->string('title', 30)->nullable();
            $table->string('icon', 20)->default('fa fa-puzzle-piece');
            $table->text('info')->length(500)->nullable();
            $table->boolean('on_mainpage')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Обратить миграции.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
