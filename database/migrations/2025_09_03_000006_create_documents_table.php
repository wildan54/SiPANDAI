<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();

            // Relasi tipe dokumen dan unit
            $table->unsignedBigInteger('document_type_id');
            $table->unsignedBigInteger('unit_id');

            // File: bisa upload atau embed
            $table->string('file_path')->nullable();         // untuk file upload
            $table->string('file_source')->default('embed'); // 'upload' atau 'embed'
            $table->text('file_embed')->nullable();          // link cloud jika embed
            $table->integer('file_size')->nullable();
            $table->string('file_type')->nullable();

            // Metadata
            $table->string('slug')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // Upload info (wajib)
            $table->timestamp('upload_date');               // tidak nullable
            $table->unsignedBigInteger('uploaded_by');      // tidak nullable

            $table->timestamp('updated_at')->nullable();

            // Index/foreign key
            $table->foreign('document_type_id')->references('id')->on('document_types')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};