<?php

    class Associations extends Model{

        protected $_pledge;

        public function __construct(){       
            parent::__construct();
            $this->_pledge = array();
        }

        public function getHoursPassed($req_date){

            $hourdiff = round((strtotime(NOW) - strtotime($req_date))/3600, 2);
            if ($hourdiff<0){
                return 0;
            }else {
                return $hourdiff;
            }
        }

        public function findAllAssoc(){            
            $_sql  = "select * from assoc order by assoc_ref_no desc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        } 

        public function findByAssocRefNo($_ref_no){           
            $_sql  = "select * from assoc where assoc_ref_no = '$_ref_no' order by assoc_ref_no desc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        public function findByPhUserNo($_user_no){           
            $_sql  = "select * from assoc where ph_user_number = '$_user_no' and status <> 'Rejected' order by assoc_ref_no desc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt;
        }

        public function findByPhUserNoAssigned($_user_no){           
            $_sql  = "select * from assoc where ph_user_number = '$_user_no' and status='Assigned' order by assoc_ref_no desc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function findByPhUserNoPaid($_user_no){           
            $_sql  = "select * from assoc where ph_user_number = '$_user_no' and status='Paid' order by assoc_ref_no desc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function findByPhUserSumPaid($_user_no){           
            $_sql  = "select COALESCE(sum(amount),0) as count from assoc where ph_user_number = '$_user_no' and status='Paid' order by assoc_ref_no desc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			$_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            
            return $_row['count'];
        }

        public function totalPaidRewards($_user_no){           
            $_sql  = "select COALESCE(sum(amount),0) as count 
                        from assoc a
                       where gh_user_number = '$_user_no' 
                         and status='Paid'
                         and exists (select 1
                                          from deposits
                                          where duration = 550
                                            and user_no = a.ph_user_number
                                            and trans_id = a.ph_ref_no)";

            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			$_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            
            return $_row['count'];
        }

        public function topNetworkEarners(){           
            $_sql  = "select user_no, username, name, surname, sum(amount) as count 
                        from assoc a, members m 
                       where a.gh_user_number = m.user_no 
                         and status='Paid' 
                         and exists (select 1 
                                       from deposits 
                                      where duration = 550 
                                        and user_no = a.ph_user_number 
                                        and trans_id = a.ph_ref_no) 
                    group by gh_user_number order by 5 desc limit 3";

            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt;
        }

        public function topP2PEarners(){           
            $_sql  = "select user_no, username, name, surname, sum(amount) as count 
                        from assoc a, members m 
                       where a.gh_user_number = m.user_no 
                         and status='Paid' 
                         and exists (select 1 
                                       from deposits 
                                      where duration = 5
                                        and user_no  = a.ph_user_number 
                                        and trans_id = a.ph_ref_no) 
                         and exists (select 1 
                                        from deposits 
                                       where duration = 5
                                         and user_no  = a.gh_user_number 
                                         and trans_id = a.original_ph)                
                    group by gh_user_number order by 5 desc limit 3";

            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt;
        }
        
        public function findByGhUserNo($_user_no){           
            $_sql  = "select * from assoc where gh_user_number = '$_user_no' and status <> 'Rejected' order by assoc_ref_no desc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt;
        } 

        public function findByGhUserNoAssigned($_user_no){           
            $_sql  = "select * from assoc where gh_user_number = '$_user_no' and status='Assigned' order by assoc_ref_no desc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function findByGhUserNoPaid($_user_no){           
            $_sql  = "select * from assoc where gh_user_number = '$_user_no' and status='Paid' order by assoc_ref_no desc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function findByGhUserSumPaid($_user_no){           
            $_sql  = "select COALESCE(sum(amount),0) as count 
                       from assoc a
                      where gh_user_number = '$_user_no' 
                        and status='Paid' 
                        and not exists (select 1
                                          from deposits
                                          where duration = 550
                                            and user_no = a.ph_user_number
                                            and trans_id = a.ph_ref_no)";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			$_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            
            return $_row['count'];
        }

        public function findByPHRefNo($_ref_no){           
            $_sql  = "select * from assoc where ph_ref_no = '$_ref_no' order by assoc_ref_no desc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function findByGHRefNo($_ref_no){           
            $_sql  = "select * from assoc where gh_ref_no = '$_ref_no' order by assoc_ref_no desc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function findByOriginalPh($_ref_no){           
            $_sql  = "select * from assoc where original_ph = '$_ref_no' order by assoc_ref_no desc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function SumByOriginalPhCreated($_ref_no){           
            $_sql  = "select COALESCE(sum(amount),0) as count from assoc where original_ph = '$_user_no' and status='Created'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			$_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            
            return $_row['count'];
        }

        public function SumByOriginalPhPaid($_ref_no){           
            $_sql  = "select COALESCE(sum(amount),0) as count from assoc where original_ph = '$_user_no' and status='Paid'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			$_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            
            return $_row['count'];
        }

        public function SumByOriginalPhAssigned($_ref_no){           
            $_sql  = "select COALESCE(sum(amount),0) as count from assoc where original_ph = '$_user_no' and status='Assigned'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			$_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            
            return $_row['count'];
        }

        public function allocate($_withdrawal_status){            
            $_w = new Withdrawals();
            if(($_w->newWithdrawalsDue()>0 && $_withdrawal_status=='Created') or ($_w->reAllWithdrawalsDue()>0 && $_withdrawal_status=='Re-All') ){
	    		$stmt = $this->_conn->prepare("select *
	    								   from withdrawals
	    								  where status = :status
                                          order by trans_id asc
                                          limit 10");

	    		$stmt->bindParam(':status', $_withdrawal_status);
	    		$stmt->execute();
	    		$_count = $stmt->rowCount();
	    		$i=0;

	    		while(($_row=$stmt->fetch(PDO::FETCH_ASSOC)) && $i<$_count){
	    			$_s = $this->w_t_p($_row['user_no'],$_row['total'],$_row['trans_id'],$_row['deposit_trans_id']);
	    			if($_s == 'success'){
	    				$i+=1;
	    				$_status = $i.' Allocation(s) created successfully';
	    			}else{
	    				$_status = $i.' Allocation(s) created, Error: '.$_s;
	    				break;
	    			}
	    		}
	    		return $_status;
	    	} else
	    		return 'No withdrawals found';            
        }
        
        public function allocate_admin($_gh_user_no, $_ph_trans_id, $_amount){

            $_status = '';
            $_pledge = new Pledges();
            $_withdraw = new Withdrawals();

            $_create = $_pledge->createAdminPledge($_gh_user_no,$_amount);
            $_w      = $_withdraw->withdrawAdminFor($_gh_user_no);
            
            if($_withdraw->newWithdrawalsDueFor($_gh_user_no)>0){
    
                $stmt = $this->_conn->prepare("select *
                                                 from withdrawals
                                                where status in ('Created')
                                                  and user_no = '$_gh_user_no'
                                                order by trans_id desc");
                $stmt->execute();
    
                $_count = $stmt->rowCount();
    
                if ($_count>1){
                    $_count = 1;
                }
    
                $i=0;
    
                while(($_row=$stmt->fetch(PDO::FETCH_ASSOC)) && $i<$_count){
    
    
                        $_s = $this->w_t_p_admin($_ph_trans_id, $_row['user_no'],$_row['total'],$_row['trans_id'],$_row['deposit_trans_id']);
    
                        if($_s == 'success'){
                            $i+=1;
                            $_status = $i.' Allocation(s) created successfully';
                        }else{
                            $_status = $i.' Allocation(s) created, Error: '.$_s;
                            break;
                        }
                }
    
                return $_status;
    
            } else
                return 'No withdrawals found';
        }

        public function allocate_homeless($_gh_user_no, $_gh_trans_id, $_ph_trans_id, $_amount){

            $_status = '';
            $_pledge = new Pledges();
            $_withdraw = new Withdrawals();

            /*$_create = $_pledge->createAdminPledge($_gh_user_no,$_amount);
            $_w      = $_withdraw->withdrawAdminFor($_gh_user_no);*/
            
            if($_withdraw->newHomelessWithdrawalsDueFor($_gh_trans_id)>0){
    
                $stmt = $this->_conn->prepare("select *
                                                 from withdrawals
                                                where status in ('Re-All')
                                                  and user_no = '$_gh_user_no'
                                                  and trans_id = '$_gh_trans_id'
                                                order by trans_id desc");
                $stmt->execute();
    
                $_count = $stmt->rowCount();
    
                if ($_count>1){
                    $_count = 1;
                }
    
                $i=0;
    
                while(($_row=$stmt->fetch(PDO::FETCH_ASSOC)) && $i<$_count){
    
    
                        $_s = $this->w_t_p_admin($_ph_trans_id, $_row['user_no'],$_amount,$_row['trans_id'],$_row['deposit_trans_id'],$_order='Homeless');
    
                        if($_s == 'success'){
                            $i+=1;
                            $_status = $i.' Allocation(s) created successfully';
                        }else{
                            $_status = $i.' Allocation(s) created, Error: '.$_s;
                            break;
                        }
                }
    
                return $_status;
    
            } else
                return 'No withdrawals found';
        }
        
        public function w_t_p($gh_user_number,$gh_amount,$gh_ref_no,$original_ph){
            date_default_timezone_set("Africa/Johannesburg");
    
            $now_date = date("Y-m-d H:i:s");
    
            $field = '';
    
            $row_excess = 0;
    
            $stmt = $this->_conn->prepare("select COALESCE(sum(excess),0) as sum_excess
                                       from deposits
                                      where trans_id > 0
                                        and status in ('Assigned','Created')
                                        and excess  <> 0
                                        and datediff(CURRENT_DATE,trans_date) >=3
                                        and duration not in (0,999,550)
                                        and user_no <> '$gh_user_number'
                                        and user_no <> 0");
    
            $stmt->execute();
    
            $row_sql_results=$stmt->fetch(PDO::FETCH_ASSOC);
    
            $row_excess = $row_sql_results['sum_excess'];
    
            //return $row_excess;
    
            if($row_excess<$gh_amount){
                $field = 'Funds exhausted.';
            }else{
                $stmt = $this->_conn->prepare("select *
                                           from deposits
                                          where trans_id > 0
                                             and status in ('Assigned','Created')
                                             and duration not in (0,999,550)
                                             and datediff(CURRENT_DATE,trans_date) >=3
                                             and excess <> 0
                                             and user_no <> '$gh_user_number'
                                             and user_no >= 0
                                            order by trans_id asc");
    
                $stmt->execute();
                $count = $stmt->rowCount();
    
                $stmt2 = $this->_conn->prepare("select max(assoc_ref_no) as assoc_ref_no from assoc");
                $stmt2->execute();
                $result_count1 = $stmt2->fetch(PDO::FETCH_ASSOC);
                $count11 = $result_count1['assoc_ref_no'];
    
                if ($count11==0){
                    $assoc_ref_no = 100;
                }else{
                    $assoc_ref_no = $count11+1;
                }
    
                if ($count==0){
                    $field = 'No new offers found. Please try again in 6 hours.';
                }else{
    
                    while (($row1=$stmt->fetch(PDO::FETCH_ASSOC)) and ($gh_amount>0)){
    
                            $ph_ref_no = $row1['trans_id'];
                            $ph_user_number = $row1['user_no'];
    
                            $excess = 0;
                            $contributed_amount = 0;
                            $alloc_amt = 0;
                            $assoc_ph_amount = $row1['excess'];
                            $check = $assoc_ph_amount;
                            $assoc_amount = $assoc_ph_amount;
    
                            if($assoc_amount>$gh_amount){
                                $excess = $assoc_amount-$gh_amount;
                                $contributed_amount = $gh_amount;
                            }else{
                                $contributed_amount = $assoc_amount;
                                $excess = 0;
                            }
    
                            //$contributed_amount += $contributed_amount;
                            $gh_amount -= $contributed_amount;
    
                            date_default_timezone_set("Africa/Johannesburg");
                            $f_now = date("Y-m-d H:i:s");
    
                            $stmt3 = $this->_conn->prepare("update deposits
                                                  set status = 'Assigned',
                                                        excess = '$excess',
                                                   timestamp = '$f_now'
                                            where  trans_id  = '$ph_ref_no'
                                              and status in ('Created','Assigned')");
    
                            if ($stmt3->execute()){
    
                                $stmt4 = $this->_conn->prepare("insert
                                    into assoc
                                    values ($gh_user_number,$ph_user_number,$contributed_amount,$assoc_ref_no,$ph_ref_no,$gh_ref_no,$original_ph,'$now_date','Assigned','','','$now_date')");
                                if ($stmt4->execute()){
    
                                    $stmt5 = $this->_conn->prepare("update withdrawals
                                                                 set status  =	'Assigned',
                                                                   timestamp =	'$f_now'
                                                               where trans_id = '$gh_ref_no'");
    
                                    if ($stmt5->execute()){
    
                                        $field = 'success';
    
                                        $stmt6 = $this->_conn->prepare("select *
                                                                    from members
                                                                   where user_no = '$ph_user_number'");
    
                                        if ($stmt6->execute()){
    
                                            $_row = $stmt6->fetch(PDO::FETCH_ASSOC);
                                            $_Msg = new Msg();

                                            $f_sendsms_no = $_row['mobile'];
                                            $user = $_row['name'];
    
                                            $_send = new MySMS($f_sendsms_no,$_Msg->getMsg(139));
                                            $_sms = $_send->sendSMS();
                                        }
    
                                        $stmt7 = $this->_conn->prepare("select *
                                                                          from members
                                                                         where user_no = '$gh_user_number'");
    
                                        if ($stmt7->execute()){
    
                                            $_row = $stmt7->fetch(PDO::FETCH_ASSOC);
    
                                            $f_sendsms_no = $_row['mobile'];
                                            $user = $_row['name'];
    
                                            $_send = new MySMS($f_sendsms_no,$_Msg->getMsg(140));
                                            $_sms = $_send->sendSMS();
                                        }
    
                                    }
                                }else{
                                    $field = 'Could not insert allocation';
                                }
    
                                $assoc_ref_no += 1;
    
                            }else{
                                $field = 'Status was not updated to Assigned';
                            }  
                            $assoc_amount = 0;
                    }
    
                }
            }
    
            return $field;
        }


        public function w_t_p_admin($_ph_trans_id,$gh_user_number,$gh_amount,$gh_ref_no,$original_ph,$_order){
            date_default_timezone_set("Africa/Johannesburg");
    
            $now_date = date("Y-m-d H:i:s");
    
            $field = '';
    
            $row_excess = 0;
    
            $stmt = $this->_conn->prepare("select COALESCE(sum(excess),0) as sum_excess
                                       from deposits
                                      where trans_id = '$_ph_trans_id'
                                        and status in ('Assigned','Created')
                                        and excess  <> 0
                                        and duration not in (0)
                                        and user_no <> '$gh_user_number'
                                        and user_no <> 0");
    
            $stmt->execute();
    
            $row_sql_results=$stmt->fetch(PDO::FETCH_ASSOC);
    
            $row_excess = $row_sql_results['sum_excess'];

            $_my_dur=15;
    
            //return $row_excess;
    
            if($row_excess<$gh_amount){
                $field = 'Funds exhausted.';
            }else{
                $stmt = $this->_conn->prepare("select *
                                           from deposits
                                          where trans_id = '$_ph_trans_id'
                                             and status in ('Assigned','Created')
                                             and duration not in (0)
                                             and excess <> 0
                                             and user_no <> '$gh_user_number'
                                             and user_no >= 0
                                            order by trans_id asc");
    
                $stmt->execute();
                $count = $stmt->rowCount();
    
                $stmt2 = $this->_conn->prepare("select max(assoc_ref_no) as assoc_ref_no from assoc");
                $stmt2->execute();
                $result_count1 = $stmt2->fetch(PDO::FETCH_ASSOC);
                $count11 = $result_count1['assoc_ref_no'];
    
                if ($count11==0){
                    $assoc_ref_no = 100;
                }else{
                    $assoc_ref_no = $count11+1;
                }
    
                if ($count==0){
                    $field = 'No new offers found. Please try again in 6 hours.';
                }else{
    
                    while (($row1=$stmt->fetch(PDO::FETCH_ASSOC)) and ($gh_amount>0)){
    
                            $ph_ref_no = $row1['trans_id'];
                            $ph_user_number = $row1['user_no'];

                            $_my_dur = $row1['duration'];
    
                            $excess = 0;
                            $contributed_amount = 0;
                            $alloc_amt = 0;
                            $assoc_ph_amount = $row1['excess'];
                            $check = $assoc_ph_amount;
                            $assoc_amount = $assoc_ph_amount;
    
                            if($assoc_amount>$gh_amount){
                                $excess = $assoc_amount-$gh_amount;
                                $contributed_amount = $gh_amount;
                            }else{
                                $contributed_amount = $assoc_amount;
                                $excess = 0;
                            }
    
                            //$contributed_amount += $contributed_amount;
                            $gh_amount -= $contributed_amount;
    
                            date_default_timezone_set("Africa/Johannesburg");
                            $f_now = date("Y-m-d H:i:s");
    
                            $stmt3 = $this->_conn->prepare("update deposits
                                                  set status = 'Assigned',
                                                        excess = '$excess',
                                                   timestamp = '$f_now'
                                            where  trans_id  = '$ph_ref_no'
                                              and status in ('Created','Assigned')");
    
                            if ($stmt3->execute()){
    
                                $stmt4 = $this->_conn->prepare("insert
                                    into assoc
                                    values ($gh_user_number,$ph_user_number,$contributed_amount,$assoc_ref_no,$ph_ref_no,$gh_ref_no,$original_ph,'$now_date','Assigned','','','$now_date')");
                                if ($stmt4->execute()){

                                    if(!$_order=='Homeless'){
    
                                    $stmt5 = $this->_conn->prepare("update withdrawals
                                                                 set status  =	'Assigned',
                                                                   timestamp =	'$f_now'
                                                               where trans_id = '$gh_ref_no'");
                                    }else{
                                        $stmt5 = $this->_conn->prepare("update withdrawals
                                                                 set status  =	'Re-All',
                                                                    total = total - '$assoc_ph_amount',
                                                                   timestamp =	'$f_now'
                                                               where trans_id = '$gh_ref_no'");
                                        
                                    }
    
                                    if ($stmt5->execute()){
    
                                        $field = 'success';
    
                                        $stmt6 = $this->_conn->prepare("select *
                                                                    from members
                                                                   where user_no = '$ph_user_number'");
    
                                        if ($stmt6->execute()){
    
                                            $_row = $stmt6->fetch(PDO::FETCH_ASSOC);
                                            $_Msg = new Msg();

                                            $f_sendsms_no = $_row['mobile'];
                                            $user = $_row['name'];
                                            
                                            if($_my_dur==999){
                                                $_send = new MySMS($f_sendsms_no,$_Msg->getMsg(142));
                                                $_sms = $_send->sendSMS();
                                            }elseif($_my_dur==550 && $contributed_amount==150){   // Only send one SMS
                                                $_send = new MySMS($f_sendsms_no,$_Msg->getMsg(143));
                                                $_sms = $_send->sendSMS();
                                            }elseif($_my_dur==550 && $contributed_amount<>150){   // Only send one SMS
                                                $_send = new MySMS($f_sendsms_no,$_Msg->getMsg(143));
                                                //$_sms = $_send->sendSMS();
                                            }else{
                                                $_send = new MySMS($f_sendsms_no,$_Msg->getMsg(139));
                                                $_sms = $_send->sendSMS();
                                            }
                                            
                                        }
    
                                        $stmt7 = $this->_conn->prepare("select *
                                                                          from members
                                                                         where user_no = '$gh_user_number'");
    
                                        if ($stmt7->execute()){
    
                                            $_row = $stmt7->fetch(PDO::FETCH_ASSOC);
    
                                            $f_sendsms_no = $_row['mobile'];
                                            $user = $_row['name'];
    
                                            $_send = new MySMS($f_sendsms_no,$_Msg->getMsg(140));
                                            //$_sms = $_send->sendSMS();
                                        }
    
                                    }
                                }else{
                                    $field = 'Could not insert allocation';
                                }
    
                                $assoc_ref_no += 1;
    
                            }else{
                                $field = 'Status was not updated to Assigned';
                            }  
                        $assoc_amount = 0;
                    }
                }
            }
    
            return $field;
        }


        public function confirmPayment($assoc_ref_no,$f_old_user_number){

            date_default_timezone_set("Africa/Johannesburg");
            $f_now = date("Y-m-d H:i:s");
            $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
            $now_date = date("Y-m-d H:i:s");

            $_approve = 'N';
    
            if (1==1){
                 $alloc_result = "select * from assoc where assoc_ref_no = '$assoc_ref_no'";
                 $result = mysqli_query($db,$alloc_result);
                 $row_assoc = mysqli_fetch_array($result,MYSQLI_ASSOC);
    
                 $count = mysqli_num_rows($result);
                 $ph_user_number=$row_assoc['ph_user_number'];
                 $gh_user_number=$row_assoc['gh_user_number'];
                 //$user = $_SESSION['login_user'];
    
              if ($count==1) {
                 $status = $row_assoc['status'];
    
                 $gh_ref_no = $row_assoc['gh_ref_no'];
                 $ph_ref_no = $row_assoc['ph_ref_no'];
                 $paid_amount = $row_assoc['amount'];
    
                 if ($status=='Assigned'){
                        $sql0 = "select * from withdrawals where user_no = $gh_user_number and trans_id = $gh_ref_no";
                     $result0 = mysqli_query($db,$sql0);
                     $row0 = mysqli_fetch_array($result0,MYSQLI_ASSOC);
    
                     $req_amount = $row0['due'];
    
                     $sql1 = "Update assoc set status = 'Paid', assoc_timestamp = '$now_date' where assoc_ref_no=$assoc_ref_no and gh_user_number=$f_old_user_number and ph_user_number=$ph_user_number";
    
                     if (mysqli_query($db,$sql1)){
    
                        //CHANGED
                        $refer = '';
                        $sy = "select * from deposits where user_no = $ph_user_number and trans_id = $ph_ref_no";
                        $s_res = mysqli_query($db,$sy);
                        $row_s_res = mysqli_fetch_array($s_res,MYSQLI_ASSOC);
                        $ph_amount = $row_s_res['amount'];
                        $exc_amount = $row_s_res['excess'];
    
                        //Added code
                        $sql_new = "select sum(amount) as sum_complete from assoc where ph_ref_no = $ph_ref_no and status = 'Paid'";
                        $s_res_new = mysqli_query($db,$sql_new);
                        $row_s_res_new = mysqli_fetch_array($s_res_new,MYSQLI_ASSOC);
    
                        $_complete_ph = $row_s_res_new['sum_complete'];
    
                        //Also changed the conditions inside the if statement below:
                        if($_complete_ph==$ph_amount and $exc_amount==0){
                            $_approve = 'YES';
                            //CHANGED ENDS HERE
                            $sql2 = "Update deposits set status = 'Paid', timestamp='$f_now', excess=0 where status = 'Assigned' and user_no = $ph_user_number and trans_id= $ph_ref_no";

                            $_appr ="Update members set approved = 'Y', timestamp='$f_now'  where user_no = '$ph_user_number'"; 
                            $_q_appr = mysqli_query($db,$_appr);
                        }
                        else{
                            $sql2 = "Update deposits set status = 'Assigned', timestamp='$f_now' where user_no = $ph_user_number and trans_id = $ph_ref_no";
                        }
    
                        //Check if user whose payment is being confirmed has a referral
                        $ssqqll = "SELECT * FROM members db1 WHERE user_no = $ph_user_number and exists (select 1 from members db2 where username = db1.sponsor)";
                        $rrsstt = mysqli_query($db,$ssqqll);
                        $rrooww = mysqli_fetch_array($rrsstt,MYSQLI_ASSOC);
                        $ct = mysqli_num_rows($rrsstt);
    
                        //if referral exists
                        if ($ct>0){
                            $refer =  $rrooww['sponsor'];
                        }
    
                        $s = "select amount from deposits where user_no = $ph_user_number and trans_id = $ph_ref_no";
    
                        $r = mysqli_query($db,$s);
                        $rw = mysqli_fetch_array($r,MYSQLI_ASSOC);
    
                        //Calc bonus due to the referral
    
                        $five_percent_bonus = $rw['amount'];
                        $five_percent_bonus = $five_percent_bonus*(0.1);
    
                        if ($ct>0){
                            $refer =  $rrooww['sponsor'];
                        }
    
                        //Convert sponsor into user number
                        $s_convert  = "select user_no from members where username = '$refer'";
                        $r_convert  = mysqli_query($db,$s_convert);
                        $rw_convert = mysqli_fetch_array($r_convert,MYSQLI_ASSOC);
    
                        $refer = $rw_convert['user_no'];
    
                        $s = "select amount from deposits where user_no = $ph_user_number and trans_id = $ph_ref_no";
    
                        $r = mysqli_query($db,$s);
                        $rw = mysqli_fetch_array($r,MYSQLI_ASSOC);
    
                        //Calc bonus due to the referral
                        //Calc bonus due to the referral
                        $five_percent_bonus1 = $rw['amount'];
                        $five_percent_bonus = $rw['amount'];
                        $five_percent_bonus = $five_percent_bonus*(0.1);
                        $_text = 'zero';
                        if ($ct>0 and $exc_amount==0 and $five_percent_bonus1 >= 1000){
                            $_text = 'three';
                            //Check if there's still allocation on status allocated before inserting bonus
                            $sql123  = "select distinct(status) from assoc where ph_ref_no = $ph_ref_no";
                            $rw_s = mysqli_query($db,$sql123);
                            $countss = mysqli_num_rows($rw_s);
    
                            //Make sure referral only gets 1 bonus
                            $sql1234  = "select count(*) as ccs from bonus where user_no = $refer and ph_user_no = $ph_user_number";
                            $rw_s1234 = mysqli_query($db,$sql1234);
                            $rw_s1234_row = mysqli_fetch_array($rw_s1234,MYSQLI_ASSOC);
                            $countss1234 = $rw_s1234_row['ccs'];
    
                            if ($countss==1 and $countss1234==0){
                              $_text = 'four';
                              $sql_count = "select max(trans_id) as ref_no from deposits";
                                       $result_check_count= mysqli_query($db,$sql_count);
                                       $result_count = mysqli_fetch_array($result_check_count,MYSQLI_ASSOC);
                                       $counts = $result_count['ref_no']+1;
    
                                $_bonus_avail = "select *
                                                   from deposits
                                                  where user_no = $refer
                                                    and status = 'Bonus-Pending'";
    
                                $_query_avail = mysqli_query($db,$_bonus_avail);
    
                                if(mysqli_num_rows($_query_avail)==1){
                                    $_text = 'five';
                                    $_row = mysqli_fetch_array($_query_avail,MYSQLI_ASSOC);
                                    $_c_trans_id = $_row['trans_id'];
                                    $sql_bonus = "update deposits
                                                     set amount  = amount + $five_percent_bonus,
                                                      timestamp  = '$now_date'
                                                  where trans_id = '$_c_trans_id'";
                                }else{
                                    $_text = 'six';
                                    $sql_bonus = "insert into
                                                   deposits
                                            values ($refer,$counts,'Bonus','$now_date',$five_percent_bonus,0,0,'N','Bonus-Pending','Rands','$now_date')";
    
                                }
    
                                if (mysqli_query($db,$sql_bonus)){

                                    $_text = 'seven';
    
                                    if($five_percent_bonus1 >= 1000){
                                        $_text = 'eight';
                                        $sql_bonus_1 = "insert into
                                                        bonus
                                                        values ($refer,$ph_user_number,$ph_ref_no,$five_percent_bonus1,$five_percent_bonus)";
    
                                        if (mysqli_query($db,$sql_bonus_1)){
                                            $_text = 'nine';
                                            $field='Could not insert bonus'.mysqli_error($db);
                                        };
                                    }
                                }else{
                                    echo mysqli_error($db);
                                }
                            }
                        }
    
                        if (mysqli_query($db,$sql2)){
    
                            $amount_due = $req_amount-$paid_amount;
    
                            if ($amount_due==0) {
                               $sql4 = "Update withdrawals
                                            set status = 'Complete',
                                                      due = '$amount_due',
                                             timestamp = '$now_date'
                                           where user_no  = $gh_user_number
                                             and trans_id = $gh_ref_no";
    
                                if (mysqli_query($db,$sql4)){
                                    $field='Thank you for confirming payment';
                                }
                                else{
                                    $field='Could not update the Withdrawal amount due and status';
                                }
                            }
                            else{
                                //status = 'Assigned',
                                $sql4 = "Update withdrawals
                                            set due = $amount_due,
                                             timestamp = '$now_date'
                                          where user_no = $gh_user_number
                                            and trans_id = $gh_ref_no";
    
                                if (mysqli_query($db,$sql4)){
                                    $field='Thank you for confirming payment';
                                }
                                else{
                                    $field='Could not update the GH amount due';
                                }
    
                            }
                       }else{
                           $field='Could not update the PH status to PAID';
    
                       }
    
                    }else{
                     $field='Could not update allocation status to complete';
                    }
    
            }else{
                $field = 'Payment Confirmed Already';
            }
             }else{
              $field='Association Ref Number not found';
             }
    
             }else{
            echo 'Please provide the correct Associate Ref Number<h4>';
             }
    
             return $field;
    
        }

        function rejectPayment($ref_no,$assoc_r_n){
            date_default_timezone_set("Africa/Johannesburg");
            $f_now = date("Y-m-d H:i:s");
            $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
            $now_date = date("Y-m-d H:i:s");
    
            $sql = "select * from deposits where trans_id = $ref_no";
            $sql_results = mysqli_query($db,$sql);
            $row = mysqli_fetch_array($sql_results, MYSQLI_ASSOC);
            $count = mysqli_num_rows($sql_results);
            $counting = 0;
            $status1 = $row['status'];
            $user_n = $row['user_no'];
            $_dur = $row['duration'];
    
            if ($status1=='Paid' or $status1=='Complete' or $status1=='Bonus-Pending' or $status1=='Bonus-Paid'){
                $counting = -1;
            }else{
                    if($count>=0){
                        $sql_upd = "update deposits set status = 'Rejected', timestamp = '$now_date' where user_no = '$user_n' and status in ('Created','Assigned')";
                        $sql_upd2 = "update members set active = 'N', approved = 'N', timestamp = '$now_date' where user_no = $user_n";
                        //$sql_updd = "delete from requests where user_no = $user_n";
    
                        if($_dur<>'999'){
                            if(mysqli_query($db,$sql_upd) and mysqli_query($db,$sql_upd2))
                            {
                                $counting += 1;
                            }
                        }
    
                    //NEW ENDS HERE
                    //
                    $sql1 = "select * from assoc where ph_ref_no = $ref_no and assoc_ref_no = $assoc_r_n  and status='Assigned'";
                    $sql_results1 = mysqli_query($db,$sql1);
                    $count1 = mysqli_num_rows($sql_results1);
    
                    $count_break=0;
    
                    while ($count_break==0 and $row1 = mysqli_fetch_array($sql_results1, MYSQLI_ASSOC)) {
                        $counts=0;
                        $now_date = date("Y-m-d H:i:s");
    
                        $gh_user_no = $row1['gh_user_number'];
                        $ph_user_no = $row1['ph_user_number'];
                        $gh_ref = $row1['gh_ref_no'];
                        $assoc_ref = $row1['assoc_ref_no'];
                        $assoc_orig_ref = $row1['original_ph'];
                        $assoc_amt = $row1['amount'];
    
                        /*$sql2 = "delete from requests where ref_no = $gh_ref and request_type = 'GH'";
                        if (mysqli_query($db,$sql2)){
                            $counting += 1;
                        }*/
    
                        $sql3 = "update assoc set status = 'Rejected', assoc_timestamp = '$now_date' where assoc_ref_no = $assoc_ref and status='Assigned'";
                        if (mysqli_query($db,$sql3)){
                            $counting += 1;
                        }
    
                        /*$sql3 = "update requests set status='COMPLETE' where ref_no=$assoc_orig_ref";
                        if (mysqli_query($db,$sql3)){
                            $counting += 1;
                        }*/
    
                        $sql22 ="insert
                                   into withdrawals
                                 values ('$gh_user_no',
                                         'null',
                                         '$assoc_orig_ref',
                                         '$f_now',
                                         '$assoc_amt)',
                                         '$assoc_amt',
                                         '$assoc_amt',
                                         '0',
                                         'Re-All',
                                         'Rands',
                                         '$f_now')";
                          if (mysqli_query($db,$sql22)){
                            $counting += 1;
                        }else{
                            echo mysqli_error($db);
                        }
                        $count_break += 1;
                    }
                  }
            }
    
    
                return $counting;
        }

        public function makeKeeper(){

            date_default_timezone_set("Africa/Johannesburg");
            $f_now = date("Y-m-d H:i:s");
            $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
            $now_date = date("Y-m-d H:i:s");
    
            $_c = 0;
            $_track_message = '';

            $s_sql = "select *
                        from deposits d
                       where status = 'Paid'
                         and amount >= '1000'
                         and keeper = 'Y'
                         and duration not in (0,999)
                         and not exists(select 1
                                         from withdrawals w
                                        where d.trans_id = w.deposit_trans_id
                                            and d.user_no = w.user_no)
                         order by trans_id asc limit 5";

            $_query = mysqli_query($db,$s_sql);

            if(mysqli_num_rows($_query)>0){

                $_pledge = new Pledges();

                while(($_row=mysqli_fetch_array($_query,MYSQLI_ASSOC)) && $_c<5){

                    $_user_no = $_row['user_no'];

                    if(1==1){  // We used to check if banking details are captured
                        
                        $_amount = $_row['amount']*0.4;
                        $_dep_trans_id = $_row['trans_id'];
                        $_sql = "insert
                                into withdrawals
                                values ('$_user_no',
                                        'null',
                                        '$_dep_trans_id',
                                        '$f_now',
                                        '$_amount',
                                        '$_amount',
                                        '$_amount',
                                        '999',
                                        'Created',
                                        'Rands',
                                        '$f_now')";
                        

                        if( $_pledge->totalCreated()>=$_amount){

                            if(mysqli_query($db,$_sql)){
                            
                                $_new_trans_id = mysqli_insert_id($db);
                            
                                $_s = $this->w_t_p($_user_no,$_amount,$_new_trans_id,$_dep_trans_id);
                            
                                if($_s == 'success'){
                                    $_c = $_c + 1;
                                    $_status = 'Keeper allocation created successfully';
                                    $success = 1;
                                    $_track_message = $_c.' '.$_status;
                                    $_sql_a = "select mobile
                                                        from members
                                                            where user_no = '$_user_no'";
                                
                                    if ($query = mysqli_query($db,$_sql_a)){
                                    
                                        $_row = mysqli_fetch_array($query,MYSQLI_ASSOC);
                                        $f_sendsms_no = $_row['mobile'];
                                        
                                        $_Msg = new Msg();
                                    
                                        $_send = new MySMS($f_sendsms_no,$_Msg->getMsg(141));
                                        $_sms = $_send->sendSMS();
                                    }
                                }else{
                                    $_status = 'Keeper allocation creation error: '.$_s;
                                    $_track_message = $_c.' '.$_status;
                                    $success = 0;
                                }
                            
                            }else{
                                    $_status = 'Could not create keeper withdrawal.';
                                    $_track_message = $_c.' '.$_status;
                                    $success=0;
                            }
                        }else{
                            $_status = 'Insufficient for this Withdrawal';
                            $_track_message = $_c.' '.$_status;
                        }
                    }else{
                        $_status = 'Members have no banking details.';
                    }
                }
            }else{
                $_status = 'No one qualifies to be a keeper.';
            }

            return $_status;
        }

    
    }
