<?php

namespace Root22\Router;



/**
 * Responce
 * 
 * @property mixed $data
 * @property array $content
 */
class Responce {

    private $data;
    private array $content = [];

    public function __construct(mixed $data) {

        $this->data = $data;
        $this->Find_Headers();
        $this->GetResponce();
    }
    
    /**
     * GetResponce
     * 
     * Show the responce.
     *
     * @return void
     */
    private function GetResponce() {

        if(empty($this->content['body'])) {

            return 'Empty';
        }
        
        return printf($this->content['body']);
    }
    
    /**
     * Find_Headers
     * 
     * Auto-detected header HTTP responce.
     *
     * @return void
     */
    private function Find_Headers() {

        if(is_string($this->data)) {

            header('Content-Type:text/html');
            $this->content['body'] = $this->data;
        }
        if(is_array($this->data)) {

            header('Content-Type:application/json');
            $this->content['body'] = json_encode($this->data);
        }
    }
}