<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            // Add new foreign key columns
            $table->unsignedBigInteger('jabatan_id')->nullable()->after('no_handphone');
            $table->unsignedBigInteger('lokasi_presensi_id')->nullable()->after('jabatan_id');
            
            // Add foreign key constraints
            $table->foreign('jabatan_id')->references('id')->on('jabatan')->onDelete('set null');
            $table->foreign('lokasi_presensi_id')->references('id')->on('lokasi_presensi')->onDelete('set null');
            
            // Drop old string columns
            $table->dropColumn(['jabatan', 'lokasi_presensi']);
        });
    }

    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            // Restore old string columns
            $table->string('jabatan', 50)->nullable();
            $table->string('lokasi_presensi', 50)->nullable();
            
            // Drop foreign key constraints
            $table->dropForeign(['jabatan_id']);
            $table->dropForeign(['lokasi_presensi_id']);
            
            // Drop new columns
            $table->dropColumn(['jabatan_id', 'lokasi_presensi_id']);
        });
    }
};
