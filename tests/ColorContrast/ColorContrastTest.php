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

use ColorContrast\ColorContrast;
use ColorContrast\Exception\InvalidColorException;
use PHPUnit\Framework\TestCase;

class ColorContrastTest extends TestCase
{
    /**
     * Test different ways to add colors.
     */
    public function testColorAdditionWithMultipleArguments(): void
    {
        $contrast = new ColorContrast();
        $contrast->addColors('#ff9900', 0x223399, 0x0, '#ffff00');
        $combinations = $contrast->getCombinations(0);
        $this->assertCount(12, $combinations);

        $contrast->clear();
        $combinations = $contrast->getCombinations(0.0);
        $this->assertCount(0, $combinations);

        $colors = ['#ff0000', '#000000', '#ffffff'];
        $contrast->addColors($colors);
        $combinations = $contrast->getCombinations(0.0);
        $this->assertCount(6, $combinations);

        $combinations = $contrast->getCombinations(ColorContrast::MIN_CONTRAST_AA);
    }

    public function testColorAdditionWithInvalidColor(): void
    {
        $contrast = new ColorContrast();
        $this->expectException(InvalidColorException::class);
        $contrast->addColors('red');
    }

    /**
     * functional.
     */
    public function testLightOrDark(): void
    {
        $contrast = new ColorContrast();
        $complimentaryColor = $contrast->complimentaryTheme('ffffff');
        $this->assertEquals(ColorContrast::DARK, $complimentaryColor, 'The algorithm should recommend a dark complimentary color for ef4444');
        $complimentaryColor = $contrast->complimentaryTheme('ef4444');
        $this->assertEquals(ColorContrast::LIGHT, $complimentaryColor, 'The algorithm should recommend a light complimentary color for ef4444');
        $complimentaryColor = $contrast->complimentaryTheme('ffcc00');
        $this->assertEquals(ColorContrast::DARK, $complimentaryColor, 'The algorithm should recommend a dark complimentary color for ffcc00');
        $complimentaryColor = $contrast->complimentaryTheme('ccff00');
        $this->assertEquals(ColorContrast::DARK, $complimentaryColor, 'The algorithm should recommend a dark complimentary color for ccff00');
        $complimentaryColor = $contrast->complimentaryTheme('00ccff');
        $this->assertEquals(ColorContrast::DARK, $complimentaryColor, 'The algorithm should recommend a light complimentary color for 00ccff');
    }
}
