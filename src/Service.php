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

 namespace NextPHP\Rest;

 #[\Attribute(\Attribute::TARGET_CLASS)]
 /**
  * Class Service
  *
  * A simple implementation of a PSR-7 HTTP message interface and PSR-12 extended coding style guide.
  * This class represents the Service attribute.
  *
  * @package NextPHP\Rest
  */
class Service
{
    public function __construct(public string $description = '') {}
}
