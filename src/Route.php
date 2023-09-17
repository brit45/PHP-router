<?php

namespace Root22\Router;

class Route {

    public string $path;
    public string $url;
    public string $name = '';
    public array $controller;
    public array $middleware_ = [];

    public function __construct(string $path, $callable, ?string $name) {

        $this->url = $path;
        $this->path = trim($path, '/');
        $this->path = preg_replace("#^(.*)$#", "#^($1)$#i", $this->path);
        $this->controller = $callable;

        if($name)
            $this->name = $name;
    }

    public function with(string $name, string $regex) {

        $this->path = preg_replace("#{($name)}#i", ("$1" !== $name)?"(?P<$name>$regex)":'', $this->path);

        return $this;
    }

    public function middleware(...$middleware) {

        $this->middleware_ = $middleware;

        return $this;
    }
}