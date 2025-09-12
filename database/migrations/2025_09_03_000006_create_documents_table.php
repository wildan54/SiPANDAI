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
            $table->unsignedBigInteger('uploaded_by'); // user yang upload dokumen

            // File: bisa upload atau embed
            $table->string('file_path')->nullable();
            $table->string('file_source')->default('embed'); 
            $table->text('file_embed')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('file_type')->nullable();

            // Metadata
            $table->string('slug')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // Tahun dokumen (manual, tidak ambil dari upload_date)
            $table->year('year'); 

            // Upload info
            $table->timestamp('upload_date');
            $table->timestamp('updated_at')->nullable();

            // Index/foreign key
            $table->foreign('document_type_id')
                  ->references('id')->on('document_types')
                  ->restrictOnDelete(); // ❌ dokumen tidak ikut hilang saat tipe dihapus

            $table->foreign('unit_id')
                  ->references('id')->on('units')
                  ->cascadeOnDelete();  // unit hilang, dokumen ikut hilang

            $table->foreign('uploaded_by')
                  ->references('id')->on('users')
                  ->restrictOnDelete(); // ❌ dokumen tidak ikut hilang saat user dihapus
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};