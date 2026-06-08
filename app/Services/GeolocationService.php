<?php

namespace App\Services;

class GeolocationService
{
    public function isWithinRadius(
        float $userLat, float $userLng,
        float $schoolLat, float $schoolLng,
        int $radiusMeters
    ): bool {
        $distance = $this->calculateDistance($userLat, $userLng, $schoolLat, $schoolLng);
        return $distance <= $radiusMeters;
    }

    public function calculateDistance(
        float $lat1, float $lng1,
        float $lat2, float $lng2
    ): float {
        $earthRadius = 6371000; // meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
