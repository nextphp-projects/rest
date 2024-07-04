<?php

/**
 * This file is part of the NextPHP REST package.
 *
 * (c) [Your Name] <your.email@example.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 * @license https://opensource.org/licenses/MIT MIT License
 */

 namespace NextPHP\Resr;

 #[\Attribute(\Attribute::TARGET_CLASS)]
 /**
  * Class Controller
  *
  * A simple implementation of a PSR-7 HTTP message interface and PSR-12 extended coding style guide.
  * This class represents the Controller attribute.
  *
  * @package NextPHP\Rest
  */
class Controller
{
    public function __construct(public string $description = '') {}
}