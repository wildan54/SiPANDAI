<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('access_type', ['view', 'download', 'upload', 'delete', 'update', 'other']);
            $table->foreignId('document_id')->nullable()->constrained('documents')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer', 255)->nullable();
            $table->timestamp('access_datetime')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_logs');
    }
};