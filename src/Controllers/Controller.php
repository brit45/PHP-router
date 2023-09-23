<?php

namespace Root22\Router\Controllers;

use Philo\Blade\Blade;
use Root22\Router\{Request, Route};
use Root22\Router\Configs\appConfig;

class Controller {

    public Request $request;
    public array $params = [];
    public Route $route;
    protected appConfig $config;
    private Blade $blade;

    public function __construct(Request $request, ?Route $route) {

        $this->request = $request;

        $this->config = new appConfig;

        $this->params = $this->request->Data()['params'];
        
        if($route !== null) {

            $this->route = $route;
        }

        $this->blade = new Blade([$this->config->get('layout.views_dir')], $this->config->get('layout.cache_dir'));
    }


    public function view(string $layout, array $params = []) {

        return $this->blade->view()->make($layout, $params)->render();
    }

}