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
 * Class RouteGroup
 *
 * A simple implementation of a PSR-7 HTTP message interface and PSR-12 extended coding style guide.
 * This class represents a route group attribute with a prefix.
 *
 * @package NextPHP\Rest\Attributes
 */
class RouteGroup
{
    /**
     * @var string The route group prefix.
     */
    public string $prefix;

    /**
     * RouteGroup constructor.
     *
     * @param string $prefix The prefix for the route group.
     */
    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
    }
}