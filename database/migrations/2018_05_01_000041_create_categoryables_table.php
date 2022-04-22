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
        Schema::create('categoryables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->morphs('categoryable');
            $table->boolean('is_main')->default(false);

            $table->index('category_id');
            $table->index('categoryable_id');
            $table->index('categoryable_type');
            $table->unique([
                'category_id',
                'categoryable_id',
                'categoryable_type',
            ], 'categoryable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categoryables');
    }
};
