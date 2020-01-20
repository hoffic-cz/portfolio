<?php
declare(strict_types=1);


namespace App\Util;


class SourceArt
{
    public function draw(string $source): string
    {
        $map = $this->getBitmap();
        $parts = $this->splitSource($source);

        $output = '';
        $line = 0;
        $position = 0;
        $usedFilling = false;

        while ($line < count($map)) {
            if (empty($parts)) {
                $parts = $this->getFilling();
                $usedFilling = true;
            }
            if ($position >= count($map[0])) {
                $output .= "\n";
                $line++;
                $position = 0;
            } else {
                [$distance, $length] = $this->getNextBlock($map, $line, $position);
                if (is_null($distance)) {
                    $output .= "\n";
                    $line++;
                    $position = 0;
                } elseif ($distance === 0) {
                    $part = array_shift($parts);
                    $output .= $part . ' ';
                    $position += strlen($part) + 1;
                } else {
                    $output .= str_repeat(' ', $distance);
                    $position += $distance;
                }
            }
        }

        if (!$usedFilling) {
            $output .= join(' ', $parts);
        }

        return $output;
    }

    /**
     * Calculates the distance and the length of the next block on the line.
     *
     * @param array $map
     * @param int $line
     * @param int $startPosition
     * @return array [0] = distance, [1] = length
     */
    private function getNextBlock(array &$map, int $line, int $startPosition): array
    {
        $blockStart = null;
        for ($i = $startPosition; $i < count($map[0]); $i++) {
            if ($map[$line][$i]) {
                $blockStart = $i;
                break;
            }
        }

        if (is_null($blockStart)) {
            return [null, 0];
        } else {
            $blockEnd = $blockStart;
            for ($i = $blockStart; $i < count($map[0]); $i++) {
                if (!$map[$line][$i]) {
                    $blockEnd = $i;
                    break;
                }
            }

            return [$blockStart - $startPosition, $blockEnd - $blockStart];
        }
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

                // Make the image 2x wider
                $bitmap[$y][$x * 2] = $pixel['red'] + $pixel['green'] + $pixel['blue'] <= 127 * 3;
                $bitmap[$y][$x * 2 + 1] = $pixel['red'] + $pixel['green'] + $pixel['blue'] <= 127 * 3;
            }
        }

        return $bitmap;
    }

    private function splitSource(string $source)
    {
        $source = preg_replace('/(?:><)/', '> <', $source);
        $source = preg_replace('/([a-z"])>/', '$1 >', $source);
        $source = preg_replace('/(?:=)/', ' = ', $source);

        return preg_split('/[ \n]+/', $source);
    }

    private function getFilling(): array
    {
        return explode(' ', '<!-- Hi, I am Petr! -->');
    }
}
