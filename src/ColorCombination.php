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

/**
 * Data object that represents a combination between a foreground and a background color
 * and their contrast value.
 */
final class ColorCombination
{
    public function __construct(
        private readonly Color $foreground,
        private readonly Color $background,
        private readonly float $contrast
    ) {
    }

    public function getForeground(): Color
    {
        return $this->foreground;
    }

    public function getBackground(): Color
    {
        return $this->background;
    }

    public function getContrast(): float
    {
        return $this->contrast;
    }
}
