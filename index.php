<?php

    ini_set('display_errors',1);
    require "app/core/Initialization.php";
    
    Initialization::run(); 

    new Application;
	
	//phpinfo();

?>