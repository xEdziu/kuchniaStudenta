<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;

class ImageConverter
{
    /**
     * Konwertuje zdjęcie na format .webp.
     *
     * @param string $inputPath Ścieżka do oryginalnego zdjęcia.
     * @param string $outputPath Ścieżka do zapisanego zdjęcia .webp.
     *
     * @return Response Czy konwersja zakończona sukcesem.
     */
    public function convertToWebp(string $inputPath, string $outputPath): Response
    {
        $inputExtension = pathinfo($inputPath, PATHINFO_EXTENSION);

        // Sprawdzanie, czy plik jest obsługiwany
        if (!in_array($inputExtension, ['jpg', 'jpeg', 'png'])) {
            return new Response(json_encode([
                'success' => false,
                'path' => null,
                'error' => 'Nieobsługiwany format pliku.'
            ]));
        }

        $image = null;

        // Wczytywanie obrazu z oryginalnego pliku
        switch ($inputExtension) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($inputPath);
                break;
            case 'png':
                $image = imagecreatefrompng($inputPath);
                break;
        }

        // Sprawdzanie, czy obraz został poprawnie wczytany
        if (!$image) {
            return new Response(json_encode([
                'success' => false,
                'path' => null,
                'error' => 'Nie udało się wczytać obrazu.'
            ]));
        }

        // Konwersja i zapisanie obrazu w formacie .webp
        $success = imagewebp($image, $outputPath);

        // Zwalnianie pamięci
        imagedestroy($image);

        return new Response(json_encode([
            'success' => $success,
            'path' => $outputPath,
            'error' => null
        ]));
    }
}