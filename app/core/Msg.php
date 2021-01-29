<?php

class Msg extends Model
{	
	
	public function __construct(){
        parent::__construct();
	}
    
	public function getMsg($_msg_no){		
		$stmt = $this->_conn->prepare("select * from msg where msg_no =:msg");	
		$stmt->execute(array(':msg'=>$_msg_no));
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
			$row    = $stmt->fetch(PDO::FETCH_ASSOC);		
			$_msg   = $row['msg'];
		  
		  	return $_msg;		  
		}else{	
			return 'Error retrieving error message';	
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