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

use PHPUnit\Framework\TestCase;

class LuminosityAlgorithmTest extends TestCase
{
    public function testContrastBlackWhite(): void
    {
        $algorithm = new \ColorContrast\ContrastAlgorithm\LuminosityContrast();
        $black = new \MischiefCollective\ColorJizz\Formats\Hex(0x000000);
        $white = new \MischiefCollective\ColorJizz\Formats\Hex(0xffffff);
        $contrast = $algorithm->calculate($black, $white);

        $this->assertEqualsWithDelta(21, $contrast, 0.001, 'Contrast between black and white should be 21');
    }

    /**
     * Test different ways to add colors.
     */
    public function testContrastRedWhite(): void
    {
        $algorithm = new \ColorContrast\ContrastAlgorithm\LuminosityContrast();
        $red = new \MischiefCollective\ColorJizz\Formats\Hex(0xff0000);
        $white = new \MischiefCollective\ColorJizz\Formats\Hex(0xffffff);
        $contrast = $algorithm->calculate($red, $white);

        $this->assertEqualsWithDelta(4, $contrast, 0.01, 'Contrast between red and white should be 4ish');
    }
}
