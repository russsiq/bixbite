<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    // Schema::enableForeignKeyConstraints();
    // Schema::disableForeignKeyConstraints();

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');

            // Relation and other indexed keys.
            $table->string('img')->nullable();
            $table->unsignedInteger('user_id')->default(1);
            $table->unsignedInteger('image_id')->nullable();

            // Main content.
            $table->enum('state', ['draft', 'unpublished', 'published'])->default('unpublished');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('teaser')->nullable();
            $table->text('content');

            // Meta.
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->enum('robots', ['noindex', 'nofollow', 'none'])->nullable(); // null - content="all"

            // Aditional.
            $table->boolean('on_mainpage')->nullable()->default(1);
            $table->boolean('is_favorite')->nullable()->default(0);
            $table->boolean('is_pinned')->nullable()->default(0);
            $table->boolean('is_catpinned')->nullable()->default(0);
            $table->tinyInteger('allow_com')->unsigned()->default(2); // 0 - no; 1 - yes; 2 - by default

            // Counters.
            $table->unsignedInteger('shares')->length(10)->default(0);
            $table->unsignedInteger('views')->length(10)->default(0);
            $table->unsignedInteger('votes')->length(10)->nullable();
            $table->unsignedInteger('rating')->length(10)->nullable();

            // Timestamps.
            $table->timestamps();

            // Make indexes.
            $table->index('state');
            $table->index('user_id');
            // Do not call the onDelete() method if you want the `NO ACTION` option.
            $table->foreign('image_id')->references('id')->on('files')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');
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
        Schema::table('articles', function(Blueprint $table) {
            // $table->dropPrimary(['id']); // ALTER TABLE table_name CHANGE key_field_name key_field_name INTEGER NOT NULL;

            $table->dropForeign(['user_id']);
            $table->dropForeign(['image_id']);
            // $table->dropUnique(['slug']);
            // $table->dropIndex(['state']);
            // $table->dropIndex(['user_id']);
            //
            // $table->dropIndex('search');
        });
        Schema::dropIfExists('articles');
    }
}
