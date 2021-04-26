<?php

use App\Rules\MetaRobotsRule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Запустить миграции.
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // Отношения и другие поля с индексами.
            $table->unsignedBigInteger('image_id')->nullable();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->unsignedBigInteger('position')->default(1);

            // Основное содержимое.
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('alt_url')->nullable();
            $table->text('info')->length(500)->nullable();

            // Мета поля.
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->enum('robots', MetaRobotsRule::DIRECTIVES)->nullable(); // null - content="all"

            // Дополнительная информация.
            $table->boolean('show_in_menu')->nullable()->default(1);
            $table->unsignedTinyInteger('paginate')->nullable();
            $table->string('order_by', 30)->nullable();
            $table->enum('direction', ['desc', 'asc'])->default('desc');
            $table->string('template', 20)->nullable();

            // Временные метки.
            $table->timestamps();

            $table->index('parent_id');
            $table->index('position');
            $table->foreign('image_id')->references('id')->on('files')
                  ->onDelete('set null');
        });

    }

    /**
     * Обратить миграции.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
