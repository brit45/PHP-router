<?php

namespace Root22\Router\Commands;

use Root22\Router\Controllers\Controller;

class console {

    private array $cmd_list = [];

    public function __construct($argv) {

        array_shift($argv);

        $this->cmd_list = [

            'help' => [
                'description' => "Show help interface.\n",
                'prog' => 'help'
            ],
            'route:list' => [
                'description' => "Liste the routes in the router.\n",
                'prog' => 'list_route'
            ]
        ];

        if(empty($argv)) {
            
            return $this->list_route();
        }

        (string) $action = $argv[0];
        array_shift($argv);

        return $this->{$this->cmd_list[$action]['prog']}();
    }

    public function help() {

        echo "\n=================\n";
        echo "| Help Commands |\n";
        echo "=================\n";


        foreach($this->cmd_list as $name => $info) {

            echo "\t\033[32m$name\033[0m\t\t{$info['description']}";
        }

    }



    public function list_route() {

        include 'routes/route.php';

        echo "\n  ==================\n";
        echo "-|  List of routes  |-\n";
        echo "  ==================\n\n";

        $method = '';        

        foreach($router->list_routes() as $k => $v) {

            if($k === 'GET') {

                $method = "\033[1;32m$k\033[0m";
            }
            if($k === 'POST') {

                $method = "\033[1;34m$k\033[0m";
            }
            if($k === 'PUT') {

                $method = "\033[1;37m$k\033[0m";
            }
            if($k === 'DELETE') {

                $method = "\033[1;31m$k\033[0m";
            }
            if($k === 'PATCH') {

                $method = "\033[1;33m$k\033[0m";
            }
            
            foreach($v as $route) {
                
                $url = $route->url;
                if(!empty($route->name))
                    $name = preg_replace('#^(.*)$#', "( \033[3;4m$1\033[0m )", $route->name);
                else
                $name = '';
                
                $controller = $route->controller[0];

                $controller = explode('\\', $controller);
                $controller = array_reverse($controller)[0];
                $action = $route->controller[1];

                $middlewares = implode(' | ',$route->middleware_);

                if(!empty($middlewares)) {
                    $middlewares = "{ \033[1;46m" . $middlewares . "\033[0m }";
                }
                else {
                    $middlewares = '';
                }

                printf("\033[1;30mHEAD\033[0m/%s \t \033[32;1m%s\033[0m \t \033[2;4m%s\033[0m@\033[2;4m%s\033[0m %s %s\033[0m\n", $method, $url, $controller, $action, $name, $middlewares);
            }
        }
    }
}