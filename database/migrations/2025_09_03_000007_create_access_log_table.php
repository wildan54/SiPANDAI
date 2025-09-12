<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('access_logs', function (Blueprint $table) {
            $table->id();

            // Relasi ke users (jika user dihapus, log ikut terhapus)
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Document ID tanpa foreign key
            $table->unsignedBigInteger('document_id')->nullable();

            // Snapshot judul dokumen
            $table->string('document_title')->nullable();

            $table->enum('access_type', ['view', 'download', 'upload', 'delete', 'update', 'other']);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer', 255)->nullable();
            $table->timestamp('access_datetime')->useCurrent();

            // Index untuk mempercepat query berdasarkan waktu
            $table->index('access_datetime');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_logs');
    }
};