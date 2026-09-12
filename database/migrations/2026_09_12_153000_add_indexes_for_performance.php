<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensi', function (Blueprint $table) {
            $table->index('tanggal_masuk', 'presensi_tanggal_masuk_idx');
            $table->index(['id_pegawai', 'tanggal_masuk'], 'presensi_pegawai_tanggal_idx');
        });

        Schema::table('ketidakhadiran', function (Blueprint $table) {
            $table->index('status_pengajuan', 'ketidakhadiran_status_idx');
            $table->index('tanggal', 'ketidakhadiran_tanggal_idx');
            $table->index(['id_pegawai', 'status_pengajuan'], 'ketidakhadiran_pegawai_status_idx');
        });
    }

    public function down(): void
    {
        Schema::table('presensi', function (Blueprint $table) {
            $table->dropIndex('presensi_tanggal_masuk_idx');
            $table->dropIndex('presensi_pegawai_tanggal_idx');
        });

        Schema::table('ketidakhadiran', function (Blueprint $table) {
            $table->dropIndex('ketidakhadiran_status_idx');
            $table->dropIndex('ketidakhadiran_tanggal_idx');
            $table->dropIndex('ketidakhadiran_pegawai_status_idx');
        });
    }
};
