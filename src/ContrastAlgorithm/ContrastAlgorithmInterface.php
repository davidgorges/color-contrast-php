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

namespace ColorContrast\ContrastAlgorithm;

use MischiefCollective\ColorJizz\ColorJizz;

/**
 * The ContrastInterface class.
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
