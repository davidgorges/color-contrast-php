<?php

namespace ColorContrast\ContrastAlgorithm;

use MischiefCollective\ColorJizz\ColorJizz;

/**
 * The LuminosityContrast calculates the contrast based on the proposed
 * algorithm from the WCAG 2.0 guides
 *
 * @link http://www.w3.org/TR/WCAG20/#contrast-ratiodef
 */
class LuminosityContrast implements ContrastAlgorithmInterface
{
    /**
     * @inheritdoc
     */
    public function calculate(ColorJizz $foreground, ColorJizz $background)
    {
        $c1 = $background->toRGB();
        $c2 = $foreground->toRGB();
        $luminosity1 = 0.2126 * pow($c1->getRed() / 255, 2.2) +
            0.7152 * pow($c1->getGreen() / 255, 2.2) +
            0.0722 * pow($c1->getBlue() / 255, 2.2);

        $luminosity2 = 0.2126 * pow($c2->getRed() / 255, 2.2) +
            0.7152 * pow($c2->getGreen() / 255, 2.2) +
            0.0722 * pow($c2->getBlue() / 255, 2.2);

        if ($luminosity1 > $luminosity2) {
            return ($luminosity1 + 0.05) / ($luminosity2 + 0.05);
        } else {
            return ($luminosity2 + 0.05) / ($luminosity1 + 0.05);
        }
    }
}