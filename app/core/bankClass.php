<?php

class bankClass extends Model
{	

	private $_now;	

	public function __construct(){
				parent::__construct();
				$this->_now = NOW;
	}
    
	public function getAllMembers(){		
		$stmt = $this->_conn->prepare("select * from banking");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
			return $stmt;		  
		}else{	
			return '';		
		}	    					  	
	}
	
	public function getBankingDetails($_member_no){		
		$stmt = $this->_conn->prepare("select b.member_no, accholder, bank, accnumber, branch_code, wallet 
										 from banking b 
								   left join bitcoins b2 
								      on b.member_no = b2.member_no
								   where b.member_no = '$_member_no'");	
		$stmt->execute();
		$_count = $stmt->rowCount();			  											  
		return $stmt;   					  	
  }
    
  
	public function addMember($_id, $_array){		
		$stmt = $this->_conn->prepare("insert 
										 into banking
									 values( :member_no,
											 :accholder,
											 :bank,
											 :accnumber,
											 :branch,
                                             :timestamp)");
                                             
		$stmt->execute(array(':member_no'	=>$_id,
							 ':accholder'   =>$_array['accholder'],
							 ':bank'	    =>$_array['bank'],
							 ':branch'	    =>$_array['branchcode'],
							 ':accnumber'	=>$_array['accnumber'],
							 ':timestamp'	=>$this->_now
							));	
		  											  
		if($stmt){
            return "Banking details inserted";		  
		}else{	
			return "Error inserting banking details";		
		}	    					  	
	}

	public function hasBitcoin($_user){			
		$stmt = $this->_conn->prepare("select * from bitcoins where member_no =:msg");		
		$stmt->execute(array(':msg'=>$_user));		
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){			
			return true;	  
		}else{			
			return false;			
		}						  	
	}
	
	public function getBitcoin($_user){			
		$stmt = $this->_conn->prepare("select * from bitcoins where member_no =:msg");		
		$stmt->execute(array(':msg'=>$_user));		
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){		
			$_row =  $stmt->fetch(PDO::FETCH_ASSOC);	
			return $_row['wallet'];	  
		}					  	
	}


       
	public function updateBanking($_array){	
		$stmt = $this->_conn->prepare("Update banking 
									 	  set accholder   = :accholder,
									 			bank      = :bank,
									 			accnumber = :accnumber,	
									 		  branch_code = :branchcode,
									 			timestamp = :timestamp
									 where member_no = :user");

	  $stmt->execute(array( ':accholder'	=>	$_array['accholder'],
							':bank'   		=>	$_array['bank'],
							':accnumber'	=>	$_array['accnumber'],
							':branchcode'	=>	$_array['branchcode'],
							':timestamp'	=>	$this->_now,
							':user'			=>	ME
	 ));	

	 if($this->hasBitcoin(ME)){
		$stmt2 = $this->_conn->prepare("Update bitcoins 
									 	  set wallet   = :wallet,									 			
									 		 timestamp = :timestamp
									 where member_no = :user");

	  	$stmt2->execute(array( ':wallet'	=>	$_array['wallet'],
							':timestamp'	=>	$this->_now,
							':user'			=>	ME
	 	));	
	 }else{
		$stmt2 = $this->_conn->prepare("insert 
								into bitcoins
							values( :member_no,
									:wallet,
									:timestamp)");
									
		$stmt2->execute(array(':member_no'	=> ME,						
							  ':wallet'	    => $_array['wallet'],
							  ':timestamp'	=> $this->_now
		));	
	 }

	 if($stmt && $stmt2){
		 return 'Banking details changed';
	 }else{
		return 'Error, could not update banking details';
	 }
	}

	public function checkUsernameExists($_id){		
    $stmt = $this->_conn->prepare("select * from members where username = :id");
    $stmt->execute(array(':id'=>$_id));
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
      $_row =  $stmt->fetch(PDO::FETCH_ASSOC);
      return "Sorry this username is not available";		  
		}else{	
			return "<div class='l'>This username is available</div>";		
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