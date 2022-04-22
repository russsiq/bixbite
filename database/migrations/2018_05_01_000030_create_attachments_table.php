<?php

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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('attachable');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('disk')->default('public');
            $table->string('folder')->default('uploads');
            $table->string('type')->comment('archive, audio, document, image, video, other');
            $table->string('name');
            $table->string('extension', 10);
            $table->string('mime_type');
            $table->unsignedBigInteger('filesize');
            $table->json('properties')->comment('Dimention, duration, etc.');
            $table->unsignedBigInteger('downloads')->default(0);
            $table->timestamps();

            $table->unique(['name', 'extension']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
};
