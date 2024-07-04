<?php

namespace NextPHP\Rest\DI;

use ReflectionClass;
use Exception;

class Container
{
    private array $instances = [];

    public function get(string $class)
    {
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        if (!class_exists($class)) {
            throw new Exception("Class \"$class\" does not exist");
        }

        $reflectionClass = new ReflectionClass($class);
        $constructor = $reflectionClass->getConstructor();
        if ($constructor === null) {
            $instance = new $class();
        } else {
            $parameters = $constructor->getParameters();
            $dependencies = array_map(fn($param) => $this->resolveParameter($param), $parameters);
            $instance = $reflectionClass->newInstanceArgs($dependencies);
        }

        $this->instances[$class] = $instance;
        return $instance;
    }

    private function resolveParameter($param)
    {
        $type = $param->getType();
        if ($type && !$type->isBuiltin()) {
            return $this->get($type->getName());
        }

        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }

        throw new Exception("Cannot resolve parameter " . $param->getName());
    }

    public function set(string $class, $instance): void
    {
        $this->instances[$class] = $instance;
    }
}