<?php
use ColorContrast\ColorContrast;

class ColorContrastTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test different ways to add colors
     */
    public function testColorAdditionWithMultipleArguments()
    {
        $contrast = new ColorContrast();
        $contrast->addColors('#ff9900', 0x223399, 0x0, '#ffff00');
        $combinations = $contrast->getCombinations(0);
        $this->assertCount(12, $combinations);

        $contrast->clear();
        $combinations = $contrast->getCombinations(0.0);
        $this->assertCount(0, $combinations);

        $colors = array('#ff0000', '#000000', '#ffffff');
        $contrast->addColors($colors);
        $combinations = $contrast->getCombinations(0.0);
        $this->assertCount(6, $combinations);

        $combinations = $contrast->getCombinations(ColorContrast::MIN_CONTRAST_AA);
    }

    /**
     * @expectedException ColorContrast\InvalidColorException
     */
    public function testColorAdditionWithInvalidColor()
    {
        $contrast = new ColorContrast();
        $contrast->addColors('red');
    }

}