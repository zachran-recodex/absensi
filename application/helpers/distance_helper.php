<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Fungsi untuk menghitung jarak antara dua titik koordinat (latitude, longitude)
 * Menggunakan rumus Haversine
 *
 * @param float $lat1 Latitude titik pertama
 * @param float $lon1 Longitude titik pertama
 * @param float $lat2 Latitude titik kedua
 * @param float $lon2 Longitude titik kedua
 * @return float Jarak dalam meter
 */
function calculate_distance($lat1, $lon1, $lat2, $lon2)
{
    $earth_radius = 6371; // Radius bumi dalam kilometer

    // Menghitung perbedaan dalam radian
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    // Menghitung jarak dengan rumus Haversine
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    // Menghitung jarak dalam meter
    $distance = $earth_radius * $c * 1000; 

    return $distance;
}
?>
