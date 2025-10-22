# Color Contrast PHP

[![CI](https://github.com/davidgorges/color-contrast-php/workflows/CI/badge.svg)](https://github.com/davidgorges/color-contrast-php/actions)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-blue.svg)](https://www.php.net/)
[![PHPStan](https://img.shields.io/badge/PHPStan-max%20level-brightgreen.svg)](https://phpstan.org/)

A modern PHP 8.1+ library for calculating color contrast ratios and finding accessible color combinations according to WCAG 2.0 guidelines.

## Features

- **Zero dependencies** (except dev dependencies for testing and code quality)
- **Modern PHP 8.1+** with strict types, readonly classes, and constructor property promotion
- **WCAG 2.0 compliant** contrast ratio calculations
- **Comprehensive test suite** with 100+ test cases
- **PHPStan level max** static analysis
- **PSR-12 compliant** code style
- **Fully typed** for better IDE support and type safety

## Requirements

- PHP 8.1 or higher

## Installation

Install via Composer:

```bash
composer require davidgorges/color-contrast-php
```

## Usage

### Basic Usage

```php
use ColorContrast\ColorContrast;

$contrast = new ColorContrast();

// Add colors in various formats
$contrast->addColors('#FF0000', '#FFFFFF', '00FF00', 0x0000FF);

// Get all color combinations with their contrast ratios
$combinations = $contrast->getCombinations();

foreach ($combinations as $combination) {
    echo sprintf(
        "Foreground: %s, Background: %s, Contrast: %.2f\n",
        $combination->getForeground()->toHexString(),
        $combination->getBackground()->toHexString(),
        $combination->getContrast()
    );
}
```

### WCAG 2.0 Compliance

Filter combinations by WCAG 2.0 contrast requirements:

```php
use ColorContrast\ColorContrast;

$contrast = new ColorContrast();
$contrast->addColors('#FF0000', '#FFFFFF', '#000000', '#808080');

// Get combinations meeting WCAG AA standards (4.5:1)
$aaCombinations = $contrast->getCombinations(ColorContrast::MIN_CONTRAST_AA);

// Get combinations meeting WCAG AAA standards (7:1)
$aaaCombinations = $contrast->getCombinations(ColorContrast::MIN_CONTRAST_AAA);

// For large text (AA: 3:1, AAA: 4.5:1)
$aaLargeCombinations = $contrast->getCombinations(ColorContrast::MIN_CONTRAST_AA_LARGE);
$aaaLargeCombinations = $contrast->getCombinations(ColorContrast::MIN_CONTRAST_AAA_LARGE);
```

### Complementary Theme Detection

Determine whether to use light or dark text on a background:

```php
use ColorContrast\ColorContrast;

$contrast = new ColorContrast();

// Returns ColorContrast::DARK for light backgrounds
$theme = $contrast->complimentaryTheme('#FFFFFF');
echo $theme; // 'dark'

// Returns ColorContrast::LIGHT for dark backgrounds
$theme = $contrast->complimentaryTheme('#000000');
echo $theme; // 'light'

// Custom threshold (default is 128)
$theme = $contrast->complimentaryTheme('#808080', 100);
```

### Working with Color Objects

```php
use ColorContrast\Color;

// Create colors
$red = new Color(255, 0, 0);
$blue = Color::fromHex(0x0000FF);
$green = Color::fromHexString('#00FF00');
$yellow = Color::fromHexString('FFFF00'); // # is optional

// Short hex notation (e.g., #F00 -> #FF0000)
$shortRed = Color::fromHexString('F00');

// Access RGB values
echo $red->getRed();   // 255
echo $red->getGreen(); // 0
echo $red->getBlue();  // 0

// Convert to hex
echo $red->toHex();          // 16711680 (0xFF0000)
echo $red->toHexString();    // '#FF0000'
echo $red->toHexString(false); // 'FF0000'
```

### Custom Contrast Algorithms

Implement your own contrast calculation algorithm:

```php
use ColorContrast\ColorContrast;
use ColorContrast\ContrastAlgorithm\ContrastAlgorithmInterface;
use ColorContrast\Color;

class CustomAlgorithm implements ContrastAlgorithmInterface
{
    public function calculate(Color $foreground, Color $background): float
    {
        // Your custom contrast calculation
        return 5.0;
    }
}

$contrast = new ColorContrast(new CustomAlgorithm());
// or
$contrast->setAlgorithm(new CustomAlgorithm());
```

## WCAG 2.0 Standards

The library includes constants for WCAG 2.0 contrast ratios:

- `ColorContrast::MIN_CONTRAST_AA` (4.5) - Normal text AA
- `ColorContrast::MIN_CONTRAST_AAA` (7.0) - Normal text AAA
- `ColorContrast::MIN_CONTRAST_AA_LARGE` (3.0) - Large text AA
- `ColorContrast::MIN_CONTRAST_AAA_LARGE` (4.5) - Large text AAA

Large text is defined as 18pt+ or 14pt+ bold.

## Development

### Install Dependencies

```bash
composer install
```

### Run Tests

```bash
composer test
# or
vendor/bin/phpunit
```

### Code Style

Check code style with PHP_CodeSniffer (PSR-12):

```bash
composer phpcs
# or
vendor/bin/phpcs
```

### Static Analysis

Run PHPStan at max level:

```bash
composer phpstan
# or
vendor/bin/phpstan analyse
```

### Run All Checks

```bash
composer check
```

## Contributing

Contributions are welcome! Please ensure:

1. All tests pass
2. Code follows PSR-12 standards
3. PHPStan max level passes
4. Add tests for new features

## Thanks

- [Calculating Color Contrast with PHP](http://www.splitbrain.org/blog/2008-09/18-calculating_color_contrast_with_php) by Andreas Gohr
- [jxnblk/colorable](https://github.com/jxnblk/colorable) JavaScript Library that inspired ColorContrast 

## License
The MIT License (MIT)

Copyright (c) 2015 David Gorges

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

[1]:https://getcomposer.org/doc/04-schema.md#minimum-stability
