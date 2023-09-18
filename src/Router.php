<?php

namespace Root22\Router;

use Root22\Router\Commands\console;
use Root22\Router\Exceptions\RouterException;



/**
 * Router
 * 
 * @property Route[] $route List of route by methods.
 * @property Request $request Uri request.
 */
class Router {

    private $route = [];
    
    public Request $request;

    public function __construct() {}
    
    /**
     * add - Create new route object.
     *
     * @param  string $method
     * @param  string $path
     * @param  array $callable
     * @param  string $name
     * @return Route
     */
    private function add(string $method, string $path, array $callable, ?string $name = '') : Route {

        $route = new Route($path, $callable, $name);
        $this->route[$method][] = $route;

        return $route;

    }

    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------
    //?------------------------------- PUBLIC SETTER ROUTING
    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------

    
    /**
     * GET
     * 
     * Use connection GET.
     *
     * @param  string $path link of url.
     * 
     * ## Simple uri:
     * ```php
     * '/about'
     * ```
     * ## Dynamic uri:
     * ```php
     * 'about/{slug}-{id}-{ref}'
     * ```
     * @param  array $callable controller with action.
     * @param  string $name name of route.
     * @return Route
     */
    public function GET(string $path, array $callable, ?string $name = '') : Route {

        return $this->add('GET',$path,$callable,$name);
    }
    
    /**
     * POST
     * 
     * Use connection POST.
     * 
     * ## Simple uri:
     * ```php
     * '/about'
     * ```
     * ## Dynamic uri:
     * ```php
     * 'about/{slug}-{id}-{ref}'
     * ```
     * @param  string $path link of url.
     * @param  array $callable controller with action.
     * @param  string $name name of route.
     * @return Route
     */
    public function POST(string $path, array $callable, ?string $name = '') : Route {

        return $this->add('POST',$path,$callable,$name);
    }

    /**
     * DELETE
     * 
     * Use connection DELETE.
     * 
     * ## Simple uri:
     * ```php
     * '/about'
     * ```
     * ## Dynamic uri:
     * ```php
     * 'about/{slug}-{id}-{ref}'
     * ```
     * @param  string $path link of url.
     * @param  array $callable controller with action.
     * @param  string $name name of route.
     * @return Route
     */
    public function DELETE(string $path, array $callable, ?string $name = '') : Route {

        return $this->add('DELETE',$path,$callable,$name);
    }

    /**
     * PUT
     * 
     * Use connection PUT.
     * 
     * ## Simple uri:
     * ```php
     * '/about'
     * ```
     * ## Dynamic uri:
     * ```php
     * 'about/{slug}-{id}-{ref}'
     * ```
     * @param  string $path link of url.
     * @param  array $callable controller with action.
     * @param  string $name name of route.
     * @return Route
     */
    public function PUT(string $path, array $callable, ?string $name = '') : Route {

        return $this->add('PUT',$path,$callable,$name);
    }

    /**
     * PATCH
     * 
     * Use connection PATCH.
     * 
     * ## Simple uri:
     * ```php
     * '/about'
     * ```
     * ## Dynamic uri:
     * ```php
     * 'about/{slug}-{id}-{ref}'
     * ```
     * @param  string $path link of url.
     * @param  array $callable controller with action.
     * @param  string $name name of route.
     * @return Route
     */
    public function PATCH(string $path, array $callable, ?string $name = '') : Route {

        return $this->add('PATCH',$path,$callable,$name);
    }

    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------
    //?------------------------------- END PUBLIC SETTER ROUTING
    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------



    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------
    //?------------------------------- GENERATE URL FROM ROUTE NAME
    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------

        
    /**
     * route
     * ! IN DEVELOPMENT NOT IMPLEMENTED !
     * @param  string $nameRoute Generated dynamic route uri.
     * @return string|RouterException
     */
    public function route(string $nameRoute) : string | RouterException {

        foreach($this->route as $k => $v) {
            
            foreach($v as $route) {

                if($route->name === $nameRoute) {

                    return $route->path;
                }
            }
        }

        return throw new RouterException('Cannot Found Route Name.');
    }


    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------
    //?------------------------------- END GENERATE URL FROM ROUTE NAME
    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------


    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------
    //?------------------------------- HANDLE ROUTING
    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------

    
    /**
     * run
     *
     * @param  array $argv
     * @return void
     */
    public function run(array $argv) {

        if(!empty($argv)) {

            new console($argv);
            die;
        }

        $this->request = new Request;

        return new Responce($this->matchUrl());
    }


    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------
    //?------------------------------- END HANDLE ROUTING
    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------


    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------
    //?------------------------------- ACCESS VALIDATED BY MIDDLEWARES
    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------

    
    /**
     * validate_connection
     *
     * @param  Route $route Verified permissions with middleware components.
     * @return bool
     */
    private function validate_connection(Route $route) : bool {

        if(!empty($route->middleware_)) {

            foreach($route->middleware_ as $v) {

                $c = new $v;

                if(!$c->validate())
                    return false;
            }
            
        }

        return true;

    }

    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------
    //?------------------------------- END ACCESS VALIDATED BY MIDDLEWARES
    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------

    
    /**
     * matchUrl
     * 
     * Verify if the path that user used is exist in route.
     *
     * @return Root22\Controllers\Controller|void
     */
    private function matchUrl() {

        foreach($this->route[$this->request->Data()['method']] as $route) {

            if(preg_match($route->path, $this->request->Data()['url'], $matches)) {

                $controller = new $route->controller[0]($this->request, $route);
                $action = $route->controller[1];

                $data = [];

                foreach($matches as $k => $v) {

                    if(!preg_match("#([0-9]+)#i", $k)) {
                        
                        $data[$k] = $v;
                    }
                }

                unset($matches);

                if($this->validate_connection($route)) {
                    
                    return call_user_func_array([$controller, $action], $data);
                }
                else {

                    return header('Access Denied', response_code:403);
                }
            }

        }

        return header('Not Found', response_code: 404);

    }
    
    /**
     * list_routes
     * 
     * list the entire routes.
     * 
     * @return Route[]
     */
    public function list_routes() : array {

        return $this->route;
    }
}   