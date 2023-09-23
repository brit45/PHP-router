<?php

namespace Root22\Router\Configs;

class appConfig {

    public array $config;
    
    public function __construct() {
    
        $this->config = [
            
            'layout' => require ROOT.'/src/Configs/appLayoutRenderConfig.php'
        ];
    }

    public function get(string $key) {

        $argument = explode('.', $key);

        if(isset($this->config[$argument[0]])) {

            if(isset($this->config[$argument[0]][$argument[1]])) {

                return $this->config[$argument[0]][$argument[1]];
            }
        }
    }


}