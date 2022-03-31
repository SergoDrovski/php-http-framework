<?php

namespace Framework\Http\Router;

use Psr\Http\Message\ServerRequestInterface;

class Route
{
    public $name;
    public $pattern;
    public $handler;
    public $methods;
    public $tokens;

    public function  __construct($name, $pattern, $handler, array $methods, array $tokens = [])
    {
        $this->name = $name;
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->methods = $methods;
        $this->tokens = $tokens;
    }

    public function match(ServerRequestInterface $request): ?Result
    {
            // Проверяем совпадение метода в запросе с методами маршрутов
            if($this->methods && !\in_array($request->getMethod(), $this->methods, true)){
                return null;
            }

            // Получаем рег.выражение для извлечения пути из URLa

            $pattern = preg_replace_callback('~\{([^\}]+)\}~', function ($matches) {
                $argument = $matches[1];
                $replace = $this->tokens[$argument] ?? '[^}]+';
                return '(?P<' . $argument . '>' . $replace . ')';
            }, $this->pattern);

            $addSlashesPattern = addcslashes($pattern, '/');
            $path = $request->getUri()->getPath();

            if (preg_match("/{$addSlashesPattern}/m", $path, $matches)) {
                return new Result(
                    $this->name,
                    $this->handler,
                    array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY)
                );
            }
            return null;
    }

    public function generate($name, array $params = []): ?string
    {
        $arguments = array_filter($params);
        // Проверяем совпадение имени роута в коллекции маршрутов
        if ($name !== $this->name) {
            return null;
        }
        // с помощью рег.выражения получаем реальный URL с аргументами вместо маски
        return preg_replace_callback('~\{([^\}]+)\}~', function ($matches) use (&$arguments) {
            $argument = $matches[1];
            if (!array_key_exists($argument, $arguments)) {
                return new \InvalidArgumentException("Missing parameter {$argument}");
            }
            return $arguments[$argument];
        }, $this->pattern);
    }
}