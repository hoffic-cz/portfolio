<?php
declare(strict_types=1);


namespace App\Util;


class SourceArt
{
    public function draw(string $source): string
    {
        $map = $this->getBitmap();

        return $source;
    }

    private function getBitmap(): array
    {
        $image = imagecreatefrompng('../graphics/source_art.png');

        $width = imagesx($image);
        $height = imagesy($image);

        $bitmap = [];

        for ($y = 0; $y < $height; $y++) {
            $bitmap[$y] = [];

            for ($x = 0; $x < $width; $x++) {
                $pixel = imagecolorsforindex($image, imagecolorat($image, $x, $y));

                $bitmap[$y][$x] = $pixel['red'] + $pixel['green'] + $pixel['blue'] <= 127 * 3;
            }
        }

        return $bitmap;
    }
}
