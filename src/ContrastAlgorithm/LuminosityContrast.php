<?php

namespace ColorContrast\ContrastAlgorithm;

use MischiefCollective\ColorJizz\ColorJizz;
use MischiefCollective\ColorJizz\Formats\RGB;

/**
 * The LuminosityContrast calculates the contrast based on the proposed
 * algorithm from the WCAG 2.0 guides (ITU-R Recommendation BT. 709)
 *
 * @link http://www.w3.org/TR/WCAG20/#contrast-ratiodef
 */
class LuminosityContrast implements ContrastAlgorithmInterface
{
    /**
     * returns the contrast ratio between foreground and background color.
     *
     * @param ColorJizz $foreground
     * @param ColorJizz $background
     *
     * @return float
     */
    public function calculate(ColorJizz $foreground, ColorJizz $background)
    {
        $fgLuma  = $this->relativeLuminosity($foreground->toRGB());
        $bgLuma  = $this->relativeLuminosity($background->toRGB());

        if ($fgLuma > $bgLuma) {
            return ($fgLuma + 0.05) / ($bgLuma + 0.05);
        } else {
            return ($bgLuma + 0.05) / ($fgLuma + 0.05);
        }
    }

    /**
     * @param RGB $color
     *
     * @return float
     */
    private function relativeLuminosity(RGB $color)
    {
        $luminosity = 0.2126 * pow($color->getRed() / 255, 2.2) +
            0.7152 * pow($color->getGreen() / 255, 2.2) +
            0.0722 * pow($color->getBlue() / 255, 2.2);

        return $luminosity;
    }
}