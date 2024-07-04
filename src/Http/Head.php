<?php

/*
 * This file is part of the NextPHP package.
 *
 * (c) Vedat Yıldırım <vedat@nextphp.io>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace NextPHP\Rest\Http;

#[\Attribute]
/**
 * Class Head
 *
 * A simple implementation of a PSR-7 HTTP message interface and PSR-12 extended coding style guide.
 * This class represents the HEAD HTTP method attribute.
 *
 * @package NextPHP\Rest\Attributes
 */
class Head
{
    /**
     * @var string The path associated with the HEAD attribute.
     */
    public string $path;

    /**
     * Head constructor.
     *
     * @param string $path The path for the HEAD attribute.
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }
}