<?php

namespace Root22\Router\Middlewares;

class Guest extends Middleware 
implements MiddlewareInterface {

    public function validate() : bool {
        
        return true;
    }
}