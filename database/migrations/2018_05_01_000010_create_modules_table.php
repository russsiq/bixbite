<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name', 30);
            $table->string('title', 30)->nullable();
            $table->string('icon', 20)->default('fa fa-puzzle-piece');
            $table->text('info')->length(500)->nullable();
            $table->boolean('on_mainpage')->nullable()->default(1);

            // If the module can be extended thanks to additional xfields
            $table->boolean('own_xfield')->nullable();

            $table->unique('name');

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
        Schema::table('modules', function(Blueprint $table) {
            $table->dropIndex(['name']);
        });
        Schema::dropIfExists('modules');
    }
}
