<?php

class incentivesClass extends Model
{	

	private $_now;	

	public function __construct(){
		parent::__construct();
		$this->_now = NOW;
	}
    
	public function createIncentive($_user, $_stage){		
		if(!$this->claimExists($_user,$_stage) && $_stage>0){
			$stmt = $this->_conn->prepare("insert 
        	                                 into incentives 
        	                               values (0,'$_user','$_stage','Created','$this->_now')");	
			$stmt->execute();

			if($stmt){
				return '';		  
			}else{	
				return '';		
			}	 
		}   					  	
	}
	
	public function getUpgradeDue($_user){

		$_mem  = new memberClass();
		$_a    = new allocationsClass();
		$_tree = new treeClass();
		$_inc  = $this->getAllUnclaimedIncentives($_user);

		if($_inc->rowCount()>0){
			$_counter = 0;
            $_dis = '';
			$_row = $_inc->fetch(PDO::FETCH_ASSOC);
            $_counter += 1;            
            $_stage   = $_row['stage'];
            $_amount  = 0;
            $_description = '';
            $_stage_received = 0;
        
            if($_stage == '1'){
                $_amount = 500;
				$_description = 'Level 2';
				if($_a->toStage2Eligible($_user)){
                    return $_row['trans_id'];
                }
            }elseif($_stage == '2'){
                $_amount = 1000;
                $_description = 'Level 3';
                if($_a->toStage3Eligible($_user)){
                    return $_row['trans_id'];
                }
            }elseif($_stage == '3'){
                $_amount = 3000;
                $_description = 'Level 4';
                if($_a->toStage4Eligible($_user)){
                    return $_row['trans_id'];
                }
			} 			
			return 0;
		}
		return 0;
	}

    public function hasUnclaimedIncentive($_user){		
        $stmt = $this->_conn->prepare("select * from incentives where member_no = '$_user' and status = 'Created'");	
		$stmt->execute();
                    
        $_count = $stmt->rowCount();

		if($_count>0){
			return true;  
		}else{	
			return false;		
		}	   			  	
	}
	
	public function claimExists($_user,$_stage){		
        $stmt = $this->_conn->prepare("select * from incentives where member_no = '$_user' and stage = '$_stage'");	
		$stmt->execute();
                    
        $_count = $stmt->rowCount();

		if($_count>0){
			return true;  
		}else{	
			return false;		
		}	   			  	
    }

    public function getAllIncentives($_user){		
        $stmt = $this->_conn->prepare("select * from incentives where member_no = '$_user'");	
		$stmt->execute();
                    
        $_count = $stmt->rowCount();

		return $stmt;  

		/*if($_count>0){
			return $stmt;  
		}else{	
			return '';		
		}*/   			  	
	}

	public function getCurrentIncentive($_t){		
        $stmt = $this->_conn->prepare("select * from incentives where trans_id = '$_t'");	
		$stmt->execute();

		return $stmt;  			  	
	}
	
	public function getAllClaimedIncentives($_user){		
        $stmt = $this->_conn->prepare("select * from incentives where member_no = '$_user' and status = 'Claimed'");	
		$stmt->execute();
                    
        $_count = $stmt->rowCount();

		return $stmt;  

		/*if($_count>0){
			return $stmt;  
		}else{	
			return '';		
		}*/   			  	
	}
	
	public function getAllUnclaimedIncentives($_user){		
        $stmt = $this->_conn->prepare("select * from incentives where member_no = '$_user' and status = 'Created' order by trans_id asc limit 1");	
		$stmt->execute();
                    
        $_count = $stmt->rowCount();

		return $stmt;  

		/*if($_count>0){
			return $stmt;  
		}else{	
			return '';		
		}*/   			  	
    }

    public function getMember($_id){		
        $stmt = $this->_conn->prepare("select member_no from incentives where trans_id = '$_id'");	
		$stmt->execute();
                    
        $_count = $stmt->rowCount();

		if($_count>0){
            $_row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $_row['member_no'];	 
		}else{	
			return '';		
		}	   			  	
    }

    public function getStage($_id){		
        $stmt = $this->_conn->prepare("select stage from incentives where trans_id = '$_id'");	
		$stmt->execute();
                    
        $_count = $stmt->rowCount();

		if($_count>0){
            $_row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $_row['stage'];	 
		}else{	
			return '';		
		}	   			  	
	}
	
	public function updateIncentives(){
		$stmt = $this->_conn->prepare("select * 
										 from incentives 
										where status = 'Claimed' 
										  and stage = '1' 
										  and member_no not in (select payer 
																  from allocations 
																 where payer = member_no 
																   and amount ='400')");

		$stmt->execute();
		
		while($_row = $stmt->fetch(PDO::FETCH_ASSOC)){

			$_member_no = $_row['member_no'];
			$_stage     = $_row['trans_id'];
			$_trans_id  = $_row['trans_id'];

			$stmt_1 = $this->_conn->prepare("Update incentives set status = 'Created' where trans_id = $_trans_id");
			$stmt_1->execute();
		}

		$stmt2 = $this->_conn->prepare("select * 
										 from incentives 
										where status = 'Claimed' 
										  and stage = '2' 
										  and member_no not in (select payer 
																  from allocations 
																 where payer = member_no 
																   and amount ='800')");

		$stmt2->execute();
		
		while($_row = $stmt2->fetch(PDO::FETCH_ASSOC)){

			$_member_no = $_row['member_no'];
			$_stage     = $_row['stage'];
			$_trans_id  = $_row['trans_id'];

			$stmt2_1 = $this->_conn->prepare("Update incentives 
											  set status = 'Created' 
											where trans_id = $_trans_id 
											  and member_no = $_member_no 
											  and stage = $_stage");
			$stmt2_1->execute();
		}
	}

    public function updateIncentive($_id){		
        $stmt = $this->_conn->prepare("update incentives set status = 'Claimed',timestamp = '$this->_now' where trans_id = '$_id'");	
		$stmt->execute();
                
		if($stmt)
			return 'Incentive Claimed';	 
		else	
			return 'Error claiming incentive';		
			   			  	
	}
	
	public function reverseIncentive($_member_no,$_stage){		
        $stmt = $this->_conn->prepare("update incentives set status = 'Created',timestamp = '$this->_now' where member_no = '$_member_no' and stage = $_stage");	
		$stmt->execute();
                
		if($stmt)
			return 'Incentive Reversed';	 
		else	
			return 'Error claiming incentive';		
			   			  	
    }
}