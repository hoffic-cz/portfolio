<?php
declare(strict_types=1);


namespace App\Util;


class SourceArt
{
    /** @var string */
    private $imagePath;

    /**
     * SourceArt constructor.
     * @param string $imagePath
     */
    public function __construct(string $imagePath)
    {
        $this->imagePath = $imagePath;
    }

    /**
     * The algorithm for distributing source code so it looks like the graphics.
     * @param string $source
     * @return string
     */
    public function draw(string $source): string
    {
        $map = $this->getBitmap();
        $parts = $this->splitSource($source);

        $output = '';
        $line = 0;
        $position = 0;
        $usedFilling = false;

        while ($line < count($map)) {
            /*
             * In case the source isn't long enough to cover the graphics we generate more comments.
             */
            if (empty($parts)) {
                $parts = $this->getFilling();
                $usedFilling = true;
            }
            /*
             * If the cursor has run out of the image on the right we jump to a new line.
             */
            if ($position >= count($map[0])) {
                $output .= "\n";
                $line++;
                $position = 0;
            } else {
                [$distance, $length] = $this->getNextBlock($map, $line, $position);
                /*
                 * If there are no more blocks to the right, we jump to a new line.
                 */
                if (is_null($distance)) {
                    $output .= "\n";
                    $line++;
                    $position = 0;
                } elseif ($distance === 0) {
                    $part = $parts[0];
                    /*
                     * If this isn't the first position in the block and the part is really long,
                     * we jump to the next block and try to place the part there so that we utilize
                     * the whole block.
                     */
                    if (strlen($part) > $length * 2 && $position > 0 && $map[$line][$position - 1]) {
                        $output .= ' ';
                        $position++;
                    } else {
                        $output .= $part . ' ';
                        $position += strlen($part) + 1;
                        array_shift($parts);
                    }
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
     * Calculates the distance and the length of the next block on the line. A block is made of a
     * sequence of characters that are neither a space or a new line.
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

    /**
     * Converts the
     * @return array
     */
    private function getBitmap(): array
    {
        $image = imagecreatefrompng($this->imagePath);

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

    /**
     * Splits the source code into parts that don't mind being separated by spaces or new lines.
     * @param string $source
     * @return array|false|string[]
     */
    private function splitSource(string $source)
    {
        $source = preg_replace('/(?:><)/', '> <', $source);
        $source = preg_replace('/([a-z"])>/', '$1 >', $source);
        $source = preg_replace('/(?:=)/', ' = ', $source);

        return preg_split('/[ \n]+/', $source);
    }

    /**
     * Returns an array of filling parts used when the source code isn't enough to cover the image.
     * @return array
     */
    private function getFilling(): array
    {
        return explode(' ', '<!-- Hi, I am Petr! -->');
    }
}
