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

use MischiefCollective\ColorJizz\ColorJizz;

/**
 * Data object that represents a combination between a foreground and a background color
 * and their contrast value.
 */
class ColorCombination
{
    /**
     * @var ColorJizz
     */
    private $foreground;

    /**
     * @var ColorJizz
     */
    private $background;

    /**
     * @var float
     */
    private $contrast;

    /**
     * @param ColorJizz $foreground
     * @param ColorJizz $background
     * @param float     $contrast
     */
    public function __construct($foreground, $background, $contrast)
    {
        $this->foreground = $foreground;
        $this->background = $background;
        $this->contrast = $contrast;
    }

    /**
     * @return ColorJizz
     */
    public function getForeground()
    {
        return $this->foreground;
    }

    /**
     * @return ColorJizz
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * @return float
     */
    public function getContrast()
    {
        return $this->contrast;
    }
}
