<?php

use App\Rules\MetaRobotsRule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    // Schema::enableForeignKeyConstraints();
    // Schema::disableForeignKeyConstraints();

    /**
     * Запустить миграции.
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            // Отношения и другие поля с индексами.
            $table->string('img')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('image_id')->nullable();
            $table->enum('state', ['draft', 'unpublished', 'published'])->default('unpublished');

            // Основное содержимое.
            $table->string('title');
            $table->string('slug');
            $table->string('teaser')->nullable();
            $table->text('content')->nullable();

            // Мета поля.
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->enum('robots', MetaRobotsRule::DIRECTIVES)->nullable(); // null - content="all"

            // Дополнительная информация.
            $table->boolean('on_mainpage')->nullable()->default(1);
            $table->boolean('is_favorite')->nullable()->default(0);
            $table->boolean('is_pinned')->nullable()->default(0);
            $table->boolean('is_catpinned')->nullable()->default(0);
            $table->tinyInteger('allow_com')->unsigned()->default(2); // 0 - no; 1 - yes; 2 - by default

            // Счетчики.
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('shares')->default(0);
            $table->unsignedInteger('votes')->nullable();
            $table->unsignedInteger('rating')->nullable();

            // Временные метки.
            $table->timestamps();

            // Make indexes.
            $table->index('state');
            $table->index('user_id');
            // Do not call the onDelete() method if you want the `NO ACTION` option.
            $table->foreign('image_id')->references('id')->on('files')
                  ->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');
        });

        DB::statement('ALTER TABLE '.(DB::getTablePrefix()).'articles ADD FULLTEXT search(title, content)');
    }

    /**
     * Обратить миграции.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
