<?php
use ColorContrast\ColorContrast;

class LuminisotyAlgorithmTest extends PHPUnit_Framework_TestCase
{
    public function testContrastBlackWhite()
    {
        $algorithm = new \ColorContrast\ContrastAlgorithm\LuminosityContrast();
        $black = new \MischiefCollective\ColorJizz\Formats\Hex(0x000000);
        $white = new \MischiefCollective\ColorJizz\Formats\Hex(0xffffff);
        $contrast = $algorithm->calculate($black, $white);

        $this->assertEquals(21, $contrast, 'Contrast between black and white should be 21', 0.001);
    }
    /**
     * Test different ways to add colors
     */
    public function testContrastRedWhite()
    {
        $algorithm = new \ColorContrast\ContrastAlgorithm\LuminosityContrast();
        $red = new \MischiefCollective\ColorJizz\Formats\Hex(0xff0000);
        $white = new \MischiefCollective\ColorJizz\Formats\Hex(0xffffff);
        $contrast = $algorithm->calculate($red, $white);

        $this->assertEquals(4, $contrast, 'Contrast between red and white should be 4ish', 0.01);
    }

}