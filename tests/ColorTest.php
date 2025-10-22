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
use ColorContrast\Exception\InvalidColorException;
use PHPUnit\Framework\TestCase;

final class ColorTest extends TestCase
{
    public function testConstructorWithValidRgbValues(): void
    {
        $color = new Color(255, 128, 64);

        $this->assertSame(255, $color->getRed());
        $this->assertSame(128, $color->getGreen());
        $this->assertSame(64, $color->getBlue());
    }

    public function testConstructorWithMinimumValues(): void
    {
        $color = new Color(0, 0, 0);

        $this->assertSame(0, $color->getRed());
        $this->assertSame(0, $color->getGreen());
        $this->assertSame(0, $color->getBlue());
    }

    public function testConstructorWithMaximumValues(): void
    {
        $color = new Color(255, 255, 255);

        $this->assertSame(255, $color->getRed());
        $this->assertSame(255, $color->getGreen());
        $this->assertSame(255, $color->getBlue());
    }

    public function testConstructorThrowsExceptionForRedTooLow(): void
    {
        $this->expectException(InvalidColorException::class);
        $this->expectExceptionMessage('RGB values must be between 0 and 255');

        new Color(-1, 0, 0);
    }

    public function testConstructorThrowsExceptionForRedTooHigh(): void
    {
        $this->expectException(InvalidColorException::class);
        $this->expectExceptionMessage('RGB values must be between 0 and 255');

        new Color(256, 0, 0);
    }

    public function testConstructorThrowsExceptionForGreenTooLow(): void
    {
        $this->expectException(InvalidColorException::class);
        $this->expectExceptionMessage('RGB values must be between 0 and 255');

        new Color(0, -1, 0);
    }

    public function testConstructorThrowsExceptionForGreenTooHigh(): void
    {
        $this->expectException(InvalidColorException::class);
        $this->expectExceptionMessage('RGB values must be between 0 and 255');

        new Color(0, 256, 0);
    }

    public function testConstructorThrowsExceptionForBlueTooLow(): void
    {
        $this->expectException(InvalidColorException::class);
        $this->expectExceptionMessage('RGB values must be between 0 and 255');

        new Color(0, 0, -1);
    }

    public function testConstructorThrowsExceptionForBlueTooHigh(): void
    {
        $this->expectException(InvalidColorException::class);
        $this->expectExceptionMessage('RGB values must be between 0 and 255');

        new Color(0, 0, 256);
    }

    public function testFromHexWithBlack(): void
    {
        $color = Color::fromHex(0x000000);

        $this->assertSame(0, $color->getRed());
        $this->assertSame(0, $color->getGreen());
        $this->assertSame(0, $color->getBlue());
    }

    public function testFromHexWithWhite(): void
    {
        $color = Color::fromHex(0xFFFFFF);

        $this->assertSame(255, $color->getRed());
        $this->assertSame(255, $color->getGreen());
        $this->assertSame(255, $color->getBlue());
    }

    public function testFromHexWithRed(): void
    {
        $color = Color::fromHex(0xFF0000);

        $this->assertSame(255, $color->getRed());
        $this->assertSame(0, $color->getGreen());
        $this->assertSame(0, $color->getBlue());
    }

    public function testFromHexWithGreen(): void
    {
        $color = Color::fromHex(0x00FF00);

        $this->assertSame(0, $color->getRed());
        $this->assertSame(255, $color->getGreen());
        $this->assertSame(0, $color->getBlue());
    }

    public function testFromHexWithBlue(): void
    {
        $color = Color::fromHex(0x0000FF);

        $this->assertSame(0, $color->getRed());
        $this->assertSame(0, $color->getGreen());
        $this->assertSame(255, $color->getBlue());
    }

    public function testFromHexWithArbitraryColor(): void
    {
        $color = Color::fromHex(0xABCDEF);

        $this->assertSame(0xAB, $color->getRed());
        $this->assertSame(0xCD, $color->getGreen());
        $this->assertSame(0xEF, $color->getBlue());
    }

    public function testFromHexStringWithSixCharacters(): void
    {
        $color = Color::fromHexString('FF8040');

        $this->assertSame(255, $color->getRed());
        $this->assertSame(128, $color->getGreen());
        $this->assertSame(64, $color->getBlue());
    }

    public function testFromHexStringWithHash(): void
    {
        $color = Color::fromHexString('#FF8040');

        $this->assertSame(255, $color->getRed());
        $this->assertSame(128, $color->getGreen());
        $this->assertSame(64, $color->getBlue());
    }

    public function testFromHexStringWithThreeCharacters(): void
    {
        $color = Color::fromHexString('F80');

        $this->assertSame(255, $color->getRed());
        $this->assertSame(136, $color->getGreen());
        $this->assertSame(0, $color->getBlue());
    }

    public function testFromHexStringWithThreeCharactersAndHash(): void
    {
        $color = Color::fromHexString('#F80');

        $this->assertSame(255, $color->getRed());
        $this->assertSame(136, $color->getGreen());
        $this->assertSame(0, $color->getBlue());
    }

    public function testFromHexStringWithLowercase(): void
    {
        $color = Color::fromHexString('ff8040');

        $this->assertSame(255, $color->getRed());
        $this->assertSame(128, $color->getGreen());
        $this->assertSame(64, $color->getBlue());
    }

    public function testFromHexStringThrowsExceptionForInvalidLength(): void
    {
        $this->expectException(InvalidColorException::class);
        $this->expectExceptionMessage('Hex string must be 3 or 6 characters long');

        Color::fromHexString('FF80');
    }

    public function testFromHexStringThrowsExceptionForInvalidCharacters(): void
    {
        $this->expectException(InvalidColorException::class);
        $this->expectExceptionMessage('Hex string must contain only hexadecimal characters');

        Color::fromHexString('GGGGGG');
    }

    public function testFromHexStringThrowsExceptionForInvalidCharactersWithHash(): void
    {
        $this->expectException(InvalidColorException::class);
        $this->expectExceptionMessage('Hex string must contain only hexadecimal characters');

        Color::fromHexString('#GGGGGG');
    }

    public function testToHex(): void
    {
        $color = new Color(255, 128, 64);

        $this->assertSame(0xFF8040, $color->toHex());
    }

    public function testToHexWithBlack(): void
    {
        $color = new Color(0, 0, 0);

        $this->assertSame(0x000000, $color->toHex());
    }

    public function testToHexWithWhite(): void
    {
        $color = new Color(255, 255, 255);

        $this->assertSame(0xFFFFFF, $color->toHex());
    }

    public function testToHexString(): void
    {
        $color = new Color(255, 128, 64);

        $this->assertSame('#FF8040', $color->toHexString());
    }

    public function testToHexStringWithoutHash(): void
    {
        $color = new Color(255, 128, 64);

        $this->assertSame('FF8040', $color->toHexString(false));
    }

    public function testToHexStringWithBlack(): void
    {
        $color = new Color(0, 0, 0);

        $this->assertSame('#000000', $color->toHexString());
    }

    public function testToHexStringWithWhite(): void
    {
        $color = new Color(255, 255, 255);

        $this->assertSame('#FFFFFF', $color->toHexString());
    }

    public function testRoundTripHexToColorToHex(): void
    {
        $original = 0xABCDEF;
        $color = Color::fromHex($original);

        $this->assertSame($original, $color->toHex());
    }

    public function testRoundTripHexStringToColorToHexString(): void
    {
        $original = 'ABCDEF';
        $color = Color::fromHexString($original);

        $this->assertSame(strtoupper($original), $color->toHexString(false));
    }

    public function testRoundTripColorToHexToColor(): void
    {
        $original = new Color(123, 45, 67);
        $hex = $original->toHex();
        $restored = Color::fromHex($hex);

        $this->assertSame($original->getRed(), $restored->getRed());
        $this->assertSame($original->getGreen(), $restored->getGreen());
        $this->assertSame($original->getBlue(), $restored->getBlue());
    }
}
