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

namespace ColorContrast\Tests;

use ColorContrast\Color;
use ColorContrast\ContrastAlgorithm\LuminosityContrast;
use PHPUnit\Framework\TestCase;

final class LuminosityAlgorithmTest extends TestCase
{
    private LuminosityContrast $algorithm;

    protected function setUp(): void
    {
        $this->algorithm = new LuminosityContrast();
    }

    public function testContrastBlackWhite(): void
    {
        $black = Color::fromHex(0x000000);
        $white = Color::fromHex(0xFFFFFF);
        $contrast = $this->algorithm->calculate($black, $white);

        $this->assertEqualsWithDelta(21, $contrast, 0.001, 'Contrast between black and white should be 21');
    }

    public function testContrastWhiteBlack(): void
    {
        $white = Color::fromHex(0xFFFFFF);
        $black = Color::fromHex(0x000000);
        $contrast = $this->algorithm->calculate($white, $black);

        $this->assertEqualsWithDelta(21, $contrast, 0.001, 'Contrast should be symmetric');
    }

    public function testContrastRedWhite(): void
    {
        $red = Color::fromHex(0xFF0000);
        $white = Color::fromHex(0xFFFFFF);
        $contrast = $this->algorithm->calculate($red, $white);

        $this->assertEqualsWithDelta(4, $contrast, 0.01, 'Contrast between red and white should be 4ish');
    }

    public function testContrastGreenWhite(): void
    {
        $green = Color::fromHex(0x00FF00);
        $white = Color::fromHex(0xFFFFFF);
        $contrast = $this->algorithm->calculate($green, $white);

        $this->assertGreaterThan(1.0, $contrast);
        $this->assertLessThan(2.0, $contrast);
    }

    public function testContrastBlueWhite(): void
    {
        $blue = Color::fromHex(0x0000FF);
        $white = Color::fromHex(0xFFFFFF);
        $contrast = $this->algorithm->calculate($blue, $white);

        $this->assertGreaterThan(8.0, $contrast);
        $this->assertLessThan(9.0, $contrast);
    }

    public function testContrastSameColor(): void
    {
        $color = Color::fromHex(0x808080);
        $contrast = $this->algorithm->calculate($color, $color);

        $this->assertEqualsWithDelta(1.0, $contrast, 0.001, 'Contrast of same color should be 1');
    }

    public function testContrastIsSymmetric(): void
    {
        $color1 = Color::fromHex(0xFF8040);
        $color2 = Color::fromHex(0x4080FF);

        $contrast1 = $this->algorithm->calculate($color1, $color2);
        $contrast2 = $this->algorithm->calculate($color2, $color1);

        $this->assertEqualsWithDelta($contrast1, $contrast2, 0.001, 'Contrast should be symmetric');
    }

    public function testContrastAlwaysGreaterThanOrEqualToOne(): void
    {
        $testCases = [
            [0x000000, 0x000000],
            [0xFFFFFF, 0xFFFFFF],
            [0xFF0000, 0x00FF00],
            [0x123456, 0xABCDEF],
            [0x808080, 0x808080],
        ];

        foreach ($testCases as [$hex1, $hex2]) {
            $color1 = Color::fromHex($hex1);
            $color2 = Color::fromHex($hex2);
            $contrast = $this->algorithm->calculate($color1, $color2);

            $this->assertGreaterThanOrEqual(1.0, $contrast, sprintf(
                'Contrast between %06X and %06X should be >= 1',
                $hex1,
                $hex2
            ));
        }
    }

    public function testWCAGExampleContrasts(): void
    {
        // Test some WCAG example color combinations

        // AA Large compliant (3:1)
        $color1 = Color::fromHex(0x777777);
        $white = Color::fromHex(0xFFFFFF);
        $contrast = $this->algorithm->calculate($color1, $white);
        $this->assertGreaterThan(3.0, $contrast);

        // AA compliant (4.5:1)
        $color2 = Color::fromHex(0x767676);
        $contrast = $this->algorithm->calculate($color2, $white);
        $this->assertGreaterThan(4.5, $contrast);

        // AAA compliant (7:1)
        $color3 = Color::fromHex(0x595959);
        $contrast = $this->algorithm->calculate($color3, $white);
        $this->assertGreaterThan(7.0, $contrast);
    }
}

