<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class SelfieService
{
    public function processAndStore(string $base64Image, User $user, string $type = 'masuk', ?float $lat = null, ?float $lng = null): string
    {
        $manager = new ImageManager(new Driver());

        // Decode base64
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));

        $image = $manager->read($imageData);

        // Resize to max 800px width
        $image->scale(width: 800);

        // Add watermark text
        $watermarkText = $user->name . ' | NIS: ' . ($user->nis ?? '-');
        $dateText = now()->format('d M Y H:i:s');
        $locationText = $lat && $lng ? sprintf('Lat: %.4f Lng: %.4f', $lat, $lng) : '';

        // Add text overlay (watermark)
        $image->text($watermarkText, 10, $image->height() - 60, function ($font) {
            $font->size(16);
            $font->color('#ffffff');
        });
        $image->text($dateText, 10, $image->height() - 35, function ($font) {
            $font->size(14);
            $font->color('#ffffff');
        });
        if ($locationText) {
            $image->text($locationText, 10, $image->height() - 10, function ($font) {
                $font->size(12);
                $font->color('#ffffff');
            });
        }

        // Save path
        $filename = $user->id . '_' . now()->format('Y-m-d') . '_' . $type . '.jpg';
        $path = 'selfies/' . $user->id . '/' . $filename;

        // Encode to JPEG with 70% quality
        $encoded = $image->toJpeg(70);

        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }
}
