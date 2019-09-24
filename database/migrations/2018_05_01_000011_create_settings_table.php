<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module_name', 30);
            $table->string('name', 30);
            $table->string('type', 20)->default('string');
            $table->string('value');

            $table->index('module_name');
            $table->unique(['name', 'module_name']);
            $table->foreign('module_name')->references('name')->on('modules')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function(Blueprint $table) {
            $table->dropForeign(['module_name']);
        });

        Schema::dropIfExists('settings');
    }
}
