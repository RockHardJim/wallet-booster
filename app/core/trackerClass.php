<?php

class trackerClass extends Model
{	

	private $_now;	

	public function __construct(){
		parent::__construct();
		$this->_now = NOW;
	}

    public function createTracker($_m){		
        $stmt = $this->_conn->prepare("insert 
                                         into tracker
                                       values (0,'$_m',:t)");	
        $stmt->execute(array(':t'=> NOW));     		  	
    }
    
}