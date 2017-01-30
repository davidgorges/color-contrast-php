# ColorContrast
Small library to find valid color combinations for a given contrast threshold. The contrast calculation algorithm is based on the WCAG 2.0.

[![Build Status](https://travis-ci.org/davidgorges/color-contrast-php.svg?branch=master)](https://travis-ci.org/davidgorges/color-contrast-php)

## Install

Using composer:
```bash
composer require davidgorges/color-contrast
```

## Usage
````php
    use ColorContrast\ColorContrast;

    /* ... */
    
    $contrast = new ColorContrast();
    $contrast->addColors(0xff0000, 0x002200, 0x0022ff, 0xffffff);
    $combinations = $contrast->getCombinations(ColorContrast::MIN_CONTRAST_AAA);
    foreach ($combinations as $combination) {
        printf("#%s on the Background color #%s has a contrast value of %f \n", $combination->getForeground(), $combination->getBackground(), $combination->getContrast());
    }
````

this would output:
````
    #FFFFFF on the Background color #002200 has a contrast value of 17.949476
    #FFFFFF on the Background color #0022FF has a contrast value of 8.033817
    #002200 on the Background color #FFFFFF has a contrast value of 17.949476
    #0022FF on the Background color #FFFFFF has a contrast value of 8.033817
````

### Dark or Light Complimentary Color

````
    $contrast = new ColorContrast();
    $complimentary = $contrast->complimentaryTheme('#ffffff'); // returns ColorContrast::DARK to indicate you should use a dark color on this background
    
    if($complimentary == ColorContrast::LIGHT) 
    {
        // Use a light font
    } elseif($complimentary == ColorContrast::DARK) 
    {
        // use a dark font
    }
````
## Thanks

* [Calculating Color Contrast with PHP](http://www.splitbrain.org/blog/2008-09/18-calculating_color_contrast_with_php) by Andreas Gohr
* [jxnblk/colorable](https://github.com/jxnblk/colorable) JavaScript Library that inspired ColorContrast heavily

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
