<?php

namespace Framework\Container;

class Container
{
    private $definitions = [];

    public function get($id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            throw new \InvalidArgumentException('Undefined parameter"' . $id . '"');
        }
        return $this->definitions[$id];
    }

    public function set($id, $value): void
    {
        $this->definitions[$id] = $value;
    }
}