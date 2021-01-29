<?php

class treeClass extends Model
{	

	private $_now;	

	public function __construct(){
				parent::__construct();
				$this->_now = NOW;
	}
    
	public function getAllTrees(){		
		$stmt = $this->_conn->prepare("select * from tree");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
			return $stmt;		  
		}else{	
			return '';		
		}	    					  	
	}
	
	public function myTeamList($_user){		
		$stmt = $this->_conn->prepare("select * from tree where sponsor = '$_user'");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		return $stmt;		  	    					  	
	}

	public function noOfDownliners($_user){
		$_counter = 0;
		$stmt = $this->_conn->prepare("select dl1,dl2 from tree where member_no = '$_user'");	
		$stmt->execute();
		if($stmt->rowCount()>0){
			$_row = $stmt->fetch(PDO::FETCH_ASSOC);
			if($_row['dl1']>0){
				$_counter += 1;	
			}

			if($_row['dl2']>0){
				$_counter += 1;	
			}
		}	
		return $_counter;	
	}
    
  
	public function addMember($_id, $_sponsor){		
		$stmt = $this->_conn->prepare("insert 
										 into tree
									 values( :member_no,
											:sponsor,
											0,
                      0,
                      '0',
                      :timestamp)");
                                             
		$stmt->execute(array(':member_no'	=>$_id,
							 					 ':sponsor'     =>$_sponsor,
							 					 ':timestamp'	=>$this->_now
							));	
		  											  
		if($stmt){
            return "Tree details inserted";		  
		}else{	
			return "Error inserting tree details";		
		}	    					  	
	}

	public function getSponsor($_user_no){		
		$stmt = $this->_conn->prepare("select sponsor from tree where member_no='$_user_no'");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
			$_row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $_row['sponsor'];		  
		}else{	
			return 0;		
		}	    					  	
	}

	public function selectDUpliner($_user_no){		

		if($_user_no==0)
			return 0;
			
		$stmt = $this->_conn->prepare("select member_no from tree where dl1='$_user_no'");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count==0){
				$stmt = $this->_conn->prepare("select member_no from tree where dl2='$_user_no'");	
				$stmt->execute();
				
				if($stmt->rowCount()>0){
					$_row = $stmt->fetch(PDO::FETCH_ASSOC);
					return $_row['member_no'];
				}
				else
					return 0;								
		}else{	
				$_row = $stmt->fetch(PDO::FETCH_ASSOC);
				return $_row['member_no'];				
		}	    					  	
	}

	public function getStage($_user_no){		
		$stmt = $this->_conn->prepare("select stage from tree where member_no='$_user_no'");	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
			$_row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $_row['stage'];		  
		}else{	
			return '';		
		}	    					  	
	}

	public function getStageNumbers($_st){		
		$stmt = $this->_conn->prepare("select count(*) as count from allocations where description ='Stage $_st'");	
		$stmt->execute();

		$_row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $_row['count'];		  
				  	
	}

	public function countActiveRecruits($_user_no){

		$stmt = $this->_conn->prepare("select count(*) as count
																		 from tree t
																		where sponsor='$_user_no'
																			and exists (select 1 
																										from members
																									 where member_no = t.member_no
																									   and active = 'Yes')"
																	   );	
		$stmt->execute();
		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
			$_row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $_row['count'];		  
		}else{	
			return '';		
		}	    					  	
	}

	public function getAllFromStage1($_ul_user){	

		$stmt = $this->_conn->prepare("select * from tree where member_no='$_ul_user'");	
		$stmt->execute();
		$_count = $stmt->rowCount();
		$_mem = new memberClass();
		$_matrix = array();

		if($_count>0){
			$_row = $stmt->fetch(PDO::FETCH_ASSOC);
			$_dl1 = $_row['dl1'];
			$_dl2 = $_row['dl2'];
			
			$stmt_dl1 = $this->_conn->prepare("select stage from tree where member_no='$_dl1'");	
			$stmt_dl1->execute();
			$_row_dl1 = $stmt_dl1->fetch(PDO::FETCH_ASSOC);

			$stmt_dl2 = $this->_conn->prepare("select stage from tree where member_no='$_dl2'");	
			$stmt_dl2->execute();
			$_row_dl2 = $stmt_dl2->fetch(PDO::FETCH_ASSOC);

			if($_row_dl1['stage']>0){
				array_push($_matrix, $_dl1);
				if($_row_dl2['stage']>0){
					array_push($_matrix, $_dl2);				
				}elseif($_mem->isActive($_dl2)=='Active'){
					return $_dl2;
				}				
			}elseif($_mem->isActive($_dl1)=='Active'){
				return $_dl1;
			}		
		}		
		return $_matrix;
	}

	public function getTheRestOfStages($_input_array){	

		$_output_array = array();
		$_mem = new memberClass();
		$_found = false;		
		foreach($_input_array as $_arr)
		{
			$stmt = $this->_conn->prepare("select * from tree where member_no='$_arr'");	
			$stmt->execute();
			$_count = $stmt->rowCount();
	
			if($_count>0){
				$_row = $stmt->fetch(PDO::FETCH_ASSOC);
				$_dl1 = $_row['dl1'];
				$_dl2 = $_row['dl2'];
				
				$stmt_dl1 = $this->_conn->prepare("select stage from tree where member_no='$_dl1'");	
				$stmt_dl1->execute();
				$_row_dl1 = $stmt_dl1->fetch(PDO::FETCH_ASSOC);
	
				$stmt_dl2 = $this->_conn->prepare("select stage from tree where member_no='$_dl2'");	
				$stmt_dl2->execute();
				$_row_dl2 = $stmt_dl2->fetch(PDO::FETCH_ASSOC);


				if($_row_dl1['stage']>0){
					array_push($_output_array, $_dl1);
					if($_row_dl2['stage']>0){
						array_push($_output_array, $_dl2);				
					}elseif($_mem->isActive($_dl2)=='Active'){
						return $_dl2;
					}				
				}elseif($_mem->isActive($_dl1)=='Active'){
					return $_dl1;
				}
			}
		}
		return $_output_array;		
	}

	public function hasActiveDownliners($_ul_user){	
		
		$_s1	 = $_ul_user;
		$_c      = $this->checkCurrentDl($_s1);
		$_br 	 = 'N';	

		while($_c=='Full'){
			$_s1 = $this->getAllFromStage1($_ul_user);

			
			while(is_array($_s1)){
				if(empty($_s1)){
					$_br = 'Y';					
					break 2;
				}else{
					$_s1 = $this->getTheRestOfStages($_s1);
					//print_r($_s1);
				}
				//break;
			}
			$_c = $this->checkCurrentDl($_s1);
			break;
		}

		if ($_br=='Y')
			return false;
		else
			return true;
	}

	public function placeDownliners($_ul_user, $_dl_user){	
		
			
			$_s1	 = $_ul_user;
			$_c      = $this->checkCurrentDl($_s1);

			while($_c=='Full'){
				$_s1 = $this->getAllFromStage1($_ul_user);
				while(is_array($_s1)){
					$_s1 = $this->getTheRestOfStages($_s1);
				}
				$_c = $this->checkCurrentDl($_s1);
				break;
			}

			if($_c=='dl1'){
				$stmt  = $this->_conn->prepare("Update tree 
													 set  $_c = :new_dl,
										            timestamp = :timestamp
										      where member_no = :users");
			}else{
				$stmt  = $this->_conn->prepare("Update tree 
													set  $_c = :new_dl,
													stage    = '1',
									 		      timestamp  = :timestamp
									        where member_no  = :users");
			}

			$stmt->execute(array( ':new_dl'			  =>$_dl_user,
												    ':timestamp'	  =>$this->_now,
												    ':users'				=>$_s1
			 ));	
			


			$_allClass = new allocationsClass();
			$_results = $_allClass->addAllocation($_dl_user, $_s1,'200','Reg Fee');

			$_inc = new incentivesClass();
			$_inc->createIncentive($_s1,'1');
			$_inc->createIncentive($_s1,'2');
			//$_inc->createIncentive($_s1,'3');
			 						 			
			/*if($this->getStage($_ul_user)<$this->getCurrentStage($_ul_user)){
				 
				$stmt = $this->_conn->prepare("Update tree 
																	 set stage = :stage,
																	 timestamp = :timestamp
														 where member_no = :user");
														
			  $stmt->execute(array( ':stage'		=>$this->getCurrentStage($_ul_user),
												    ':timestamp'	=>$this->_now,
												    ':user'				=>$_ul_user
			  ));
				$_inc = new incentivesClass();
				$_inc->createIncentive($_ul_user,$this->getCurrentStage($_ul_user));
			}*/

			/*if($this->getStage($_s1)<$this->getCurrentStage($_s1)){
				
				$stmt = $this->_conn->prepare("Update tree 
																	 set stage = :stage,
																	 timestamp = :timestamp
														 where member_no = :user");
														
				$stmt->execute(array( ':stage'			=>$this->getCurrentStage($_s1),
													    ':timestamp'	=>$this->_now,
													    ':user'				=>$_s1
				));

				$_find_sponsor = $this->selectDUpliner($_s1);
				while($_find_sponsor>0){
					if($this->getStage($_find_sponsor)<$this->getCurrentStage($_find_sponsor)){
				
						$stmt = $this->_conn->prepare("Update tree 
																			 set stage = :stage,
																			 timestamp = :timestamp
																 where member_no = :user");
																
						$stmt->execute(array( ':stage'			=>$this->getCurrentStage($_find_sponsor),
																	':timestamp'	=>$this->_now,
																	':user'				=>$_find_sponsor
						));
		
												
					}	
					$_find_sponsor = $this->selectDUpliner($_find_sponsor);
				}
			}*/
				 
	 		return 'New downliners placed';
			 
	}

	public function checkCurrentDl($_user_no){	

		$stmt = $this->_conn->prepare("select dl1,dl2 
										 from tree 
										where member_no='$_user_no' 
										  and exists(select 1 from members where member_no = tree.member_no and active = 'Yes')");	
		$stmt->execute();

		$_count = $stmt->rowCount();	
		  											  
		if($_count>0){
			$_row = $stmt->fetch(PDO::FETCH_ASSOC);
			if($_row['dl1']==0)
				return 'dl1';
			elseif($_row['dl2']==0)
				return 'dl2';	 
			else
				return 'Full';
		}else{	
			return 'Full';		
		}	 
	}

	public function getCurrentStage($_user){

			$_no_of_dl = $this->numberOfDl($_user);
			$_stage = 0;
			if($_no_of_dl<2){
				$_stage = 0;
			}elseif($_no_of_dl>=2 && $_no_of_dl<6){
				$_stage = 1;
			}elseif($_no_of_dl>=6 && $_no_of_dl<14){
				$_stage = 2;
			}elseif($_no_of_dl>=14 && $_no_of_dl<30){
				$_stage = 3;
			}elseif($_no_of_dl>=30){
				$_stage = 4;
			}

			return $_stage;
	} 

	public function getCurrentStage_2($_user){

		$_no_of_dl = $this->numberOfDl_2($_user);
		$_stage = 0;
		if($_no_of_dl<2){
			$_stage = 0;
		}elseif($_no_of_dl>=2 && $_no_of_dl<6){
			$_stage = 1;
		}elseif($_no_of_dl>=6 && $_no_of_dl<14){
			$_stage = 2;
		}elseif($_no_of_dl>=14 && $_no_of_dl<30){
			$_stage = 3;
		}elseif($_no_of_dl>=30){
			$_stage = 4;
		}

		return $_stage;
} 


	public function numberOfDl($_user){

		$_arr_1 = array($_user);
		$_counter = 0;
		$_arr_1 = $this->countStage($_arr_1);

		if(sizeof($_arr_1)==2){
			$_arr_1 = $this->countStage($_arr_1);
			$_counter = 2;
			if(sizeof($_arr_1)==4){
				$_counter = 2+sizeof($_arr_1);
				$_arr_1 = $this->countStage($_arr_1);
				if(sizeof($_arr_1)==8){
					$_counter = 2+4+sizeof($_arr_1);
					$_arr_1 = $this->countStage($_arr_1);
					if(sizeof($_arr_1)==16){
						$_counter = 2+4+8+sizeof($_arr_1);
						$_arr_1 = $this->countStage($_arr_1);
						if(sizeof($_arr_1)==32){
							$_counter = 2+4+8+16+sizeof($_arr_1);
						}else{
							$_counter += sizeof($_arr_1);
						}
					}else{
						$_counter += sizeof($_arr_1);
					}
				}else{
					$_counter += sizeof($_arr_1);
				}
			}else{
				$_counter += sizeof($_arr_1);
			}
		}else{
			$_counter += sizeof($_arr_1);
		}
		return $_counter;
	}

	public function numberOfDl_2($_user){

		$_arr_1 = array($_user);
		$_counter = 0;
		$_arr_1 = $this->countStage_2($_arr_1);

		if(sizeof($_arr_1)==2){
			$_arr_1 = $this->countStage_2($_arr_1);
			$_counter = 2;
			if(sizeof($_arr_1)==4){
				$_counter = 2+sizeof($_arr_1);
				$_arr_1 = $this->countStage_2($_arr_1);
				if(sizeof($_arr_1)==8){
					$_counter = 2+4+sizeof($_arr_1);
					$_arr_1 = $this->countStage_2($_arr_1);
					if(sizeof($_arr_1)==16){
						$_counter = 2+4+8+sizeof($_arr_1);
						$_arr_1 = $this->countStage_2($_arr_1);
						if(sizeof($_arr_1)==32){
							$_counter = 2+4+8+16+sizeof($_arr_1);							
						}else{
							$_counter += sizeof($_arr_1);
						}
					}else{
						$_counter += sizeof($_arr_1);
					}
				}else{
					$_counter += sizeof($_arr_1);
				}
			}else{
				$_counter += sizeof($_arr_1);
			}
		}else{
			$_counter += sizeof($_arr_1);
		}
		return $_counter;
	}

	public function countStage($_input_array){	

		$_output_array = array();
		$_mem = new memberClass();
		$_found = false;
		foreach($_input_array as $_arr)
		{
			$stmt = $this->_conn->prepare("select * from tree where member_no='$_arr'");	
			$stmt->execute();
			$_count = $stmt->rowCount();
	
			if($_count>0){
				$_row = $stmt->fetch(PDO::FETCH_ASSOC);
				$_dl1 = $_row['dl1'];
				$_dl2 = $_row['dl2'];
				
				if($_dl1 > 0){
					array_push($_output_array, $_dl1);
					if($_dl2 > 0){
						array_push($_output_array, $_dl2);
					}else{
						return $_output_array;
						break;
					}
				}else{
					return $_output_array;
					break;
				}
			}
		}
		return $_output_array;		
	}

	public function countStage_2($_input_array){	

		$_output_array = array();
		$_mem = new memberClass();
		$_found = false;
		foreach($_input_array as $_arr)
		{
			$stmt = $this->_conn->prepare("select * from tree where member_no='$_arr'");	
			$stmt->execute();
			$_count = $stmt->rowCount();
	
			if($_count>0){
				$_row = $stmt->fetch(PDO::FETCH_ASSOC);
				$_dl1 = $_row['dl1'];
				$_dl2 = $_row['dl2'];
	
				if($_dl1 > 0 && $_mem->isActive($_dl1)=='Active'){
					array_push($_output_array, $_dl1);
					if($_dl2 > 0 && $_mem->isActive($_dl2)=='Active'){
						array_push($_output_array, $_dl2);
					}else{
						return $_output_array;
						break;
					}
				}else{
					return $_output_array;
					break;
				}
			}
		}
		return $_output_array;		
	}

	public function populateTree($_input_array){	

		$_output_array = array();
		$_found = false;
		$_zero = 0;
		foreach($_input_array as $_arr)
		{
			$stmt = $this->_conn->prepare("select * from tree where member_no='$_arr'");	
			$stmt->execute();
			$_count = $stmt->rowCount();
	
			if($_count>0){
				$_row = $stmt->fetch(PDO::FETCH_ASSOC);
				$_dl1 = $_row['dl1'];
				$_dl2 = $_row['dl2'];
	
				if($_dl1 > -1){
					array_push($_output_array, $_dl1);
					if($_dl2 > -1){
						array_push($_output_array, $_dl2);						
					}else{
						return $_output_array;
						break;
					}
				}else{
					return $_output_array;
					break;
				}
			}else{
					array_push($_output_array, $_zero);
					array_push($_output_array, $_zero);			
			}
		}
		return $_output_array;		
	}

	public function updateStageForAll(){

		$stmt_1 = $this->_conn->prepare("select member_no from tree");	
		$stmt_1->execute();
		$_counter = 0;
		while($_row = $stmt_1->fetch(PDO::FETCH_ASSOC)){
			if($this->getStage($_row['member_no'])<>$this->getCurrentStage($_row['member_no'])){
				$_counter += 1;
				$stmt = $this->_conn->prepare("Update tree 
																	 set stage = :stage,
																	 timestamp = :timestamp
														 where member_no = :user");

				$stmt->execute(array( ':stage'			=>$this->getCurrentStage($_row['member_no']),
															':timestamp'	=>$this->_now,
															':user'				=>$_row['member_no']
				));
			
				$_inc = new incentivesClass();
				$_inc->createIncentive($_row['member_no'],$this->getCurrentStage($_row['member_no']));				
			}
		}
		return $_counter;
	}

	public function updateStages(){
		$stmt_1 = $this->_conn->prepare("update tree set stage='1' where dl2>0 and stage='0'");	
		$stmt_1->execute();		
	}

	public function removeFromTree($_user){
		$stmt = $this->_conn->prepare("Update tree 
										set dl1 = 0,
										stage = 0,
										timestamp = '$this->_now'
										where dl1 = '$_user'");

		$stmt->execute();

		if($stmt->rowCount()==0){
			$stmt = $this->_conn->prepare("Update tree 
											set dl2 = 0,
											stage = 0,
											timestamp = '$this->_now'
																where dl2 = '$_user'");

			$stmt->execute();
			if($stmt->rowCount()==1){
				return 'Removed from dl2';
			}else{
				return 'Not found';
			}
		}else{
			return 'Removed from dl1';
		}
	}

	public function placeAllMembers(){

		$stmt_1 = $this->_conn->prepare("select member_no,sponsor 
																	from tree t1 
																 where not exists(select 1 
																										from tree t2 
																									 where dl1 = t1.member_no or
																												 dl2 = t1.member_no)
																	 and member_no <> '1001'");	
		$stmt_1->execute();
		$_counter = 0;
		while($_row = $stmt_1->fetch(PDO::FETCH_ASSOC)){
			$_obj_2 = new treeClass();
			$_activate = $_obj_2->placeDownliners($_row['sponsor'],$_row['member_no']);	
			$_counter += 1;
		}
		return $_counter;
	}

       
}

?>