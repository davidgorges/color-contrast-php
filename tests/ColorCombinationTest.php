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
use ColorContrast\ColorCombination;
use PHPUnit\Framework\TestCase;

final class ColorCombinationTest extends TestCase
{
    public function testConstructor(): void
    {
        $foreground = new Color(255, 0, 0);
        $background = new Color(0, 0, 255);
        $contrast = 5.5;

        $combination = new ColorCombination($foreground, $background, $contrast);

        $this->assertSame($foreground, $combination->getForeground());
        $this->assertSame($background, $combination->getBackground());
        $this->assertSame($contrast, $combination->getContrast());
    }

    public function testGetForeground(): void
    {
        $foreground = new Color(128, 128, 128);
        $background = new Color(255, 255, 255);
        $combination = new ColorCombination($foreground, $background, 3.0);

        $result = $combination->getForeground();

        $this->assertInstanceOf(Color::class, $result);
        $this->assertSame(128, $result->getRed());
        $this->assertSame(128, $result->getGreen());
        $this->assertSame(128, $result->getBlue());
    }

    public function testGetBackground(): void
    {
        $foreground = new Color(128, 128, 128);
        $background = new Color(64, 32, 16);
        $combination = new ColorCombination($foreground, $background, 2.5);

        $result = $combination->getBackground();

        $this->assertInstanceOf(Color::class, $result);
        $this->assertSame(64, $result->getRed());
        $this->assertSame(32, $result->getGreen());
        $this->assertSame(16, $result->getBlue());
    }

    public function testGetContrast(): void
    {
        $foreground = new Color(0, 0, 0);
        $background = new Color(255, 255, 255);
        $contrast = 21.0;
        $combination = new ColorCombination($foreground, $background, $contrast);

        $this->assertSame($contrast, $combination->getContrast());
    }

    public function testImmutability(): void
    {
        $foreground = new Color(255, 0, 0);
        $background = new Color(0, 0, 255);
        $combination = new ColorCombination($foreground, $background, 5.5);

        // Since Color is readonly, we can't modify it
        // Just verify that the combination returns the same instances
        $this->assertSame($foreground, $combination->getForeground());
        $this->assertSame($background, $combination->getBackground());
    }

    public function testWithDifferentContrasts(): void
    {
        $foreground = new Color(100, 100, 100);
        $background = new Color(200, 200, 200);

        $combinations = [
            new ColorCombination($foreground, $background, 1.0),
            new ColorCombination($foreground, $background, 4.5),
            new ColorCombination($foreground, $background, 7.0),
            new ColorCombination($foreground, $background, 21.0),
        ];

        $this->assertSame(1.0, $combinations[0]->getContrast());
        $this->assertSame(4.5, $combinations[1]->getContrast());
        $this->assertSame(7.0, $combinations[2]->getContrast());
        $this->assertSame(21.0, $combinations[3]->getContrast());
    }
}
