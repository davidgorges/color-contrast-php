<?php

/*
 * This file is part of the ColorContrast package.
 *
 * (c) David Gorges <gorges@werbelift.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace ColorContrast;

use ColorContrast\ContrastAlgorithm\ContrastAlgorithmInterface;
use ColorContrast\ContrastAlgorithm\LuminosityContrast;
use ColorContrast\Exception\InvalidColorException;
use MischiefCollective\ColorJizz\ColorJizz;
use MischiefCollective\ColorJizz\Exceptions\InvalidArgumentException;
use MischiefCollective\ColorJizz\Formats\Hex;
use MischiefCollective\ColorJizz\Formats\RGB;

class ColorContrast
{
    const LIGHT = 'light';
    const DARK = 'dark';
    /**
     * @var ColorJizz[]
     */
    private $colors = [];

    /**
     * @var ContrastAlgorithmInterface
     */
    private $algorithm;

    /**
     * minimum color contrast based on the WACG 2.0 guidelines.
     */
    const MIN_CONTRAST_AA = 4.5;

    const MIN_CONTRAST_AAA = 7;
    const MIN_CONTRAST_AA_LARGE = 3;
    const MIN_CONTRAST_AAA_LARGE = 4.5;

    public function __construct()
    {
        $this->algorithm = new LuminosityContrast();
    }

    /**
     * add multiple colors at once,.
     *
     * @param mixed $colors
     *
     * @throws InvalidColorException
     */
    public function addColors($colors): void
    {
        $args = \func_get_args();
        foreach ($args as $arg) {
            if (\is_array($arg)) {
                foreach ($arg as $color) {
                    $this->addColor($color);
                }
            } else {
                $this->addColor($arg);
            }
        }
    }

    /**
     * resets the colors.
     */
    public function clear(): void
    {
        $this->colors = [];
    }

    /**
     * @param string|ColorJizz|int $color
     *
     * @throws InvalidColorException
     */
    public function addColor($color): void
    {
        $parsedColor = $this->parseColor($color);
        $this->colors[] = $parsedColor;
    }

    /**
     * @param float $minContrast
     *
     * @return ColorCombination[]
     */
    public function getCombinations($minContrast = 0.0)
    {
        $combinations = [];
        foreach ($this->colors as $backgroundColor) {
            foreach ($this->colors as $foregroundColor) {
                if ($backgroundColor === $foregroundColor) {
                    continue;
                }

                $contrast = $this->algorithm->calculate($foregroundColor, $backgroundColor);
                if ($contrast > $minContrast) {
                    $combinations[] = new ColorCombination($foregroundColor, $backgroundColor, $contrast);
                }
            }
        }

        return $combinations;
    }

    /**
     * @param ContrastAlgorithmInterface $algorithm
     */
    public function setAlgorithm(ContrastAlgorithmInterface $algorithm): void
    {
        $this->algorithm = $algorithm;
    }

    /**
     * @param mixed $color
     *
     * @return ColorJizz
     *
     * @throws InvalidColorException
     */
    private function parseColor($color)
    {
        try {
            if ($color instanceof ColorJizz) {
                return $color;
            }
            if (\is_int($color)) {
                return new Hex($color);
            }
            if (\is_string($color) && \strlen($color) > 4) {
                if (0 === strpos($color, '#')) {
                    return Hex::fromString(substr($color, 1));
                }

                if (6 === \strlen($color)) {
                    return Hex::fromString($color);
                }
            }
        } catch (InvalidArgumentException $e) {
            throw new InvalidColorException('Could not parse color. Currently supported formats are 0x000000, #ff0000 and ff0000', 0, $e);
        }
        throw new InvalidColorException(sprintf('Could not detect color %s', $color));
    }

    /**
     * Tries to detect what font-color you should use as complimentary color.
     * e.g. complimentaryTheme('#ffffff') would return ColorContrast::DARK.
     *
     * @param ColorJizz|string|int $color
     *
     * @return string ColorContrast::LIGHT or ColorContrast::DARK
     *
     * @throws InvalidColorException
     */
    public function complimentaryTheme($color)
    {
        $parsedColor = $this->parseColor($color);
        $yiq = $this->calculateYIQValue($parsedColor->toRGB());
        if ($yiq > 128) {
            return self::DARK;
        }

        return self::LIGHT;
    }

    /**
     * calculates the YIQ value for a given color.
     *
     * @param RGB $color
     *
     * @return float 0-255
     */
    private function calculateYIQValue(RGB $color)
    {
        $yiq = (($color->getRed() * 299) + ($color->getGreen() * 587) + ($color->getBlue() * 114)) / 1000;

        return $yiq;
    }
}
