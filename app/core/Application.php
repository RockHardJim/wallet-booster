<?php

class Application{

    protected $_controller = 'homeController';
    protected $_action = 'index';
    protected $_prams = [];

    public function __construct(){                
        $this->prepareURL(); 
        
        if(file_exists(CONTROLLER.$this->_controller.'.php')){          
            $this->_controller = new $this->_controller();
            if(method_exists($this->_controller,$this->_action)){
                //call_user_func_array is more useful when number of parameters is unknown
                call_user_func_array([$this->_controller,$this->_action],$this->_prams);   
                //print_r($this->_prams);         
            }else{
                $homeController = new homeController;
                call_user_func_array([$homeController,'notFound'],$this->_prams);
            }
        }else{
            header('Location: http://' . $_SERVER['HTTP_HOST']);
            exit();
        }
    }

    protected function prepareURL(){
        $_request = trim($_SERVER['REQUEST_URI'],'/');
        if(!empty($_request)){
            
            $_url = explode('/', $_request);

            $this->_controller = isset($_url[0]) ? $_url[0].'Controller' : 'homeController';
            $this->_action     = isset($_url[1]) ? $_url[1] : 'index';

            unset($_url[0],$_url[1]);

            $this->_prams = !empty($_url) ? array_values($_url) : []; 
        }
    }
}
