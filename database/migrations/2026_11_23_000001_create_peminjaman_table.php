<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('penggunas')->restrictOnDelete();
            $table->foreignId('media_id')->constrained('media')->restrictOnDelete();
            $table->timestamp('borrowed_at');
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();

            $table->index(['media_id', 'returned_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
