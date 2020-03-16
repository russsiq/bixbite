<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Note: When delete user, all files that user owns are deleted.

        Schema::create('files', function (Blueprint $table) {

            // name
            // file_name
            // slug
            // alt
            // description
            // folder
            // size
            // min
            // max
            // full

            // $table->string('path');
            // $table->string('alt');
            // $table->string('title');
            // $table->string('link');
            // $table->string('folder');

            // Full filename
            // $file->disk
            // $this->getUserDir()
            // $file->type
            // $file->name
            // $file->extension
            //


            $table->id();

            // Relation and other indexed keys.
            $table->unsignedBigInteger('user_id')->nullable();
            $table->nullableMorphs('attachment');

            // Main content.
            $table->string('title')->nullable();
            $table->text('description', 3000)->nullable();
            $table->string('disk')->default('public');
            $table->string('category'); // folder

            // Aditional.
            $table->boolean('is_shared')->nullable();

            // Counters.
            $table->unsignedBigInteger('downloads')->default(0); // download counters

            // Appending when download.
            $table->string('type'); // ['archive', 'audio', 'doc', 'image', 'video', 'other']
            $table->string('name');
            $table->string('extension', 10);
            $table->string('mime_type');
            $table->integer('filesize'); // filesize();
            $table->string('checksum', 32); // md5_file()
            $table->text('properties')->nullable(); // json field type. Dimention, duration, etc.

            // Timestamps.
            $table->timestamps();

            // Make indexes.
            $table->unique(['checksum']); // $table->unique(['type', 'name', 'extension']);
            $table->index('user_id');
            $table->index('attachment_id');
            $table->index('attachment_type');
            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function(Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('files');
    }
}
