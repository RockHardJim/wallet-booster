<?php    
    class Initialization {
            
        public static function run() {            
            self::init();   
            //self::autoload();    
        }
                               
        private static function init(){
            
            date_default_timezone_set("Africa/Johannesburg");
    
            define("DS", DIRECTORY_SEPARATOR);
            define("ROOT", dirname(dirname(__DIR__)) . DS);
            define("APP", ROOT . 'app' . DS);
            define("VIEW", ROOT . 'app' . DS . 'view'. DS);
            define("CONTROLLER", ROOT . 'app' . DS . 'controller'. DS);
            define("MODEL", ROOT . 'app' . DS . 'model'. DS);
            define("DATA", ROOT . 'app' . DS . 'data'. DS);
            define("CORE", ROOT . 'app' . DS . 'core'. DS);
            define("SMS", ROOT . 'app' . DS . 'core'. DS. 'sms_api' . DS);
            define("NOW", date("Y-m-d H:i:s"));
            define("UPLOADS", ROOT . 'uploads' . DS);

            define("DB_SERVER",  'localhost');
            define("DB_USERNAME",'root');
            define("DB_PASSWORD",'root');
            define("DB_DATABASE",'refinednetworknew');
            

            $_modules = [ROOT,APP,VIEW,CONTROLLER,MODEL,DATA,CORE];

            //set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR,$_modules));

            //spl_autoload_register('spl_autoload',true);

            spl_autoload_register('self::load');
           
            session_start(); 

            if(isset($_SESSION['member_no'])){
                define("ME",$_SESSION['member_no']);
            }
            
        }
            
        /*private static function autoload(){
            spl_autoload_register('spl_autoload',false);           
        }*/

        private static function load($className)
        {           
            if(file_exists(CONTROLLER.$className.'.php')){
                include CONTROLLER.$className.'.php';
            }elseif(file_exists(CORE.$className.'.php')){
                include CORE.$className.'.php';
            }elseif(file_exists(MODEL.$className.'.php')){
                include MODEL.$className.'.php';
            }elseif(file_exists(SMS.$className.'.php')){
                include SMS.$className.'.php';
            }
        }
    }  
      
?>