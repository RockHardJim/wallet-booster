<?php

ini_set('display_errors',1);

class Controller{

    protected $_view;
    protected $_model;

    public function view($_viewName,$_data=[],$_title){
        $this->_view = new View($_viewName,$_data,$_title);
        return $this->_view;
    }

    public function redirect($url)
    {   
        exit(header('Location: https://' . $_SERVER['HTTP_HOST'].DS.$url));
        exit();
    }

    public function isLoggedIn(){
        if(isset($_SESSION['member_no']) && isset($_SESSION['email'])){
            if($_SESSION['email']<>'admin@amazinggracenetwork.com'){
                return true;
            }
            else    
                return false;
        }else
            return false;
    }

    public function isLoggedInAdmin(){
        if(isset($_SESSION['member_no']) && isset($_SESSION['email'])){
            if($_SESSION['email']=='admin@amazinggracenetwork.com'){
                return true;
            }
            else    
                return false;
        }else
            return false;
    }   
}