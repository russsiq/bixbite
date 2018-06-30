<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {

            $table->increments('id');
            $table->string('module_name', 30);
            $table->enum('action', ['setting', 'xfield', 'creat', 'edit'])->default('setting');
            $table->string('section', 20)->default('main');
            $table->string('fieldset', 20)->default('general');
            $table->string('name', 30);
            $table->string('type', 20)->default('string');
            $table->string('value');
            $table->text('params')->nullable(); // multiline or json, or dinamic values
            $table->text('class')->length(255)->nullable();
            $table->text('html_flags')->length(500)->nullable();
            $table->string('title', 125)->nullable();
            $table->text('descr')->length(500)->nullable();

            // If need to monitor an additional field. I.e.: create and delete him.
            $table->boolean('as_xfield')->nullable();

            $table->index('action');
            $table->index('module_name');
            $table->unique(['name', 'module_name']);
            // $table->foreign('module_id', DB::getTablePrefix() . 'settings_module_id_fk')->references('id')->on('modules')
            $table->foreign('module_name')->references('name')->on('modules')
                  ->onDelete('cascade')->onUpdate('cascade');

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
        Schema::table('settings', function(Blueprint $table) {
            // $table->dropForeign([DB::getTablePrefix() . 'settings_module_id']);
            $table->dropForeign(['module_name']);
        });
        Schema::dropIfExists('settings');
    }
}
