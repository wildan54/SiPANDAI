<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->string('access_type', 30)->change();
        });
    }

    public function down(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->enum('access_type', [
                'view',
                'create',
                'edit'
            ])->change();
        });
    }
};
