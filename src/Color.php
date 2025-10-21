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

use ColorContrast\Exception\InvalidColorException;

/**
 * Represents a color with RGB values.
 * Supports parsing from hex strings and integers.
 */
final readonly class Color
{
    /**
     * @param int $red   Red component (0-255)
     * @param int $green Green component (0-255)
     * @param int $blue  Blue component (0-255)
     *
     * @throws InvalidColorException
     */
    public function __construct(
        private int $red,
        private int $green,
        private int $blue
    ) {
        if ($red < 0 || $red > 255 || $green < 0 || $green > 255 || $blue < 0 || $blue > 255) {
            throw new InvalidColorException(
                sprintf('RGB values must be between 0 and 255. Got: R=%d, G=%d, B=%d', $red, $green, $blue)
            );
        }
    }

    /**
     * Creates a Color from a hexadecimal integer value.
     *
     * @param int $hex Hexadecimal color value (e.g., 0xFF0000 for red)
     */
    public static function fromHex(int $hex): self
    {
        $red = ($hex >> 16) & 0xFF;
        $green = ($hex >> 8) & 0xFF;
        $blue = $hex & 0xFF;

        return new self($red, $green, $blue);
    }

    /**
     * Creates a Color from a hexadecimal string.
     *
     * @param string $hex Hexadecimal color string (e.g., 'FF0000' or '#FF0000')
     *
     * @throws InvalidColorException
     */
    public static function fromHexString(string $hex): self
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            // Convert short format (e.g., 'F00') to long format ('FF0000')
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        if (strlen($hex) !== 6) {
            throw new InvalidColorException(
                sprintf('Hex string must be 3 or 6 characters long. Got: %s', $hex)
            );
        }

        if (!ctype_xdigit($hex)) {
            throw new InvalidColorException(
                sprintf('Hex string must contain only hexadecimal characters. Got: %s', $hex)
            );
        }

        $value = hexdec($hex);

        return self::fromHex($value);
    }

    public function getRed(): int
    {
        return $this->red;
    }

    public function getGreen(): int
    {
        return $this->green;
    }

    public function getBlue(): int
    {
        return $this->blue;
    }

    /**
     * Converts the color to a hexadecimal integer value.
     */
    public function toHex(): int
    {
        return ($this->red << 16) | ($this->green << 8) | $this->blue;
    }

    /**
     * Converts the color to a hexadecimal string.
     *
     * @param bool $includeHash Whether to include the '#' prefix
     */
    public function toHexString(bool $includeHash = true): string
    {
        $hex = sprintf('%06X', $this->toHex());

        return $includeHash ? '#'.$hex : $hex;
    }
}
