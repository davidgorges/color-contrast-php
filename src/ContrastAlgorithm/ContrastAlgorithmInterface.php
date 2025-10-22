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

namespace ColorContrast\ContrastAlgorithm;

use ColorContrast\Color;

/**
 * Interface for contrast calculation algorithms.
 */
interface ContrastAlgorithmInterface
{
    /**
     * Calculates the contrast ratio between two colors.
     *
     * @param Color $foreground The foreground color
     * @param Color $background The background color
     *
     * @return float The contrast ratio
     */
    public function calculate(Color $foreground, Color $background): float;
}
