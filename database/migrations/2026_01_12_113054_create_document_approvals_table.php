<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('document_approvals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('document_id')
                  ->constrained('documents')
                  ->cascadeOnDelete();

            $table->foreignId('reviewed_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected']);
            $table->text('note')->nullable();
            $table->timestamp('reviewed_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_approvals');
    }
};
