<?php

/*
 * This file is part of the ColorContrast package.
 *
 * (c) David Gorges <gorges@werbelift.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ColorContrast;

use ColorContrast\ContrastAlgorithm\ContrastAlgorithmInterface;
use ColorContrast\ContrastAlgorithm\LuminosityContrast;
use ColorContrast\Exception\InvalidColorException;

final class ColorContrast
{
    public const LIGHT = 'light';
    public const DARK = 'dark';

    /**
     * Minimum color contrast based on the WCAG 2.0 guidelines.
     */
    public const MIN_CONTRAST_AA = 4.5;
    public const MIN_CONTRAST_AAA = 7.0;
    public const MIN_CONTRAST_AA_LARGE = 3.0;
    public const MIN_CONTRAST_AAA_LARGE = 4.5;

    /**
     * @var Color[]
     */
    private array $colors = [];

    private ContrastAlgorithmInterface $algorithm;

    public function __construct(?ContrastAlgorithmInterface $algorithm = null)
    {
        $this->algorithm = $algorithm ?? new LuminosityContrast();
    }

    /**
     * Add multiple colors at once.
     *
     * @param mixed ...$colors Color values (can be arrays or individual values)
     *
     * @throws InvalidColorException
     */
    public function addColors(mixed ...$colors): void
    {
        foreach ($colors as $color) {
            if (is_array($color)) {
                foreach ($color as $singleColor) {
                    $this->addColor($singleColor);
                }
            } else {
                $this->addColor($color);
            }
        }
    }

    /**
     * Resets the colors.
     */
    public function clear(): void
    {
        $this->colors = [];
    }

    /**
     * Add a single color to the collection.
     *
     * @param string|Color|int $color Color value in various formats
     *
     * @throws InvalidColorException
     */
    public function addColor(string|Color|int $color): void
    {
        $this->colors[] = $this->parseColor($color);
    }

    /**
     * Get all color combinations that meet the minimum contrast ratio.
     *
     * @param float $minContrast Minimum contrast ratio to filter by
     *
     * @return ColorCombination[]
     */
    public function getCombinations(float $minContrast = 0.0): array
    {
        $combinations = [];
        foreach ($this->colors as $backgroundColor) {
            foreach ($this->colors as $foregroundColor) {
                if ($backgroundColor === $foregroundColor) {
                    continue;
                }

                $contrast = $this->algorithm->calculate($foregroundColor, $backgroundColor);
                if ($contrast > $minContrast) {
                    $combinations[] = new ColorCombination(
                        $foregroundColor,
                        $backgroundColor,
                        $contrast
                    );
                }
            }
        }

        return $combinations;
    }

    /**
     * Set the contrast calculation algorithm.
     */
    public function setAlgorithm(ContrastAlgorithmInterface $algorithm): void
    {
        $this->algorithm = $algorithm;
    }

    /**
     * Parse a color value into a Color object.
     *
     * @param string|Color|int $color Color value to parse
     *
     * @throws InvalidColorException
     */
    private function parseColor(string|Color|int $color): Color
    {
        if ($color instanceof Color) {
            return $color;
        }

        if (is_int($color)) {
            return Color::fromHex($color);
        }

        if (is_string($color)) {
            if (strlen($color) === 3 || strlen($color) === 6) {
                return Color::fromHexString($color);
            }

            if (str_starts_with($color, '#') && (strlen($color) === 4 || strlen($color) === 7)) {
                return Color::fromHexString($color);
            }
        }

        throw new InvalidColorException(
            sprintf(
                'Could not parse color. Supported formats: 0xFF0000, #FF0000, FF0000, #F00, F00. Got: %s',
                (string) $color
            )
        );
    }

    /**
     * Tries to detect what font-color you should use as complimentary color.
     * e.g. complimentaryTheme('#ffffff') would return ColorContrast::DARK.
     *
     * @param Color|string|int $color     Color to analyze
     * @param int              $threshold YIQ threshold value (0-255)
     *
     * @return string ColorContrast::LIGHT or ColorContrast::DARK
     *
     * @throws InvalidColorException
     */
    public function complimentaryTheme(Color|string|int $color, int $threshold = 128): string
    {
        $parsedColor = $this->parseColor($color);
        $yiq = $this->calculateYIQValue($parsedColor);

        if ($yiq > $threshold) {
            return self::DARK;
        }

        return self::LIGHT;
    }

    /**
     * Calculates the YIQ value for a given color.
     *
     * @return float Value between 0-255
     */
    private function calculateYIQValue(Color $color): float
    {
        return (
            ($color->getRed() * 299) +
            ($color->getGreen() * 587) +
            ($color->getBlue() * 114)
        ) / 1000;
    }
}
