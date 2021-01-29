<?php

class allocationsClass extends Model
{	

	private $_now;	

	public function __construct(){
		parent::__construct();
		$this->_now = NOW;
	}
    
	public function getAllAllocations(){		
		$stmt = $this->_conn->prepare("select * from allocations where receiver not in (select member_no from members where password = 'hiddenacc') order by trans_id desc limit 100");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		return $stmt;		  	    					  	
	}

	public function getAllAllocationsByDate($_date){
		
			$date = $_date;
			$new_date = date('Y-m-d',strtotime($date));
	
			/*$trans_date = date('Y-m-d',strtotime($_row['trans_date']));
			$trans_date  = new DateTime($trans_date); */

		$stmt = $this->_conn->prepare("select * from allocations where date(trans_date) = '$new_date' and receiver not in (select member_no from members where password = 'hiddenacc') order by trans_id desc limit 100");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		return $stmt;		  	    					  	
	}

	

	public function sponsorhasUpgraded($_sid){

		$_tree = new treeClass();
        $_inc  = new incentivesClass();
        $_def  = new defaultsClass();
        $_all  = new allocationsClass();
        $_m    = new memberClass();  
		$_o    = new overridesClass();

		$_upliner_details = '';
		
		$_stage = $_inc->getStage($_sid);

		if ($_stage == '1'){
			
			$_description = 'Stage 2';
			$_sponsor = $_tree->selectDUpliner($_tree->selectDUpliner(ME));

			if(!$_all->checkIfUplinerHasUpgraded($_sponsor,$_description) && !$_o->isOverriden($_sponsor)){
				$_upliner_details = 'Your leader has not upgraded. Are you sure you want to skip your leader?<br />
									Call '.$_m->getUsername($_sponsor). ' on '.$_m->getMobile($_sponsor).' and check with them or click Upgrade below to skip the leader';
					
			}

		} elseif($_stage == '2'){

			$_description = 'Stage 3';
			$_sponsor = $_tree->selectDUpliner($_tree->selectDUpliner($_tree->selectDUpliner(ME)));

			if(!$_all->checkIfUplinerHasUpgraded($_sponsor,$_description) && !$_o->isOverriden($_sponsor)){
				$_upliner_details = 'Your leader has not upgraded. Are you sure you want to skip your leader?<br />
									Call '.$_m->getUsername($_sponsor). ' on '.$_m->getMobile($_sponsor).' and check with them or click Upgrade below to skip the leader';
			}

		} elseif($_stage == '3'){

			$_description = 'Stage 4';
			$_sponsor = $_tree->selectDUpliner($_tree->selectDUpliner($_tree->selectDUpliner($_tree->selectDUpliner(ME))));

			if(!$_all->checkIfUplinerHasUpgraded($_sponsor,$_description) && !$_o->isOverriden($_sponsor)){
				$_upliner_details = 'Your leader has not upgraded. Are you sure you want to skip your leader?<br />
									Call '.$_m->getUsername($_sponsor). ' on '.$_m->getMobile($_sponsor).' and check with them or click Upgrade below to skip the leader';
			}

		}
		return $_upliner_details;
	}

	public function getSpecificAllocations($_search){		
		$stmt = $this->_conn->prepare("select * 
										 from allocations
										where exists (select 1 
														  from members 														  
														 where (firstname 	like '%$_search%'
														    or  lastname 	like '%$_search%'
															or  username 	like '%$_search%')
														   and  password     <> 'hiddenacc'
														   and  member_no   = payer
														   and  active 		in ('No','Yes','Blocked'))
										  or exists (select member_no 
														   from members 
														  where (firstname 	like '%$_search%'
														 	 or  lastname 	like '%$_search%'
															  or  username 	like '%$_search%')
															and  password     <> 'hiddenacc'  
															and member_no   = receiver
															and  active 	in ('No','Yes','Blocked')
												  and pop = ''
										order by trans_id desc)");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
			return $stmt;		  
		}else{	
			return $stmt;		
		}	    					  	
	}

	public function countAllReceived($_user){		
		$stmt = $this->_conn->prepare("select coalesce(sum(amount),0) as sum from allocations where receiver = '$_user' and status = 'Paid'");	
		$stmt->execute();
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['sum']; 	
	}

	public function moneyCirculated(){		
		$stmt = $this->_conn->prepare("select coalesce(sum(amount),0) as sum from allocations where status = 'Paid'");	
		$stmt->execute();
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['sum']; 	
	}

	public function paid2Defaults(){		
		$stmt = $this->_conn->prepare("select coalesce(sum(amount),0) as sum 
										 from allocations 
										where receiver in (select member_no from defaults) 
										  and receiver not in (select member_no from members where password = 'hiddenacc')
										  and status = 'Paid'");	
		$stmt->execute();
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['sum']; 	
	}

	public function pending2Defaults(){		
		$stmt = $this->_conn->prepare("select coalesce(sum(amount),0) as sum 
									 	from allocations 
									    where receiver in (select member_no from defaults) 
									 	 and receiver not in (select member_no from members where password = 'hiddenacc')
									 	 and status = 'Pending'");	
		$stmt->execute();
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['sum']; 	
	}

	public function stageUpgradesReceived($_user){
		$stmt = $this->_conn->prepare("select coalesce(sum(amount),0) as sum from allocations where receiver = '$_user' and status = 'Paid' and description = '$_description'");	
		$stmt->execute();
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['sum']; 
	}

	public function routingExists($_user,$_amount){
		$stmt = $this->_conn->prepare("select * from upgradeRouting where member_no = '$_user' and amount = '$_amount'");
		$stmt->execute();
		if($stmt->rowCount()==0){
			return false;
		}
		else
			return true;
	}

	public function upgradeRoutingDue($_user,$_amount){

		return false;
		
		if($_amount==1000){
			$stmt = $this->_conn->prepare("select * from allocations where receiver = '$_user' and amount = '$_amount'");
			$stmt->execute();
			if($stmt->rowCount()==3 && !$this->routingExists($_user,$_amount)){
				$stmt = $this->_conn->prepare("insert into upgradeRouting values (0,$_user,$_amount,:timestamp)");
				if($stmt->execute(array(':timestamp'	=>$this->_now)))
					return true;
				else
					return false;
			}
			else	
				return false;
		}elseif($_amount==3000){
			$stmt = $this->_conn->prepare("select * from allocations where receiver = '$_user' and amount = '$_amount'");
			$stmt->execute();
			if($stmt->rowCount()==6 && !$this->routingExists($_user,$_amount)){
				$stmt = $this->_conn->prepare("insert into upgradeRouting values (0,$_user,$_amount,:timestamp)");
				if($stmt->execute(array(':timestamp'	=>$this->_now)))
					return true;
				else
					return false;
			}
			else	
				return false;
		}else{
			return false;
		}			
	}

	public function countAllReceivedUpgrades($_user){		
		$stmt = $this->_conn->prepare("select coalesce(sum(amount),0) as sum from allocations where receiver = '$_user' and status = 'Paid' and description like 'Stage%'");	
		$stmt->execute();
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['sum']; 	
	}

	public function countAllPaid($_user){		
		$stmt = $this->_conn->prepare("select coalesce(sum(amount),0) as sum from allocations where payer = '$_user' and status = 'Paid'");	
		$stmt->execute();
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['sum']; 	
	}

	public function getStageCompleted($_user){		
		$stmt = $this->_conn->prepare("select count(distinct amount) as count from allocations where payer = '$_user' and status = 'Paid' and status not in ('Deleted','Reversed')");	
		$stmt->execute();
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['count']; 	
	}

	public function checkIfUplinerHasUpgraded($_user,$_description){		
		$stmt = $this->_conn->prepare("select * from allocations where payer = '$_user' and status = 'Paid' and description = '$_description' and status <> 'Deleted'");	
		$stmt->execute();
		if($stmt->rowCount()>0)
			return true;
		else	
			return false;
	}

	public function hasPendingAllocation($_user){		
		$stmt = $this->_conn->prepare("select * from allocations where payer = '$_user' and status = 'Pending'");	
		$stmt->execute();
		if($stmt->rowCount()>0)
			return true;
		else	
			return false;			
	}

	public function hasDeletedAllocation($_user){		
		$stmt = $this->_conn->prepare("select * from allocations where payer = '$_user' and status = 'Deleted'");	
		$stmt->execute();
		if($stmt->rowCount()>0)
			return true;
		else	
			return false;			
	}

	public function hasPaidRegFee($_user){		
		$stmt = $this->_conn->prepare("select * from allocations where payer = '$_user' and status = 'Paid' and amount = '200' and description = 'Reg Fee'");	
		$stmt->execute();
		if($stmt->rowCount()>0)
			return true;
		else	
			return false;			
	}
	
	public function getDescription($_trans_id){		
		$stmt = $this->_conn->prepare("select description from allocations where trans_id = '$_trans_id'");	
		$stmt->execute();
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['description']; 	
	}

	public function getPayer($_trans_id){		
		$stmt = $this->_conn->prepare("select payer from allocations where trans_id = '$_trans_id'");	
		$stmt->execute();
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['payer']; 	
	}

	public function getReceiver($_trans_id){		
		$stmt = $this->_conn->prepare("select receiver from allocations where trans_id = '$_trans_id'");	
		$stmt->execute();
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['receiver']; 	
	}

	public function toStage2Eligible($_user){
		$stmt = $this->_conn->prepare("select count(*) as count from allocations where receiver = '$_user' and status = 'Paid' and amount='200' ");	
		$stmt->execute();
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		if($_row['count']>='2')
			return true;
		else
			return false;
	}

	public function toStage3Eligible($_user){
		$stmt = $this->_conn->prepare("select count(*) as count from allocations where receiver = '$_user' and status = 'Paid' and amount='400' ");	
		$stmt->execute();
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		if($_row['count']>=2)
			return true;
		else
			return false;
	}

	public function toStage4Eligible($_user){
		$stmt = $this->_conn->prepare("select count(*) as count from allocations where receiver = '$_user' and status = 'Paid' and amount='1000' ");	
		$stmt->execute();
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		if($_row['count']>=3)
			return true;
		else
			return false;
	}

	public function PendingWithdrawals($_user){		
		$stmt = $this->_conn->prepare("select coalesce(sum(amount),0) as sum from allocations where payer = '$_user' and status = 'Pending'");	
		$stmt->execute();
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['sum']; 	
	}

	public function getAllMemberAllocation($_user){		
		$stmt = $this->_conn->prepare("select * from allocations where (payer = '$_user' or receiver = '$_user') and status <> 'Deleted'");	
		$stmt->execute();
		$_count = $stmt->rowCount();			  											  
		return $stmt;		  
	}

	public function getAllocationsPaidIn($_user){		
		$stmt = $this->_conn->prepare("select * from allocations where receiver = '$_user' and status not in ('Deleted','Reversed')");	
		$stmt->execute();
		$_count = $stmt->rowCount();			  											  
		return $stmt;		  
	}

	public function getAllocationsPaidOut($_user){		
		$stmt = $this->_conn->prepare("select * from allocations where payer = '$_user' and status not in ('Deleted','Reversed')");	
		$stmt->execute();
		$_count = $stmt->rowCount();			  											  
		return $stmt;		  
	}
	
	public function getBankingDetails($_member_no){		
		$stmt = $this->_conn->prepare("select * from banking where member_no = '$_member_no'");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count==1){
			return $stmt;		  
		}else{	
			return '';		
		}	    					  	
  }
    
  
	public function addAllocation($_payer, $_receiver,$_amount,$_description){		
		$stmt = $this->_conn->prepare("insert 
										 into allocations
									 values( 0,
											 :payer,
											 :receiver,
											 :amount,
                       :description,
                       :trans_date,
                       'Pending',
                       '',   
                       :timestamp)");
                                             
		$stmt->execute(array(':payer'	    =>$_payer,
							 ':receiver'    =>$_receiver,
							 ':amount'	    =>$_amount,
                             ':description'	=>$_description,
                             ':trans_date'	=>$this->_now,
							 ':timestamp'	=>$this->_now
							));	
		  											  
		if($stmt){
			$_mem = new memberClass();
			$_rmobile = $_mem->getMobile($_receiver);
			$_ruser   = $_mem->getUsername($_receiver);
			$_pmobile = $_mem->getMobile($_payer);

			/*$_sms = new MySMS($_pmobile,'AGN allocation to pay upliner. You have 12 hours to pay. Visit Cash Out on site for details.');							                                                                                                
			$_send = $_sms->sendSMS(); 
			
			$_sms = new MySMS($_rmobile,'Hi '.$_ruser.', AGN allocation to be paid by a member of your tree. Visit Cash In on site for details.');							                                                                                                
			$_send = $_sms->sendSMS(); */
			
            return "Allocations details inserted";		  
		}else{	
			return "Error inserting banking details";		
		}	    					  	
	}

	public function updateAllocation($_user){	
		$stmt = $this->_conn->prepare("Update allocations 
										  set status    = 'Paid',	
									        timestamp = :timestamp
																		where payer     	= :payer
																		  and description = 'Reg Fee'");

	  $stmt->execute(array( ':timestamp'  	=> $this->_now,
													':payer'				=> $_user
	 ));	

	 if($stmt){
		 return 'Allocations updated';
	 }else{
		return 'Error, could not update allocations details';
	 }

	}

	public function updateAllocation_2($_user){	
		$stmt = $this->_conn->prepare("Update allocations 
																			set status    = 'Pending',	
																					timestamp = :timestamp
																		where payer     	= :payer
																		  and description = 'Reg Fee'");

	  $stmt->execute(array( ':timestamp'  	=> $this->_now,
													':payer'				=> $_user
	 ));	

	 if($stmt){
		 return 'Allocations updated';
	 }else{
		return 'Error, could not update allocations details';
	 }

	}

	public function restoreAllocation($_user,$_date){	
		$stmt = $this->_conn->prepare("Update allocations 
										  set status    = 'Pending',
											trans_date  = '$_date',
										      timestamp = :timestamp
									where payer     	= :payer
									  and status = 'Deleted'");

	  $stmt->execute(array( ':timestamp'  	=> $this->_now,
													':payer'				=> $_user
	 ));	

	 if($stmt){
		 return 'Allocations updated';
	 }else{
		return 'Error, could not update allocations details';
	 }

	}

	public function setAllocationToPaid($_trans_id){	
		$stmt = $this->_conn->prepare("Update allocations 
																			set status    = 'Paid',	
																					timestamp = :timestamp
																		where trans_id  = :trans");

	  $stmt->execute(array( ':timestamp'  	=> $this->_now,
													':trans'				=> $_trans_id
	 ));	

	 if($stmt){
		 return 'Allocations updated';
	 }else{
		return 'Error, could not update allocations details';
	 }

	}
	
	public function confirmPayment($_trans_id){	
		$stmt = $this->_conn->prepare("Update allocations 
									set status = 'Paid',	
									timestamp  = :timestamp
								where trans_id = :trans");

	  $stmt->execute(array( ':timestamp'  	=> $this->_now,
								':trans'	=> $_trans_id
	 ));	

	 if($stmt){
		 return 'Allocations updated';
	 }else{
		return 'Error, could not update allocations details';
	 }

	}

	public function reversePayment($_trans_id){	
		$stmt = $this->_conn->prepare("Update allocations 
										set status = 'Reversed',	
										timestamp  = :timestamp
									where trans_id = :trans");

	  $stmt->execute(array( ':trans' => $_trans_id,':timestamp' => $this->_now
	 ));	

	 if($stmt){
		 return 'Payment deleted';
	 }else{
		return 'Error, could not update allocations details';
	 }

	}

	public function hoursRemaining($_s1)
	{

		$datetime = date("Y-m-d H:i:s",strtotime($_s1));

        // Convert datetime to Unix timestamp
        $timestamp = strtotime($datetime);
        // Subtract time from datetime
        $time = $timestamp + (12 * 60 * 60);
        // Date and time after subtraction
		$datetime = date("Y-m-d H:i:s", $time);

		$remaining  = strtotime($datetime)-strtotime(date('Y-m-d H:i:s'));

		return $remaining/3600;
	}

	public function deleteAllPayments(){
		$stmt = $this->_conn->prepare("select * from allocations where status = 'Pending'");		
		$stmt->execute();		

		$_tree = new treeClass();
		$_mem  = new memberClass();
		while($_row = $stmt->fetch(PDO::FETCH_ASSOC)){	
			if($this->hoursRemaining($_row['trans_date'])<=0){
				$_upd = $this->deletePayment($_row['trans_id']);
				if($_upd=='Allocations deleted'){
                    if($this->getDescription($_row['trans_id'])=='Reg Fee'){
                        $_active = $_mem->blockMember($_row['payer']);                        
                        if($_active == 'Member deactivated'){
                            $_remove = $_tree->removeFromTree($_row['payer']);
                            /*if($_remove=='Removed from dl1' || $_remove=='Removed from dl2'){
                                //$_tree->updateStageForAll();
                                echo $_remove;
                            }
                            else
                                echo 'Deleted but could not be removed from your tree';*/
                        }                                          
                    }else{
                        $_active = $_mem->deactivateMember($_row['payer']);                        
                        /*if($_active == 'Member deactivated'){
                            echo 'Payment Deleted';
                        }*/
                    }
                }	
			}
		}		
	}

	public function deletePayment($_trans_id){	
		$stmt = $this->_conn->prepare("Update allocations 
									set status = 'Deleted',	
									timestamp  = :timestamp
								where trans_id = :trans");

	  $stmt->execute(array( ':timestamp'  	=> $this->_now,
								':trans'	=> $_trans_id
	 ));	

	 if($stmt){
		 return 'Allocations deleted';
	 }else{
		return 'Error, could not delete allocations details';
	 }

	}


	public function setAllocationToPending($_trans_id){	
		$stmt = $this->_conn->prepare("Update allocations 
																			set status    = 'Pending',	
																					timestamp = :timestamp
																		where trans_id  = :trans");

	  $stmt->execute(array( ':timestamp'  	=> $this->_now,
													':trans'				=> $_trans_id
	 ));	

	 if($stmt){
		 return 'Allocations updated';
	 }else{
		return 'Error, could not update allocations details';
	 }

	}

	public function uploadPoP($_ass_ref_no, $_file_name)
    {   
        $_tstamp = $this->_now;
        $_user   = ME;

        $_sql = "update allocations 
                    set pop  = '$_file_name', 
                   timestamp = '$_tstamp'
              where trans_id = '$_ass_ref_no'";
        
        $stmt = $this->_conn->prepare($_sql);
                                                  
        $_save = $stmt->execute();                                

        if($_save){
            return 'Uploaded';
        } else{
            return 'Not uploaded';
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