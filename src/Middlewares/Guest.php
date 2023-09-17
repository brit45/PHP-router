<?php

namespace Root22\Router\Middlewares;

class Guest extends Middleware {

    public function validate() : bool {

        return true;
    }
}