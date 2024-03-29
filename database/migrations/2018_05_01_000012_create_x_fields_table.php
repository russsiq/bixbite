<?php

use App\Models\XField;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('x_fields', function (Blueprint $table) {
            $table->id();
            $table->string('extensible');
            $table->string('name', XField::maximumLengthColumnName());
            $table->string('type')->default('string');
            $table->json('params')->default('[]');
            $table->string('title')->nullable();
            $table->text('description')->length(500)->nullable();
            $table->json('html_flags')->default('[]');
            $table->timestamps();

            // disabled or except or used or activated.

            $table->index('extensible');
            $table->unique(['extensible', 'name']);
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
};
