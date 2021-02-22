<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atachments', function (Blueprint $table) {
            $table->id();

            // Отношения и индексные поля.
            $table->morphs('attachable');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Основное содержимое.
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('disk')->default('public');
            $table->string('folder')->default('public');

            // Автоматически заполняемые поля.
            $table->string('type'); // ['archive', 'audio', 'doc', 'image', 'video', 'other']
            $table->string('name');
            $table->string('extension', 10);
            $table->string('mime_type');
            $table->unsignedBigInteger('filesize');
            $table->string('checksum', 32)->unique(); // md5_file()
            $table->json('properties'); // json field type. Dimention, duration, etc.

            // Счетчики.
            $table->unsignedBigInteger('downloads')->default(0);

            // Временные метки.
            $table->timestamps();

            // Создание индексов.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('atachments');
    }
}
