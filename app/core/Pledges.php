<?php

    class Pledges extends Model{

        protected $_pledge;

        public function __construct(){       
            parent::__construct();
            $this->_pledge = array();
            $this->_msg = new Msg();
            $this->_msg_array = array();
        }

        public function findAllPledges(){            
            $_sql  = "select * from deposits order by trans_id desc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt;
        } 

        public function findByTransId($_transId){           
            $_sql  = "select * from deposits where trans_id = '$_transId' order by trans_id desc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        } 

        public function findLastTransID($_user){           
            $_sql  = "select max(trans_id) as max_trans_id from deposits where user_no = '$_user' and duration <> 0";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);            
            return $_row['max_trans_id'];
        } 

        public function findByUserNo($_user_no){           
            $_sql  = "select * from deposits where user_no = '$_user_no' order by trans_id desc";
            $_stmt = $this->_conn->prepare($_sql);                                                 
            $_stmt->execute();
			return $_stmt;
        } 

        public function findHomeless($_user_no){           
            $_sql  = "select name, surname, bank, w.* from withdrawals w, members m, banking b where m.user_no = w.user_no and m.user_no = b.user_no and w.user_no <> '$_user_no' and w.status in ('Re-All') and total>0 order by timestamp asc";
            $_stmt = $this->_conn->prepare($_sql);                                                 
            $_stmt->execute();
			return $_stmt;
        } 

        public function findFirstPledge($_user_no){           
            $_sql  = "select * from deposits where user_no = '$_user_no' order by trans_id asc limit 1";
            $_stmt = $this->_conn->prepare($_sql);                                                 
            $_stmt->execute();
			return $_stmt;
        } 

        public function findFirstPledgeAbove1000($_user_no){           
            $_sql  = "select * from deposits where user_no = '$_user_no' and amount >= 1000 and duration not in(0,550,999) order by trans_id asc limit 1";
            $_stmt = $this->_conn->prepare($_sql);                                                 
            $_stmt->execute();
			return $_stmt;
        } 

        public function sumMyPledges($_user_no){            
            $_sql  = "select sum(amount) as count from deposits where user_no = '$_user_no'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
			return $_row['count'];
        } 

        public function sumMyPaidPledges($_user_no){            
            $_sql  = "select sum(amount) as count from deposits where user_no = '$_user_no' and status='Paid'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        }
        
        public function sumMyNewPledges($_user_no){            
            $_sql  = "select sum(amount) as count from deposits where user_no = '$_user_no' and status='Created'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
			return $_row['count'];
        } 

        public function sumMyAssignedledges($_user_no){            
            $_sql  = "select sum(amount) as count from deposits where user_no = '$_user_no' and status='Assigned'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
			return $_row['count'];
        } 

        public function countActivePledges($_user_no){            
            $_sql  = "select sum(amount) as count from deposits where user_no = '$_user_no' and status in ('Created','Assigned')";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        } 

        public function readyToAllocate(){

            $_sql  = "select coalesce(sum(excess),0) as count 
                       from deposits 
                      where status = 'Created'
                        and datediff(CURRENT_DATE,trans_date) >=3
                        and duration not in (0,999,550)";

            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        }

        public function isKeeper($_trand_id){            
            $_sql  = "select keeper from deposits where trans_id = '$_trand_id'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            
            $_keeper = $_row['keeper'];

            if($_keeper=='Y')
                return true;
            else
                return false;            
        }

        public function isRecruiterPledge($_trand_id){            
            $_sql  = "select description from deposits where trans_id = '$_trand_id'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            
            $_keeper = $_row['description'];

            if($_keeper=='Recruiter Pledge')
                return true;
            else
                return false;            
        }

        public function isRDeposit($_trand_id){            
            $_sql  = "select * from deposits where trans_id = '$_trand_id'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            
            $_dur = $_row['duration'];

            if($_dur=='550')
                return true;
            else
                return false;            
        }

        public function isPaid($_trand_id){            
            $_sql  = "select status from deposits where trans_id = '$_trand_id'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            
            $_paid = $_row['status'];

            if($_paid=='Paid')
                return true;
            else
                return false;            
        }

        public function isCreated($_trand_id){            
            $_sql  = "select status from deposits where trans_id = '$_trand_id'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            
            $_created = $_row['status'];

            if($_created=='Created')
                return true;
            else
                return false;            
        }

        public function isAssigned($_trand_id){            
            $_sql  = "select status from deposits where trans_id = '$_trand_id'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            
            $_assigned = $_row['status'];

            if($_assigned=='Assigned')
                return true;
            else
                return false;            
        }

        public function isWithdrawn($_trand_id){            
            $_sql  = "select status from deposits where trans_id = '$_trand_id'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            
            $_withdrawn = $_row['status'];

            if($_withdrawn=='Withdrawn')
                return true;
            else
                return false;            
        }

        public function countAll(){            
            $_sql  = "select count(*) as count from deposits where duration not in (0,999,550)";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        } 

        public function totalAll(){            
            $_sql  = "select COALESCE(sum(amount),0) as count from deposits where duration not in (0,999)";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        }
        
        public function countCreated(){            
            $_sql  = "select count(*) as count from deposits where status = 'Created' and duration not in (0,999,550)";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        } 

        public function totalCreated(){            
            $_sql  = "select COALESCE(sum(excess),0) as count from deposits where status in ('Created','Assigned') and duration not in (0,999,550)";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
            
        }

        public function countAssigned(){            
            $_sql  = "select count(*) as count from deposits where status = 'Assigned' and duration not in (0,999,550)";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        } 

        public function totalAssigned(){            
            $_sql  = "select COALESCE(sum(amount)-sum(excess),0) as count from deposits where status = 'Assigned' and duration not in (0,999,550)";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        }

        public function countPaid(){            
            $_sql  = "select count(*) as count from deposits where status = 'Paid' and duration not in (0,999,550)";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        } 

        public function totalPaid(){            
            $_sql  = "select COALESCE(sum(amount),0) as count from deposits where status = 'Paid' and duration not in (0,999,550)";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        }

        public function totalBonuses(){            
            $_sql  = "select COALESCE(sum(amount),0) as count from deposits where status = 'Bonus-Pending'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        }

        public function countWithdrawn(){            
            $_sql  = "select count(*) as count from deposits where status = 'Withdrawn' and duration not in (0,999,550)";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        } 

        public function totalWithdrawn(){            
            $_sql  = "select COALESCE(sum(amount),0) as count from deposits where status = 'Withdrawn' and duration not in (0,999,550)";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        }

        public function countAdmin(){            
            $_sql  = "select count(*) as count from deposits where status = 'Withdrawn' and duration=0";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        } 

        public function totalAdmin(){            
            $_sql  = "select COALESCE(sum(amount),0) as count from deposits where status = 'Withdrawn' and duration=0";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        }

        public function readyToBeAKeeper(){
            $_sql  = "select count(*) as count
                        from deposits d
                       where status = 'Paid'
                         and amount >= '1000'
                         and keeper = 'Y'
                         and duration not in (0,999)
                         and not exists(select 1
                                         from withdrawals w
                                        where d.trans_id = w.deposit_trans_id
                                            and d.user_no = w.user_no)";

            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        }


        public function totalReadyToBeAKeeper(){
            $_sql  = "select COALESCE(sum(amount),0) as count
                        from deposits d
                       where status = 'Paid'
                         and amount >= '1000'
                         and keeper = 'Y'
                         and duration not in (0,999)
                         and not exists(select 1
                                         from withdrawals w
                                        where d.trans_id = w.deposit_trans_id
                                            and d.user_no = w.user_no)";

            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return number_format(($_row['count']*0.4),2); 
        }

        public function AllReadyToWithdraw(){
            $_counter = 0;            
            $_sql  = "select * from deposits where status = 'Paid' and duration not in (0,999)";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            while($_row = $_stmt->fetch(PDO::FETCH_ASSOC)){
                if($this->getDaysRemaining($_row['trans_date'],$_row['duration'],$_row['status'])==0){
                    $_counter+=1;
                }
            }
            return $_counter;
        } 

        public function totalReadyToWithdraw(){ 
            $_x = new Withdrawals();           
            $_counter = 0.00;            
            $_sql  = "select * from deposits where status = 'Paid' and duration = 5";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            while($_row = $_stmt->fetch(PDO::FETCH_ASSOC)){
                if($this->getDaysRemaining($_row['trans_date'],$_row['duration'],$_row['status'])==0){
                    $_counter = $_counter + $_x->getGhAmount($_row['amount'],$_row['duration']);
                }
            }
            return number_format($_counter,2);
        }

        public function AllAdminReadyToWithdraw(){
            $_counter = 0;            
            $_sql  = "select * from deposits where status = 'Paid' and duration = 0";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            while($_row = $_stmt->fetch(PDO::FETCH_ASSOC)){
                if($this->getDaysRemaining($_row['
                
                
                
                '],$_row['duration'],$_row['status'])==0){
                    $_counter+=1;
                }
            }
            return $_counter;
        } 

        public function totalAdminReadyToWithdraw(){ 
            $_x = new Withdrawals();           
            $_counter = 0.00;            
            $_sql  = "select * from deposits where status = 'Paid' and duration = 0";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            while($_row = $_stmt->fetch(PDO::FETCH_ASSOC)){
                if($this->getDaysRemaining($_row['timestamp'],$_row['duration'],$_row['status'])==0){
                    $_counter+=$_x->getGhAmount($_row['amount'],$_row['duration']);
                }
            }
            return number_format($_counter,2);
        }

        public function totalBonusesReadyToWithdraw(){            
            $_sql  = "select COALESCE(sum(amount),0) as count from deposits where status = 'Bonus-Pending' and amount>=500";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        }

        public function getDaysRemaining($_timestamp,$_duration,$_status){
            //$_mature_date = date('Y-m-d H:i:s', strtotime($_timestamp. "+ ".$_duration." days"));
            $_mature_date = date('Y-m-d', strtotime($_timestamp. "+ ".$_duration." days"));
            $_days_r=0;
            $_remaining =strtotime($_mature_date)-strtotime(date('Y-m-d'));
        
        
             if ($_duration<>0 and ($_status=='Paid' or $_status=='Bonus')){
                 $_days_r = $_remaining/(60*60*24);
             }elseif ($_duration<>0 and ($_status=='Created' or $_status=='Assigned')){
                 //$_days_r = $_duration;
				 $_days_r = $_remaining/(60*60*24);
             }elseif ($_duration<>0 and $_status=='Withdrawn'){
                 $_days_r = 0;
             }
        
             if ($_days_r<0 or $_duration==0){
                 return 0;
             }else{
                 return $_days_r;
             }
        }

        function getCurrentGhAmount($_amount,$_timestamp,$_duration,$_status){
            $_per = 0;
            $_amount_due = 0;
            if ($_duration==5) {
               $_per = 100;
            }elseif ($_duration==20) {
               $_per = 75;
            }elseif ($_duration==30) {
               $_per = 100;
            }else{
               $_per = 0;
            }
    
            $_days_elapsed = $_duration - $this->getDaysRemaining($_timestamp,$_duration,$_status);
            if ($_duration<>0){
               $_amount_due = $_amount + ($_per/100)*($_amount*$_days_elapsed/$_duration);
            }else{
                $_amount_due = $_amount;
            }
            return $_amount_due;
        }

        public function createPledge($_amount, $_duration)
        {   
		
			$_keeper = 'N';
			
            $_sql = "insert 
                           into deposits
                         values (:user_no,
                                  null,
                                 'Member Pledge',
                                 :date,
                                 :amount,
                                 :amount,
                                 :duration,
                                 :keeper,
                                 'Created',
                                 'Rands',
                                 :timestamp)";
            
            $stmt = $this->_conn->prepare($_sql);
                                                      
            $_save = $stmt->execute(array(':amount'      =>$_amount, 
                                          ':keeper'      =>$_keeper,
										  ':duration'	 =>$_duration,
                                          ':date'        => NOW,
                                          ':user_no'     => ME,
                                          ':timestamp'   => NOW ));                                
    
            if($_save){
                array_push($this->_msg_array,$this->_msg->getMsg(129),$this->_msg->getTyp(129));
                
                return $this->_msg_array;
                
            }else{
                array_push($this->_msg_array,$this->_msg->getMsg(130).' :PDO ERR - '.implode(":",$this->pdo->errorInfo()),$this->_msg->getTyp(130));
                return $this->_msg_array;
            }       
        }

        public function createRecruiterPledge($_user, $_amount, $_duration)
        {   
		
			$_keeper = 'N';
			
            $_sql = "insert 
                           into deposits
                         values (:user_no,
                                  null,
                                 'Recruiter Pledge',
                                 :date,
                                 :amount,
                                 :amount,
                                 :duration,
                                 :keeper,
                                 'Created',
                                 'Rands',
                                 :timestamp)";
            
            $stmt = $this->_conn->prepare($_sql);
                                                      
            $_save = $stmt->execute(array(':amount'      =>$_amount, 
                                          ':keeper'      =>$_keeper,
										  ':duration'	 =>$_duration,
                                          ':date'        => NOW,
                                          ':user_no'     =>$_user,
                                          ':timestamp'   => NOW ));                                
    
            if($_save){
                array_push($this->_msg_array,$this->_msg->getMsg(129),$this->_msg->getTyp(129));
                
                return $this->_msg_array;
                
            }else{
                array_push($this->_msg_array,$this->_msg->getMsg(130).' :PDO ERR - '.implode(":",$this->pdo->errorInfo()),$this->_msg->getTyp(130));
                return $this->_msg_array;
            }       
        }

        public function createAdminPledge($_user, $_amount)
        {   
            $_sql = "insert 
                           into deposits
                         values (:user_no,
                                  null,
                                 'Admin Pledge',
                                 :date,
                                 :amount,
                                 '0',
                                 '0',
                                 'N',
                                 'Paid',
                                 'Rands',
                                 :timestamp)";

            $stmt = $this->_conn->prepare($_sql);

            $_save = $stmt->execute(array(':amount'      => $_amount, 
                                          ':date'        => NOW,
                                          ':user_no'     => $_user,
                                          ':timestamp'   => NOW ));                                

            if($_save){
                array_push($this->_msg_array,$this->_msg->getMsg(129),$this->_msg->getTyp(129));            
                return $this->_msg_array;          
            }else{
                array_push($this->_msg_array,$this->_msg->getMsg(130).' :PDO ERR - '.implode(":",$this->pdo->errorInfo()),$this->_msg->getTyp(130));
    			return $this->_msg_array;
            }       
        }

        public function fundsKept($_user){
            $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
            $counts = 0;
            $sql_count = "select *
                            from deposits
                           where user_no = '$_user'
                             and status = 'Paid'
                             and amount > 0
                             and duration in (15)";
    
             if ($result_check_count=mysqli_query($db,$sql_count)){
    
                while($r = mysqli_fetch_array($result_check_count,MYSQLI_ASSOC)){
    
                    $_dream_trans_id = $r['trans_id'];
                    $_60_per = $r['amount']*0.4;
    
                    $_sql = "SELECT sum(amount) as amt_kept
                             FROM `assoc` a
                            WHERE status in ('Paid')
                              and gh_user_number = '$_user'
                              and original_ph    = '$_dream_trans_id'
                              and exists(select 1
                                           from withdrawals w
                                          where w.deposit_trans_id = a.original_ph
                                            and duration='999')
                            group by original_ph";
    
                    if ($_query = mysqli_query($db,$_sql)){
    
                        $r2 = mysqli_fetch_array($_query,MYSQLI_ASSOC);
                        $_amt = $r2['amt_kept'];
    
                        if ($_60_per == $_amt){
                            $counts = $counts + $_amt;
    
                            $_sqql = "select *
                                        from keepers
                                       where trans_id = '$_dream_trans_id'
                                         and user_no  = '$_user'";
    
                            if ($_query2 = mysqli_query($db,$_sqql)){
                                if (mysqli_num_rows($_query2)>0){
                                    $r3 = mysqli_fetch_array($_query2,MYSQLI_ASSOC);
    
                                    $_k_trans_id = $r3['k_trans_id'];
    
                                    $_sql3 = "SELECT coalesce(sum(amount),0) as amt_kept
                                                FROM `assoc` a
                                               WHERE status = 'Paid'
                                                 and ph_user_number = '$_user'
                                                 and ph_ref_no      = '$_k_trans_id'
                                                   group by ph_ref_no";
    
                                    if ($_query3 = mysqli_query($db,$_sql3)){
                                        $r4 = mysqli_fetch_array($_query3,MYSQLI_ASSOC);
                                        $_amt = $r4['amt_kept'];
    
                                        $counts = $counts - $_amt;
                                    }
                                }
                            }
    
                        }else{
                            $counts = $counts + $_amt;
                        }
                    }
                }
            }
    
            return $counts;
        }
    

        public function totalKept(){
            $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
            $counts = 0;
            $sql_count = "select *
                            from deposits
                           where status = 'Paid'
                             and amount > 0
                             and duration in (15)";
    
            if ($result_check_count= mysqli_query($db,$sql_count)){
    
                while($r = mysqli_fetch_array($result_check_count,MYSQLI_ASSOC)){
    
                    $_dream_trans_id = $r['trans_id'];
                    $_60_per = $r['amount']*0.4;
                    $_user = $r['user_no'];
    
                    $_sql = "SELECT sum(amount) as amt_kept
                             FROM `assoc` a
                            WHERE status in ('Paid')
                              and gh_user_number = '$_user'
                              and original_ph    = '$_dream_trans_id'
                              and exists(select 1
                                           from withdrawals w
                                          where w.deposit_trans_id = a.original_ph
                                            and duration='999')
                            group by original_ph";
    
                    if ($_query = mysqli_query($db,$_sql)){
    
                        $r2 = mysqli_fetch_array($_query,MYSQLI_ASSOC);
                        $_amt = $r2['amt_kept'];
    
                        if ($_60_per == $_amt){
                            $counts = $counts + $_amt;
    
                            $_sqql = "select *
                                        from keepers
                                       where trans_id = '$_dream_trans_id'
                                         and user_no  = '$_user'";
    
                            if ($_query2 = mysqli_query($db,$_sqql)){
                                if (mysqli_num_rows($_query2)>0){
                                    $r3 = mysqli_fetch_array($_query2,MYSQLI_ASSOC);
    
                                    $_k_trans_id = $r3['k_trans_id'];
    
                                    $_sql3 = "SELECT coalesce(sum(amount),0) as amt_kept
                                                FROM `assoc` a
                                               WHERE status = 'Paid'
                                                 and ph_user_number = '$_user'
                                                 and ph_ref_no      = '$_k_trans_id'
                                                   group by ph_ref_no";
    
                                    if ($_query3 = mysqli_query($db,$_sql3)){
                                        $r4 = mysqli_fetch_array($_query3,MYSQLI_ASSOC);
                                        $_amt = $r4['amt_kept'];
    
                                        $counts = $counts - $_amt;
                                    }
                                }
                            }
    
                        }else{
                            $counts = $counts + $_amt;
                        }
                    }
                }
            }
    
            return number_format($counts,2);
        }

        public function loadKeepers(){

            $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
            $now_date = date("Y-m-d H:i:s");
            
            $_c = 0;
            $_track_message = '';
            //echo 'load';
            $s_sql = "select *
                        from deposits d
                         where status = 'Paid'
                             and amount >= '1000'
                             and duration>0
                         and exists(select 1
                                         from withdrawals w
                                        where d.trans_id = w.deposit_trans_id
                                            and d.user_no = w.user_no
                                            and duration = '999')
                         and not exists (select 1 from keepers where trans_id = d.trans_id)
                         order by timestamp asc";

            $_query = mysqli_query($db,$s_sql);

            if(mysqli_num_rows($_query)>0){
                //echo 'load2';
                while(($_row=mysqli_fetch_array($_query,MYSQLI_ASSOC)) && $_c<5){

                    $_user_no = $_row['user_no'];
                    $trans_id = $_row['trans_id'];
                    $at = $_row['amount']*0.4;
                    $xx=0;

                    $_f = "select sum(amount) as excess, timestampdiff(hour, request_date, '$now_date') as v
                             from assoc
                             where gh_user_number = '$_user_no'
                                and original_ph = '$trans_id'
                                and status = 'Paid'
                                and timestampdiff(hour, request_date, '$now_date') >= 48";

                    if($_f_1 = mysqli_query($db, $_f)){
                        $_row_f = mysqli_fetch_array($_f_1,MYSQLI_ASSOC);
                        $xx = $_row_f['excess'];
                        //echo '<br />'.$_user_no.'  '.$now_date.'  '.$_row_f['v']. ' '.$xx.' '.$at;
                    }else{
                        $_status = 'Error getting completed allocations: ';
                        $success = 1;
                    }


                    if($this->getDaysRemaining($_row['timestamp'],$_row['duration'],$_row['status']) > 3 and $xx==$at){

                        echo 'qwer';
                        $_s = $this->load_keepers($_user_no, $trans_id);

                        if($_s == 'success'){
                            $_c = $_c + 1;
                            $_status = 'Keeper(s) loaded successfully';
                            $success = 1;
                            $_track_message = $_c.' '.$_status;

                        }else{
                            $_status = 'Keeper(s) loading error: '.$_s;
                            $_track_message = $_c.' '.$_status;
                            $success = 0;
                        }
                    }else{
                        $_status = 'Either money has only been kept for less than 48 hours or Days remaining less than 3 days or keeper not fully paid'.$this->getDaysRemaining($_row['timestamp'],$_row['duration'],$_row['status']).' - '.$xx.' - '.$at;
                    }
                }
            }else{
                        $_status = 'No keepers found.';
                        $success=0;
            }

            if($_c>0){
                $_status = 	$_track_message;
                $success=1;
            }

            return $_status;


        }   
        
        public function load_keepers($gh_user_number,$original_ph){
            $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
            date_default_timezone_set("Africa/Johannesburg");
            $now_date = date("Y-m-d H:i:s");
            $field = '';
    
            $_new_trans = $this->get_new_request_number();
    
            $_a_sql =	"SELECT sum(amount) as amt_kept
                        FROM `assoc` a
                        WHERE status = 'Paid'
                        and gh_user_number = '$gh_user_number'
                        and original_ph    = '$original_ph'
                        and exists(select 1
                                        from withdrawals w
                                    where w.deposit_trans_id = a.original_ph
                                        and duration='999')
                        group by original_ph";
    
            if($a_sql_results = mysqli_query($db,$_a_sql)){
                $row_a_sql = mysqli_fetch_array($a_sql_results,MYSQLI_ASSOC);
    
                $_amount = $row_a_sql['amt_kept'];
    
                $_new_deposit = "insert
                                into deposits
                                values ('$gh_user_number',
                                        '$_new_trans',
                                        'Load Keeper',
                                        '$now_date',
                                        '$_amount',
                                        '$_amount',
                                        '999',
                                        'N',
                                        'Created',
                                        'Rands',
                                        '$now_date')";
    
                if ($_query = mysqli_query($db,$_new_deposit)){
    
                    $_kep = "insert into keepers values('$gh_user_number','$original_ph','$_new_trans')";
    
                    if($_query2 = mysqli_query($db,$_kep)){
                        $_s = "update assoc set status = 'Rejected', assoc_timestamp = '$now_date' where gh_user_number = '$gh_user_number'
                                and original_ph = '$original_ph' and status <> 'Paid'";
    
                        if ($_query = mysqli_query($db,$_s)){
                            $field = 'success';
                        }else{
                            $field = 'Error cancelling allocations';
                        }
                    }else{
                        $field = 'Error populating keepers table';
                    }
                }else{
                    $field = 'Error inserting a new deposit';
                }
            } else{
                $field = 'Error getting sum amount';
            }
            return $field;
        }

        public function get_new_user_number(){

            $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
            $sql_count = "select max(user_no) as count_user from members";
            $result_check_count= mysqli_query($db,$sql_count);
            $result_count = mysqli_fetch_array($result_check_count,MYSQLI_ASSOC);
    
            $counts = $result_count['count_user'];
            if ($counts==0){
                $counts=1;
            }
            else {
                $counts+=1;
            }
    
            return $counts;
        }

        public function get_new_request_number(){

            $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
            $sql_count = "select max(trans_id) as count_user from deposits";
            $result_check_count= mysqli_query($db,$sql_count);
            $result_count = mysqli_fetch_array($result_check_count,MYSQLI_ASSOC);
    
            $counts = $result_count['count_user'];
            if ($counts==0){
                $counts=1;
            }
            else {
                $counts+=1;
            }
    
            return $counts;
        }
    }
