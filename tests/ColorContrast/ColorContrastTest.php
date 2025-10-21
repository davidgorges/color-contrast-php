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
use ColorContrast\ColorContrast;
use ColorContrast\Exception\InvalidColorException;
use PHPUnit\Framework\TestCase;

final class ColorContrastTest extends TestCase
{
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
        $this->assertIsArray($combinations);
    }

    public function testColorAdditionWithInvalidColor(): void
    {
        $contrast = new ColorContrast();
        $this->expectException(InvalidColorException::class);
        $contrast->addColors('red');
    }

    public function testAddColorWithColorObject(): void
    {
        $contrast = new ColorContrast();
        $color = new Color(255, 0, 0);
        $contrast->addColor($color);

        $combinations = $contrast->getCombinations(0.0);
        $this->assertCount(0, $combinations); // Only one color, no combinations
    }

    public function testAddColorWithHexInteger(): void
    {
        $contrast = new ColorContrast();
        $contrast->addColor(0xFF0000);

        $combinations = $contrast->getCombinations(0.0);
        $this->assertCount(0, $combinations);
    }

    public function testAddColorWithHexString(): void
    {
        $contrast = new ColorContrast();
        $contrast->addColor('FF0000');
        $contrast->addColor('#00FF00');

        $combinations = $contrast->getCombinations(0.0);
        $this->assertCount(2, $combinations);
    }

    public function testAddColorWithShortHexString(): void
    {
        $contrast = new ColorContrast();
        $contrast->addColor('F00');
        $contrast->addColor('#0F0');

        $combinations = $contrast->getCombinations(0.0);
        $this->assertCount(2, $combinations);
    }

    public function testGetCombinationsReturnsCorrectTypes(): void
    {
        $contrast = new ColorContrast();
        $contrast->addColors('#ff0000', '#000000', '#ffffff');

        $combinations = $contrast->getCombinations(0.0);

        $this->assertCount(6, $combinations);
        foreach ($combinations as $combination) {
            $this->assertInstanceOf(\ColorContrast\ColorCombination::class, $combination);
            $this->assertInstanceOf(Color::class, $combination->getForeground());
            $this->assertInstanceOf(Color::class, $combination->getBackground());
            $this->assertIsFloat($combination->getContrast());
        }
    }

    public function testGetCombinationsFiltersCorrectly(): void
    {
        $contrast = new ColorContrast();
        $contrast->addColors('#000000', '#ffffff');

        $allCombinations = $contrast->getCombinations(0.0);
        $this->assertCount(2, $allCombinations);

        $filteredCombinations = $contrast->getCombinations(10.0);
        $this->assertCount(2, $filteredCombinations);

        $tooHighFilter = $contrast->getCombinations(100.0);
        $this->assertCount(0, $tooHighFilter);
    }

    public function testLightOrDark(): void
    {
        $contrast = new ColorContrast();

        $complimentaryColor = $contrast->complimentaryTheme('ffffff');
        $this->assertSame(ColorContrast::DARK, $complimentaryColor, 'The algorithm should recommend a dark complimentary color for ffffff');

        $complimentaryColor = $contrast->complimentaryTheme('ef4444');
        $this->assertSame(ColorContrast::LIGHT, $complimentaryColor, 'The algorithm should recommend a light complimentary color for ef4444');

        $complimentaryColor = $contrast->complimentaryTheme('ffcc00');
        $this->assertSame(ColorContrast::DARK, $complimentaryColor, 'The algorithm should recommend a dark complimentary color for ffcc00');

        $complimentaryColor = $contrast->complimentaryTheme('ccff00');
        $this->assertSame(ColorContrast::DARK, $complimentaryColor, 'The algorithm should recommend a dark complimentary color for ccff00');

        $complimentaryColor = $contrast->complimentaryTheme('00ccff');
        $this->assertSame(ColorContrast::DARK, $complimentaryColor, 'The algorithm should recommend a dark complimentary color for 00ccff');
    }

    public function testComplimentaryThemeWithHexInteger(): void
    {
        $contrast = new ColorContrast();

        $complimentaryColor = $contrast->complimentaryTheme(0xFFFFFF);
        $this->assertSame(ColorContrast::DARK, $complimentaryColor);

        $complimentaryColor = $contrast->complimentaryTheme(0x000000);
        $this->assertSame(ColorContrast::LIGHT, $complimentaryColor);
    }

    public function testComplimentaryThemeWithColorObject(): void
    {
        $contrast = new ColorContrast();

        $white = new Color(255, 255, 255);
        $complimentaryColor = $contrast->complimentaryTheme($white);
        $this->assertSame(ColorContrast::DARK, $complimentaryColor);

        $black = new Color(0, 0, 0);
        $complimentaryColor = $contrast->complimentaryTheme($black);
        $this->assertSame(ColorContrast::LIGHT, $complimentaryColor);
    }

    public function testComplimentaryThemeWithCustomThreshold(): void
    {
        $contrast = new ColorContrast();

        $color = 'aaaaaa'; // Mid-gray
        $lowThreshold = $contrast->complimentaryTheme($color, 100);
        $highThreshold = $contrast->complimentaryTheme($color, 200);

        $this->assertIsString($lowThreshold);
        $this->assertIsString($highThreshold);
    }

    public function testSetCustomAlgorithm(): void
    {
        $mockAlgorithm = $this->createMock(\ColorContrast\ContrastAlgorithm\ContrastAlgorithmInterface::class);
        $mockAlgorithm->method('calculate')->willReturn(5.0);

        $contrast = new ColorContrast();
        $contrast->setAlgorithm($mockAlgorithm);
        $contrast->addColors('#000000', '#ffffff');

        $combinations = $contrast->getCombinations(0.0);
        $this->assertCount(2, $combinations);
        $this->assertSame(5.0, $combinations[0]->getContrast());
    }

    public function testWCAGConstants(): void
    {
        $this->assertSame(4.5, ColorContrast::MIN_CONTRAST_AA);
        $this->assertSame(7.0, ColorContrast::MIN_CONTRAST_AAA);
        $this->assertSame(3.0, ColorContrast::MIN_CONTRAST_AA_LARGE);
        $this->assertSame(4.5, ColorContrast::MIN_CONTRAST_AAA_LARGE);
    }

    public function testClearRemovesAllColors(): void
    {
        $contrast = new ColorContrast();
        $contrast->addColors('#ff0000', '#00ff00', '#0000ff');

        $combinations = $contrast->getCombinations(0.0);
        $this->assertCount(6, $combinations);

        $contrast->clear();
        $combinations = $contrast->getCombinations(0.0);
        $this->assertCount(0, $combinations);
    }
}

