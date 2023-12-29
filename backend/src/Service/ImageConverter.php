<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;

class ImageConverter
{
    /**
     * Konwertuje zdjęcie na format .webp.
     *
     * @param string $inputPath Ścieżka do oryginalnego zdjęcia.
     *
     * @return Response Tablica z informacją o sukcesie konwersji, zwraca również skonwertowane zdjęcie w formacie base64.
     */
    public function convertToWebp(string $inputPath): Response
    {
        $inputExtension = pathinfo($inputPath, PATHINFO_EXTENSION);

        if (!in_array($inputExtension, ['jpg', 'jpeg', 'png'])) {
            return new Response(json_encode([
                'success' => false,
                'data' => null,
                'error' => 'Nieobsługiwany format pliku.'
            ]));
        }

        $image = null;

        switch ($inputExtension) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($inputPath);
                break;
            case 'png':
                $image = imagecreatefrompng($inputPath);
                break;
        }

        if (!$image) {
            return new Response(json_encode([
                'success' => false,
                'data' => null,
                'error' => 'Nie udało się wczytać obrazu.'
            ]));
        }

        ob_start();
        $success = imagewebp($image);
        $webpData = ob_get_clean();

        imagedestroy($image);

        return new Response(json_encode([
            'success' => $success,
            'data' => base64_encode($webpData),
            'error' => null
        ]));
    }
}