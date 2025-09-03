<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('file_path', 255);
            $table->bigInteger('file_size')->nullable();
            $table->string('file_type', 50)->nullable();
            $table->foreignId('document_type_id')->nullable()->constrained('document_types')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->year('year')->nullable();
            $table->dateTime('upload_date')->nullable(); // ini ganti created_at
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('slug', 255)->unique();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();

            // hanya updated_at
            $table->timestamp('updated_at')->nullable();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};