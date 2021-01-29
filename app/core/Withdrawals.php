<?php

    class Withdrawals extends Model{

        protected $_pledge;

        public function __construct(){       
            parent::__construct();
            $this->_pledge = array();
        }

        public function withdrawAll(){
            $_counter=0;
            $_sql  = "select * from deposits where status = 'Paid' and duration <> 0 order by timestamp asc limit 10";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            while($_row = $_stmt->fetch(PDO::FETCH_ASSOC)){
                if($this->getDaysRemaining($_row['trans_date'],$_row['duration'],$_row['status'])==0){
                    $_ghAmount = $this->getGhAmount($_row['amount'], $_row['duration']);                    
                    $_cWithdrawal = $this->createWithdrawal($_row['user_no'],$_row['trans_id'],$_ghAmount,$_row['duration'],'Created',$_row['currency']);
                    if($_cWithdrawal){
                        $_counter+=1;
                    }
                }
            }
            return $_counter;
        }
        
        public function withdrawAdmin(){
            $_counter=0;
            $_sql  = "select * from deposits where status = 'Paid' and duration = 0 order by timestamp asc limit 10";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            while($_row = $_stmt->fetch(PDO::FETCH_ASSOC)){
                if($this->getDaysRemaining($_row['timestamp'],$_row['duration'],$_row['status'])==0){
                    $_ghAmount = $this->getGhAmount($_row['amount'], $_row['duration']);                    
                    $_cWithdrawal = $this->createWithdrawal($_row['user_no'],$_row['trans_id'],$_ghAmount,$_row['duration'],'Created',$_row['currency']);
                    if($_cWithdrawal){
                        $_counter+=1;
                    }
                }
            }
            return $_counter;
        } 

        public function withdrawAdminFor($_user){
            $_counter=0;
            $_sql  = "select * from deposits where user_no = '$_user' and status = 'Paid' and duration = 0 order by trans_id desc limit 1";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            while($_row = $_stmt->fetch(PDO::FETCH_ASSOC)){
                if($this->getDaysRemaining($_row['timestamp'],$_row['duration'],$_row['status'])==0){
                    $_ghAmount = $this->getGhAmount($_row['amount'], $_row['duration']);                    
                    $_cWithdrawal = $this->createWithdrawal($_row['user_no'],$_row['trans_id'],$_ghAmount,$_row['duration'],'Created',$_row['currency']);
                    if($_cWithdrawal){
                        $_counter+=1;
                    }
                }
            }
            return $_counter;
        } 

        public function withdrawBonus(){
            $_counter=0;
            $_sql  = "select * from deposits where status = 'Bonus-Pending' and amount>=500 order by timestamp asc limit 10";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            while($_row = $_stmt->fetch(PDO::FETCH_ASSOC)){
                if($this->getDaysRemaining($_row['timestamp'],$_row['duration'],$_row['status'])==0){
                    $_ghAmount = $this->getGhAmount($_row['amount'], $_row['duration']);                    
                    $_cWithdrawal = $this->createWithdrawal($_row['user_no'],$_row['trans_id'],$_ghAmount,$_row['duration'],'Created',$_row['currency']);
                    if($_cWithdrawal){
                        $_counter+=1;
                    }
                }
            }
            return $_counter;
        } 

        public function createWithdrawal($_user,$_d_trans_id,$_amount,$_duration,$_status,$_currency){
            
            $_t = NOW;
            
            $_sql  = "select sum(amount) as count from assoc where original_ph = '$_d_trans_id' and status = 'Paid'";
            $_stmt = $this->_conn->prepare($_sql);                     
            $_stmt->execute();
            
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            $_amt = $_row['count'];
            
            $_amount = $_amount-$_amt;

            $_sql  = "insert 
                        into withdrawals
                      values ('$_user',null,'$_d_trans_id','$_t','$_amount','$_amount','$_amount','$_duration','$_status','$_currency','$_t')";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            if($_stmt){
                $_sql="update deposits set status = 'Withdrawn', timestamp='$_t' where trans_id = '$_d_trans_id'";
                $_stmt = $this->_conn->prepare($_sql);                                                  
                $_stmt->execute();
            }
			return $_stmt;
        } 

        public function getGhAmount($_amount, $_duration){  
            if($_duration==5){
                $_amount = $_amount + ($_amount*1); 
            }elseif($_duration==20){
                $_amount = $_amount + ($_amount*0.75);
            }elseif($_duration==30){
                $_amount = $_amount + ($_amount*1);
            }else{
                $_amount = $_amount;
            }    
            return $_amount;          
        }

        public function newWithdrawalsDue(){            
            $_counter = 0.00;            
            $_sql  = "select * from withdrawals where status = 'Created'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            while($_row = $_stmt->fetch(PDO::FETCH_ASSOC)){
                $_counter+=1;
            }
            return $_counter;
        }

        public function newWithdrawalsDueFor($_user){            
            $_counter = 0.00;            
            $_sql  = "select * from withdrawals where status = 'Created' and user_no = '$_user'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            while($_row = $_stmt->fetch(PDO::FETCH_ASSOC)){
                $_counter+=1;
            }
            return $_counter;
        }

        public function newHomelessWithdrawalsDueFor($_gh_trans_id){            
            $_counter = 0.00;            
            $_sql  = "select * from withdrawals where status = 'Re-All' and trans_id = '$_gh_trans_id'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            while($_row = $_stmt->fetch(PDO::FETCH_ASSOC)){
                $_counter+=1;
            }
            return $_counter;
        }

        public function newWithdrawalsDueTotal(){            
            $_sql  = "select COALESCE(sum(amount),0) as count from withdrawals where status = 'Created' order by trans_id asc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        }

        public function reAllWithdrawalsDue(){            
            $_counter = 0.00;            
            $_sql  = "select * from withdrawals where status = 'Re-All'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            while($_row = $_stmt->fetch(PDO::FETCH_ASSOC)){
                $_counter+=1;
            }
            return $_counter;
        }

        public function reAllWithdrawalsDueTotal(){            
            $_sql  = "select COALESCE(sum(total),0) as count from withdrawals where status = 'Re-All' order by trans_id asc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        }

        public function getDaysRemaining($_timestamp,$_duration,$_status){
            $_mature_date = date('Y-m-d H:i:s', strtotime($_timestamp. "+ ".$_duration." days"));
            $_days_r=0;
            $_remaining =strtotime($_mature_date)-strtotime(date('Y-m-d H:i:s'));
        
        
             if ($_duration<>0 and ($_status=='Paid' or $_status=='Bonus')){
                 $_days_r = $_remaining/(60*60*24);
             }elseif ($_duration<>0 and ($_status=='Created' or $_status=='Assigned')){
                $_days_r = $_remaining/(60*60*24);
                 //$_days_r = $_duration;
             }elseif ($_duration<>0 and $_status=='Withdrawn'){
                 $_days_r = 0;
             }
        
             if ($_days_r<0 or $_duration==0){
                 return 0;
             }else{
                 return $_days_r;
             }
        }

        public function withdrawFromKeeper(){

            $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
            date_default_timezone_set("Africa/Johannesburg");
            $now_date = date("Y-m-d H:i:s");

            if($this->newWithdrawalsDue()>0){

				$_sql = "select *
							from withdrawals
							where status in('Created','Re-All')
							order by trans_id asc";

				$_query = mysqli_query($db,$_sql);

				$_count = mysqli_num_rows($_query);

				if ($_count>5){
					$_count = 5;
				}

				$i=0;

				while(($_row = mysqli_fetch_array($_query,MYSQLI_ASSOC)) && $i<$_count){


					$_s = $this->w_f_k($_row['user_no'],$_row['total'],$_row['trans_id'],$_row['deposit_trans_id']);

					if($_s == 'success'){
						$i+=1;
						$_status = $i.' allocation(s) created successfully';
						$success = 1;
					}else{
						$_status = $i.' allocation(s) created, Error: '.$_s;
						$success = 0;
						break;
					}
				}

			}else{
					$_status = 'No dreams withdrawn.';
					$success=0;
            }
            
            return $_status;
        }

        function w_f_k($gh_user_number,$gh_amount,$gh_ref_no,$original_ph){
            $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
            date_default_timezone_set("Africa/Johannesburg");
            $now_date = date("Y-m-d H:i:s");
            $field = '';

            $f_sendsms_no = '';
    
            $row_excess = 0;
    
            $sql_all = "select COALESCE(sum(excess),0) as sum_excess
                         from deposits
                        where trans_id > 0
                          and status in ('Assigned','Created')
                          and excess <> 0
                          and duration = 999
                          and user_no <> '$gh_user_number'
                          and user_no > 0
                        order by trans_id asc";
    
            $sql_results = mysqli_query($db,$sql_all);
            $row_sql_results = mysqli_fetch_array($sql_results,MYSQLI_ASSOC);
            $row_excess = $row_sql_results['sum_excess'];
    
            //return $row_excess;
    
            if($row_excess<$gh_amount){
                $field = 'Funds exhausted. -'.$row_excess.'-'.$gh_amount;
            }else{
                $sql1 = "select *
                           from deposits
                          where trans_id > 0
                            and status in ('Assigned','Created')
                            and duration = 999
                            and excess <> 0
                            and user_no <> '$gh_user_number'
                            and user_no > 0
                            order by trans_id asc";
    
                $sql1_results = mysqli_query($db,$sql1);
                $count        = mysqli_num_rows($sql1_results);
    
                $sql_count1 = "select max(assoc_ref_no) as assoc_ref_no from assoc";
                $result_check_count1= mysqli_query($db,$sql_count1);
                $result_count1 = mysqli_fetch_array($result_check_count1,MYSQLI_ASSOC);
                $count11 = $result_count1['assoc_ref_no'];
    
                if ($count11==0){
                    $assoc_ref_no = 1;
                }else{
                    $assoc_ref_no = $count11+1;
                }
    
                if ($count==0){
                    $field = 'No new Offers found. Please try again in 6 hours.';
                }else{
    
                    while ($row1=mysqli_fetch_array($sql1_results,MYSQLI_ASSOC) and ($gh_amount>0)){
    
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
    
                            $sql1 = "update deposits
                                        set status = 'Assigned',
                                            excess = '$excess',
                                            timestamp = '$now_date'
                                    where   trans_id  = '$ph_ref_no'
                                        and status in ('Created','Assigned')";
    
                            if (mysqli_query($db,$sql1)){
    
                                $sql2 = "insert
                                    into assoc
                                    values ($gh_user_number,$ph_user_number,$contributed_amount,$assoc_ref_no,$ph_ref_no,$gh_ref_no,$original_ph,'$now_date','Assigned','','','$now_date')";
                                if (mysqli_query($db,$sql2)){
    
                                    $sql2 = "update withdrawals
                                                set status='Assigned',
                                                timestamp = '$now_date'
                                             where trans_id = '$gh_ref_no'";
    
                                    if (mysqli_query($db,$sql2)){
    
                                        $field = 'success';
    
                                        $_sql_a = "select mobile
                                                     from members
                                                    where user_no = '$ph_user_number'";
    
                                        if ($query = mysqli_query($db,$_sql_a)){
    
                                            $_row = mysqli_fetch_array($query,MYSQLI_ASSOC);
    
                                            $f_sendsms_no = $_row['mobile'];
    
                                            if (substr($f_sendsms_no,0,3)=='268'){
                                                $f_sendsms_no = '+'.$f_sendsms_no;
                                                $sms_object = new MyMobileAPISwaziland();
                                                //echo '<h4 style="color:red;">Swaziland : '.$f_sendsms_no.'</h4>';
                                            }elseif (substr($f_sendsms_no,0,3)=='266'){
                                                $f_sendsms_no = '+'.$f_sendsms_no;
                                                $sms_object = new MyMobileAPILesotho();
                                                //echo '<h4 style="color:red;">Lesotho : '.$f_sendsms_no.'</h4>';
                                            }else{
                                                $sms_object = new MyMobileAPI();
                                                //echo '<h4 style="color:red;">SA '.substr($f_sendsms_no,0,3).': '.$f_sendsms_no.'</h4>';
                                            }

                                            //echo $f_sendsms_no.' -  '.$ph_user_number;
    
                                            $sms_object->sendSms($f_sendsms_no,'Hello Keeper! Your HouseOfKeepers order to pay from kept fund has just been released. Visit the site for more information and pay within 30 hrs'); //Send SMS
                                            //$sms_object->checkcredits(); //Check your credit balance
                                        }
    
                                    }
                                }else{
                                    $field = 'Could not insert allocation';
                                }
    
                                $assoc_ref_no += 1;
    
                            }else{
                                $field = 'Status was not updated to ALLOCATED';
                            }
    
                            $assoc_amount = 0;
                    }
    
                }
            }
    
            return $field;
        }
                
    }
