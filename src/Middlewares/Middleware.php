<?php

namespace Root22\Router\Middlewares;

use Root22\Router\Request;


interface MiddlewareInterface {

    /**
     * Validate
     * 
     * Verify if user is athorized to access.
     *
     * @return bool
     */
    public function Validate();
}


/**
 * Middleware
 * 
 * @property Request $request
 * @property string $permission Role of access.
 * @property string $username Entity of user.
 * 
 */
class Middleware {

    protected Request $request;
    public string $permission;
    public string $username;

    public function __construct() {

        $this->request = new Request;

        //! ---------------------------------------------------------
        //!                                                       .
        //! This Element is not Functionnel -- In development -- /!\
        //!
        //! ---------------------------------------------------------

        $this->permission = $this->request->Data()['session']['permission'];
        $this->username = $this->request->Data()['session']['user_name'];

        //! ---------------------------------------------------------
    }
    
}