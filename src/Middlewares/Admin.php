<?php

namespace Root22\Router\Middlewares;

class Admin extends Middleware {

    public function validate() : bool {

        return ($this->permission === 'admin');
    }
}