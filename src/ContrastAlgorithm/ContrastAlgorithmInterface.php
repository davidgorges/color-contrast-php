<?php

namespace ColorContrast\ContrastAlgorithm;

use MischiefCollective\ColorJizz\ColorJizz;

/**
 * The ContrastInterface class
 */
interface ContrastAlgorithmInterface
{
    /**
     * @param ColorJizz $foreground
     * @param ColorJizz $background
     *
     * @return mixed
     */
    public function calculate(ColorJizz $foreground, ColorJizz $background);
}