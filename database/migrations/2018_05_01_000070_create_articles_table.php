<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('image_id')->nullable()->constrained('attachments')->onDelete('set null');
            $table->unsignedTinyInteger('state')->default(0)->comment('0: draft; 1: published; 2: unpublished');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('teaser')->nullable();
            $table->text('content')->default('');
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_robots')->default('all');
            $table->boolean('on_mainpage')->default(true);
            $table->boolean('is_favorite')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_catpinned')->default(false);
            $table->unsignedTinyInteger('allow_com')->default(2)->comment('0: deny; 1: allow; 2: by default');
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            $table->index('user_id');
            $table->index('image_id');
            $table->index('state');
        });

        DB::statement('ALTER TABLE '.(DB::getTablePrefix()).'articles ADD FULLTEXT search(title, content)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
