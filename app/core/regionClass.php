<?php

class regionClass extends Model
{	
	
	public function __construct(){
        parent::__construct();
	}
    
	public function getAllRegions(){		
		$stmt = $this->_conn->prepare("select * from regions");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
			return $stmt;		  
		}else{	
			return '';		
		}	    					  	
  }
    
  public function getCountryCode($_iso){		
        $stmt = $this->_conn->prepare("select * from regions where iso = :iso");
        $stmt->execute(array(':iso'=>$_iso));
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
            $_row =  $stmt->fetch(PDO::FETCH_ASSOC);
            return '+'.$_row['phone_prefix'];		  
		}else{	
			return '';		
		}	    					  	
	}

	public function getCountry($_iso){		
		$stmt = $this->_conn->prepare("select * from regions where iso = :iso");
		$stmt->execute(array(':iso'=>$_iso));
		$_count = $stmt->rowCount();	

		if($_count>0){
			$_row =  $stmt->fetch(PDO::FETCH_ASSOC);
			return $_row['country'];		  
		}else{	
			return '';		
		}	    					  	
	}
	
	public function getTyp($_msg_no){
			
		$stmt = $this->_conn->prepare("select * from msg where msg_no =:msg");		
		$stmt->execute(array(':msg'=>$_msg_no));		
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){			
			$row    = $stmt->fetch(PDO::FETCH_ASSOC);		
			$_msg   = $row['msg_type'];
		  
		  	return $_msg;		  
		}else{			
			return 'Error retrieving error message type';			
		}						  	
	}	
       
}

?>