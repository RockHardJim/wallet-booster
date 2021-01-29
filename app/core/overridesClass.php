<?php

class overridesClass extends Model
{	

	private $_now;	

	public function __construct(){
		parent::__construct();
		$this->_now = NOW;
	}
        
    public function getAllOverrides(){		
        $stmt = $this->_conn->prepare("select * from overrides");	
        $stmt->execute();    
        return $stmt;       		  	
    }

    public function deleteOverrides($_t){		
        $stmt = $this->_conn->prepare("delete from overrides where trans_id = '$_t'");	
        $stmt->execute();      		  	
    }

    public function createOverrides($_m){		
        $stmt = $this->_conn->prepare("insert 
                                         into overrides
                                       values (0,'$_m','Y',:t)");	
        $stmt->execute(array(':t'=> NOW));     		  	
    }
    
    public function isOverriden($_m){
        $stmt = $this->_conn->prepare("select member_no from overrides where member_no = '$_m'");	
        $stmt->execute();  
        if($stmt->rowCount()>0)
            return true;
        else    
            return false;
    }
}