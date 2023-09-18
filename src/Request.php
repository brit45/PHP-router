<?php

namespace Root22\Router;

use Root22\Router\Exceptions\RequestException;

class Request {

    private string $method;
    private string $url;
    private array $params = [];
    public array $session_data = [];

    public function __construct() {

        $this->url = $this->getListenerUrl($_GET['url']);
        $this->method();
        $this->getParams();
        
        //! ---------------------------------------------------------
        //!                                                       .
        //! This Element is not Functionnel -- In development -- /!\
        //!
        //! ---------------------------------------------------------

        if(isset($_SESSION) || empty($_SESSION)) {
            
            $this->init_varaibles_session();
            $_SESSION = $this->session_data;
        }

        //! ---------------------------------------------------------
    }
    
    /**
     * method
     * 
     * Detect the method of connection.
     *
     * @return void
     */
    private function method() : void {

        if(isset($_POST['method']) && !empty($_POST['method'])) {

            $this->method = strtoupper($_POST['method']);
        }
        else {
            
            $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        }
    }
    
    /**
     * getParams
     * 
     * Collect variable(s) $_GET. 
     *
     * @return void
     */
    private function getParams() : void {

        foreach($_GET as $k=>$v) {

            if($k !== 'url')
                $this->params[$k] = $v;
        }
    }
    
    /**
     * getListenerUrl
     * 
     * Handle Uri for application.
     *
     * @param  string $path
     * @return string|RequestException
     */
    private function getListenerUrl($path) {

        if(isset($path))
            return $path;
        
        return throw new RequestException('Not Found Listener url variables.');
    }
    
    /**
     * Data
     *
     * Abstract property of Request.
     *
     * @return array
     */
    public function Data() : array {

        return [
            'method' => $this->method,
            'url' => $this->url,
            'params' => $this->params,
            'session' => $this->session_data
        ];
    }


    //! ---------------------------------------------------------
    //!                                                       .
    //! This Element is not Functionnel -- In development -- /!\
    //!
    //! ---------------------------------------------------------

        
    /**
     * init_varaibles_session
     * 
     * Init Session connection.
     *
     * @return void
     */
    private function init_varaibles_session() {

        if(empty($this->session_data['permission'])) {

            $this->session_data['permission'] = 'admin';
        }

        if(empty($this->session_data['user_name'])) {

            $this->session_data['user_name'] = 'guest';
        }
    }
    //! ---------------------------------------------------------
}