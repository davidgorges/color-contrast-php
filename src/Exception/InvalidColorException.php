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

namespace ColorContrast\Exception;

/**
 * The InvalidColorException is thrown when ColorContrast could not detect a color
 * from a parameter, e.g. 'red'.
 */
class InvalidColorException extends \Exception
{
}
