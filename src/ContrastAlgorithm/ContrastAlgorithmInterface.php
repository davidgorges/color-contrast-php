<?php

namespace ColorContrast\ContrastAlgorithm;

use MischiefCollective\ColorJizz\ColorJizz;

/**
 * The ContrastInterface class
 */
interface ContrastAlgorithmInterface
{
    public function calculate(ColorJizz $foreground, ColorJizz $background);
}