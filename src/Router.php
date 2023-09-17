<?php

namespace Root22\Router;

use Root22\Router\Commands\console;
use Root22\Router\Controllers\HomeController;
use Root22\Router\Middlewares\Guest;
use Root22\Router\Exceptions\RouterException;

class Router {

    /**
     * @var Route[] $route
     */
    private $route = [];
    public Request $request;

    public function __construct() {}

    private function add(string $method, string $path, array|callable $callable, ?string $name = '') {

        $route = new Route($path, $callable, $name);
        $this->route[$method][] = $route;

        return $route;

    }

    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------
    //?------------------------------- PUBLIC SETTER ROUTING
    //?----------------------------------------------------------------------------
    //?----------------------------------------------------------------------------


    public function GET(string $path, array|callable $callable, ?string $name = '') {

        return $this->add('GET',$path,$callable,$name);
    }

    public function POST(string $path, array|callable $callable, ?string $name = '') {

        return $this->add('POST',$path,$callable,$name);
    }

    public function DELETE(string $path, array|callable $callable, ?string $name = '') {

        return $this->add('DELETE',$path,$callable,$name);
    }

    public function PUT(string $path, array|callable $callable, ?string $name = '') {

        return $this->add('PUT',$path,$callable,$name);
    }

    public function PATCH(string $path, array|callable $callable, ?string $name = '') {

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


    public function run(array $argv) {

        if(!empty($argv)) {

            new console($argv);
            die;
        }

        $this->request = new Request;

        return $reponse = new Responce($this->matchUrl());
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

    public function list_routes() : array {

        return $this->route;
    }
}   