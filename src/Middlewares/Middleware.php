<?php

namespace Root22\Router\Middlewares;

use Root22\Router\Request;

class Middleware {

    protected Request $request;
    public string $permission;
    public string $username;

    public function __construct() {

        $this->request = new Request;
        $this->permission = $this->request->Data()['session']['permission'];
        $this->username = $this->request->Data()['session']['user_name'];
    }
}