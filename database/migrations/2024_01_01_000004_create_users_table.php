<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pegawai')
                ->constrained('pegawai')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('username', 50)->unique();
            $table->string('password', 225);
            $table->string('status', 20)->default('aktif');
            $table->string('role', 20); // admin | pegawai
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
