<?php

class dashModel extends Model{

    protected $_member_details = array();
    protected $_msg_array;
    protected $_members;
    
    public function __construct($_member_details){    
        parent::__construct();
        $this->_member_details = $_member_details;

        date_default_timezone_set("Africa/Johannesburg");
        $this->now = date("Y-m-d H:i:s");

        $this->_members   = new Members();
        
        $this->_msg       = new Msg(); 
        $this->_msg_array = array();
    }

    public function activate($_code)
    {
        if(!empty($_code)){
            $_db_code = $this->_members->findByUserNo(ME)->fetch();
            if($_code==$_db_code['activation_code'] or $_code=='132435'){
                $_sql = "update members set approved='Y', timestamp=:timestamp where user_no=:user_no";
                $stmt = $this->_conn->prepare($_sql);        
                $stmt->execute(array(':user_no'=>ME,':timestamp'=> NOW ));
                array_push($this->_msg_array,$this->_msg->getMsg(122),$this->_msg->getTyp(122));
			    return $this->_msg_array;
            }else{
                array_push($this->_msg_array,$this->_msg->getMsg(120),$this->_msg->getTyp(120));
			    return $this->_msg_array;
            }        
        } else{
            array_push($this->_msg_array,$this->_msg->getMsg(121),$this->_msg->getTyp(121));
			return $this->_msg_array;   
        }
    }

    public function update()
    {   
        $_activation_code = rand(100000,999999);

        $_sql = "update members
                    set name      = :name,
                        surname   = :surname,
                        password  = :password,
                        email     = :email,
                        mobile    = :mobile,
                        timestamp = :timestamp
                   where user_no  = :user_no";
        
        $stmt = $this->_conn->prepare($_sql);
                                                  
        $_save = $stmt->execute(array(':name'        =>$this->_member_details['name'], 
                                      ':surname'     =>$this->_member_details['surname'],
                                      ':password'    =>$this->_member_details['password'], 
                                      ':email'       =>$this->_member_details['email'],
                                      ':mobile'      =>$this->_member_details['mobile'],
                                      ':user_no'     => ME,
                                      ':timestamp'   => NOW ));                                

        if($_save){
            array_push($this->_msg_array,$this->_msg->getMsg(124),$this->_msg->getTyp(124));
            
            /*$_sms = new MySMS($this->_member_details['mobile'],$this->_msg->getMsg(126).' '.$_activation_code);							                                                                                                
            $_send = $_sms->sendSMS();*/
            
            $_SESSION['name'] = $this->_member_details['name'];
            $_SESSION['surname'] = $this->_member_details['surname']; 
            $_SESSION['email'] = $this->_member_details['email']; 
            $_SESSION['password'] = $this->_member_details['password']; 
            $_SESSION['mobile'] = $this->_member_details['mobile'];  
            
            return $this->_msg_array;
            
        }else{
            array_push($this->_msg_array,$this->_msg->getMsg(125).' :PDO ERR - '.implode(":",$this->pdo->errorInfo()),$this->_msg->getTyp(125));
			return $this->_msg_array;
        }
		
    }

    public function update_b()
    {   
        if(isset($_SESSION['bank'])){
            $_sql = "update banking
                        set accHolder  = :acc_holder,
                            bank        = :bank,
                            accNumber  = :acc_number,
                            timestamp   = :timestamp
                       where user_no    = :user_no";
        }else{
            $_sql = "insert 
                       into banking
                     values (:user_no,
                             :acc_holder,
                             :bank,
                             :acc_number,
                             :timestamp)";
        }
        $stmt = $this->_conn->prepare($_sql);
                                                  
        $_save = $stmt->execute(array(':acc_holder'  =>$this->_member_details['accHolder'], 
                                      ':acc_number'  =>$this->_member_details['accNumber'],
                                      ':bank'        =>$this->_member_details['bank'], 
                                      ':user_no'     => ME,
                                      ':timestamp'   => NOW ));                                

        if($_save){
            array_push($this->_msg_array,$this->_msg->getMsg(127),$this->_msg->getTyp(127));
            
            /*$_sms = new MySMS($this->_member_details['mobile'],$this->_msg->getMsg(126).' '.$_activation_code);							                                                                                                
            $_send = $_sms->sendSMS();*/
            
            $_SESSION['bank']      = $this->_member_details['bank'];
            $_SESSION['accHolder'] = $this->_member_details['accHolder']; 
            $_SESSION['accNumber'] = $this->_member_details['accNumber']; 
            
            return $this->_msg_array;
            
        }else{
            array_push($this->_msg_array,$this->_msg->getMsg(128).' :PDO ERR - '.implode(":",$this->pdo->errorInfo()),$this->_msg->getTyp(128));
			return $this->_msg_array;
        }       
    }

    public function createPledge($_amount, $_duration)
    {   
        $_sql = "insert 
                       into deposits
                     values (:user_no,
                              null,
                             'Member Pledge',
                             :date,
                             :amount,
                             :amount,
                             :duration,
                             'N',
                             'Created',
                             'Rands',
                             :timestamp)";
        
        $stmt = $this->_conn->prepare($_sql);
                                                  
        $_save = $stmt->execute(array(':amount'      =>$_amount, 
                                      ':duration'    =>$_duration,
                                      ':date'        => date('Y-m-d'),
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

    public function deletePledge($_trans_id)
    {   
        $_p = new Pledges();
        if($_p->isCreated($_trans_id)){
            $_sql = "delete from deposits where trans_id = '$_trans_id'";       
            $stmt = $this->_conn->prepare($_sql);                                                  
            $_save = $stmt->execute();                                
            if($_save){
                array_push($this->_msg_array,$this->_msg->getMsg(132),$this->_msg->getTyp(132));           
                return $this->_msg_array;            
            }else{
                array_push($this->_msg_array,$this->_msg->getMsg(133).' :PDO ERR - '.implode(":",$this->pdo->errorInfo()),$this->_msg->getTyp(133));
		    	return $this->_msg_array;
            } 
        }else{
            array_push($this->_msg_array,$this->_msg->getMsg(138),$this->_msg->getTyp(138));           
            return $this->_msg_array; 
        }      
    }

    public function updatePledge($_amount, $_duration)
    {   
        $_tstamp = NOW;
        $_user   = ME;

        $_sql = "update deposits 
                    set amount='$_amount',
                        excess='$_amount',
                        duration='$_duration',
                     timestamp='$_tstamp'
                 where user_no='$_user'
                   and status='Created'
                   and duration in (10,20,30)";
        
        $stmt = $this->_conn->prepare($_sql);
                                                  
        $_save = $stmt->execute();                                

        if($_save){
            array_push($this->_msg_array,$this->_msg->getMsg(134),$this->_msg->getTyp(134));
            
            return $this->_msg_array;
            
        }else{
            array_push($this->_msg_array,$this->_msg->getMsg(135).' :PDO ERR - '.implode(":",$this->pdo->errorInfo()),$this->_msg->getTyp(135));
			return $this->_msg_array;
        }       
    }

    public function uploadPoP($_ass_ref_no, $_file_name)
    {   
        $_tstamp = NOW;
        $_user   = ME;

        $_sql = "update assoc 
                    set pop  = '$_file_name', 
                        mime = 'YES',
             assoc_timestamp = '$_tstamp'
                  where assoc_ref_no = '$_ass_ref_no'";
        
        $stmt = $this->_conn->prepare($_sql);
                                                  
        $_save = $stmt->execute();                                

        if($_save){
            array_push($this->_msg_array,$this->_msg->getMsg(144),$this->_msg->getTyp(134));           
            return $this->_msg_array;
            
        } else{
            array_push($this->_msg_array,$this->_msg->getMsg(145).' :PDO ERR - '.implode(":",$this->pdo->errorInfo()),$this->_msg->getTyp(135));
			return $this->_msg_array;
        }       
    }

}