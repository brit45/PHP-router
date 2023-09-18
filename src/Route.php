<?php

namespace Root22\Router;

/**
 * Route
 * 
 * @property string $path link of url.
 * @property string $url Regex path of url.
 * @property string $name name of route.
 * @property array $controller
 * @property array $middleware_
 */
class Route {

    public string $path;
    public string $url;
    public string $name = '';
    public array $controller;
    public array $middleware_ = [];
    
    /**
     * __construct
     *
     * @param string $path link of url.
     * @param array $callable Controller with Action.
     * @param string $name name of route (Optionel).
     * @return void
     */
    public function __construct(string $path, $callable, ?string $name) {

        $this->url = $path;
        $this->path = trim($path, '/');
        $this->path = preg_replace("#^(.*)$#", "#^($1)$#i", $this->path);
        $this->controller = $callable;

        if($name)
            $this->name = $name;
    }
    
    /**
     * with
     * 
     * Specify type of dynamic variable in url.
     * ```php
     * ('/img/{type}-{width}-{height}', [])->with('type', '[a-z0-9]+')->with('width', '[0-9]+')->with('height', '[0-9]+');
     * ```
     * @param  string $name Key name.
     * @param  string $regex Regex for variable.
     * @return self
     */
    public function with(string $name, string $regex) {

        $this->path = preg_replace("#{($name)}#i", ("$1" !== $name)?"(?P<$name>$regex)":'', $this->path);

        return $this;
    }
    
    /**
     * middleware
     *
     * @param  array $middleware Add list of middlewares.
     * @return self
     */
    public function middleware(...$middleware) {

        $this->middleware_ = $middleware;

        return $this;
    }
}