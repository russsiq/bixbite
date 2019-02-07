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


            $table->increments('id');
            $table->boolean('is_shared')->nullable()->default(0);
            $table->integer('user_id')->unsigned()->nullable();
            $table->nullableMorphs('attachment');

            $table->string('disk')->default('public');
            $table->string('category'); // folder
            $table->string('type'); // ['archive', 'audio', 'doc', 'image', 'video', 'other']
            $table->string('name');
            $table->string('extension', 10);
            $table->string('mime_type');
            $table->integer('filesize'); // filesize();
            $table->string('checksum', 32); // md5_file()

            $table->string('title')->nullable();
            $table->text('description', 1000)->nullable();
            $table->text('properties')->nullable(); // json field type. Dimention, duration, etc.
            $table->integer('downloads')->unsigned()->default(0); // download counters




            // $table->unique(['type', 'name', 'extension']);
            $table->unique(['checksum']);




            $table->index('user_id');
            $table->index('attachment_id');
            $table->index('attachment_type');
            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('set null');

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
        Schema::table('files', function(Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('files');
    }
}
