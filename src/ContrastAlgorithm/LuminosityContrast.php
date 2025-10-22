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
 * The LuminosityContrast calculates the contrast based on the proposed
 * algorithm from the WCAG 2.0 guides (ITU-R Recommendation BT. 709).
 *
 * @see http://www.w3.org/TR/WCAG20/#contrast-ratiodef
 */
final class LuminosityContrast implements ContrastAlgorithmInterface
{
    /**
     * Returns the contrast ratio between foreground and background color.
     */
    public function calculate(Color $foreground, Color $background): float
    {
        $fgLuma = $this->relativeLuminosity($foreground);
        $bgLuma = $this->relativeLuminosity($background);

        if ($fgLuma > $bgLuma) {
            return ($fgLuma + 0.05) / ($bgLuma + 0.05);
        }

        return ($bgLuma + 0.05) / ($fgLuma + 0.05);
    }

    /**
     * Calculates the relative luminosity of a color.
     */
    private function relativeLuminosity(Color $color): float
    {
        return 0.2126 * ($color->getRed() / 255) ** 2.2
            + 0.7152 * ($color->getGreen() / 255) ** 2.2
            + 0.0722 * ($color->getBlue() / 255) ** 2.2;
    }
}
