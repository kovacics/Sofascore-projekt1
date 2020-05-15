<?php
declare(strict_types=1);

namespace src\route;

abstract class Route
{
    private static array $map = [];

    public abstract function match(string $input): bool;

    public abstract function generate(array $array = []): string;

    public static function register(string $name, Route $automaton): void
    {
        self::$map[$name] = $automaton;
    }

    public static function get($name = null)
    {
        if ($name === null) return self::$map;
        return self::$map[$name] ?? null;
    }

    abstract public function getValue($key);
}