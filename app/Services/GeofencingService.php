<?php

namespace App\Services;

class GeofencingService
{
    /**
     * Hitung jarak antara dua koordinat (meter) menggunakan formula Haversine.
     */
    public function distanceInMeters(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // meter

        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a = sin($deltaLat / 2) ** 2
            + cos($lat1Rad) * cos($lat2Rad) * sin($deltaLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Cek apakah koordinat pegawai berada dalam radius toleransi lokasi presensi.
     */
    public function isWithinRadius(float $userLat, float $userLon, float $officeLat, float $officeLon, int $radiusMeter): bool
    {
        return $this->distanceInMeters($userLat, $userLon, $officeLat, $officeLon) <= $radiusMeter;
    }
}
