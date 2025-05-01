<?php

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;

if (!function_exists('cloudinary_thumb')) {
    function cloudinary_thumb($imageUrl, $width = 400, $height = 250)
    {
        // Ambil path setelah 'upload/' dari URL Cloudinary
        $parts = explode('/upload/', $imageUrl);

        if (count($parts) !== 2) {
            return $imageUrl; // fallback ke URL asli kalau tidak sesuai format
        }

        $cloudName = 'ddvtpgszb'; // ganti sesuai cloud_name kamu
        $transformation = "w_{$width},h_{$height},c_limit";

        return "https://res.cloudinary.com/{$cloudName}/image/upload/{$transformation}/{$parts[1]}";
    }

}
