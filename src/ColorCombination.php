<?php

namespace ColorContrast;
use MischiefCollective\ColorJizz\ColorJizz;

/**
 * Data object that represents a combination between a foreground and a background color
 * and their contrast value
 */
class ColorCombination
{

    /**
     * @var ColorJizz $foreground
     */
    private $foreground;
    /**
     * @var ColorJizz $foreground
     */
    private $background;

    /**
     * @var float $contrast
     */
    private $contrast;

    /**
     * @param $foreground
     * @param $background
     * @param $contrast
     */
    function __construct($foreground, $background, $contrast)
    {
        $this->foreground = $foreground;
        $this->background = $background;
        $this->contrast = $contrast;
    }

    /**
     * @return mixed
     */
    public function getForeground()
    {
        return $this->foreground;
    }

    /**
     * @return mixed
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * @return mixed
     */
    public function getContrast()
    {
        return $this->contrast;
    }

}