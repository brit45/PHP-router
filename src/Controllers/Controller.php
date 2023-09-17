<?php

namespace Root22\Router\Controllers;

use Root22\Router\{Request, Route};

class Controller {

    public Request $request;
    public array $params = [];
    public Route $route;

    public function __construct(Request $request, ?Route $route) {

        $this->request = $request;

        $this->params = $this->request->Data()['params'];
        
        if($route !== null) {

            $this->route = $route;
        }
    }

}