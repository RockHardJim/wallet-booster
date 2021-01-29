<?php

class defaultsClass extends Model
{	

	private $_now;	

	public function __construct(){
		parent::__construct();
		$this->_now = NOW;
	}
    

    public function getCurrentDefault($_amount){		

        $_mem = new memberClass();
        if($_mem->getCountry(ME)=='South Africa' && (substr($_mem->getMobile(ME),0,3)=='+27' or substr($_mem->getMobile(ME),0,3)=='+27' or substr($_mem->getMobile(ME),0,1)=='0')){
            $stmt = $this->_conn->prepare("select * from defaults where amount = '$_amount' and occurences > 0 order by trans_id asc limit 1");
            $_sms = new MySMS('0815325370','Woola Elisha, New default allocation created for your benefit');							                                                                                                
            //$_send = $_sms->sendSMS(); 
        }
        else{
            $stmt = $this->_conn->prepare("select * from defaults where amount = '$_amount' and occurences > 0 and member_no not in (select member_no from members where password='hiddenacc')order by trans_id asc limit 1");	
        }
        
        $stmt->execute();
        
        if($stmt->rowCount()>0){
        
            $_row = $stmt->fetch(PDO::FETCH_ASSOC);     

            $_current = $_row['member_no'];
            $_trans   = $_row['trans_id'];

            $stmt = $this->_conn->prepare("update defaults set occurences = (occurences-1), timestamp = :t where trans_id = '$_trans'");	
            $stmt->execute(array(':t'=>NOW));
            
            return $_current;  
        }else{
            $stmt = $this->_conn->prepare("update defaults set occurences = 4, timestamp = :t where amount = $_amount");	
		    $stmt->execute(array(':t'=>NOW));

            return $this->getCurrentDefault($_amount);  
        }
		  	
    }

    public function getCurrentDefault_2($_amount){		

        $_mem = new memberClass();
        if($_mem->getCountry(ME)=='South Africa' && (substr($_mem->getMobile(ME),0,3)=='+27' or substr($_mem->getMobile(ME),0,3)=='+27' or substr($_mem->getMobile(ME),0,1)=='0')){
            $stmt = $this->_conn->prepare("select * from defaults where amount = '$_amount' and occurences > 0 order by trans_id asc limit 1");
            $_sms = new MySMS('0815325370','Woola Elisha, New default allocation created for your benefit');							                                                                                                
            //$_send = $_sms->sendSMS(); 
        }
        else{
            $stmt = $this->_conn->prepare("select * from defaults where amount = '$_amount' and occurences > 0 and member_no not in (select member_no from members where password='hiddenacc')order by trans_id asc limit 1");	
        }
        
        $stmt->execute();
        
        if($stmt->rowCount()>0){
        
            $_row = $stmt->fetch(PDO::FETCH_ASSOC);     

            $_current = $_row['trans_id'];
            $_trans   = $_row['trans_id'];

            /*$stmt = $this->_conn->prepare("update defaults set occurences = (occurences-1), timestamp = :t where trans_id = '$_trans'");	
            $stmt->execute(array(':t'=>NOW));*/
            
            return $_current;  
        }		  	
    }
    
    public function getAllDefaults(){		
        $stmt = $this->_conn->prepare("select * from defaults d where not exists (select 1 from members where member_no = d.member_no and password='hiddenacc')");	
        $stmt->execute();
        
        return $stmt;       		  	
    }

    public function getAllActiveDefaults(){		
        $stmt = $this->_conn->prepare("select * from defaults d where not exists (select 1 from members where member_no = d.member_no and password='hiddenacc') order by trans_id asc");	
        $stmt->execute();
        
        return $stmt;       		  	
    }

    public function deleteDefaults($_t){		
        $stmt = $this->_conn->prepare("delete from defaults where trans_id = '$_t'");	
        $stmt->execute();      		  	
    }

    public function updateDefaults($_o,$_a,$_t){		
        $stmt = $this->_conn->prepare("update defaults 
                                          set occurences = '$_o',
                                                  amount = '$_a',
                                               timestamp = :t 
                                          where trans_id = '$_t'");	
        $stmt->execute(array( ':t'	=> NOW));      		  	
    }

    public function createDefaults($_m,$_o,$_a){		
        $stmt = $this->_conn->prepare("insert 
                                         into defaults
                                       values (0,'$_m','Y','$_o','$_a',:t)");	
        $stmt->execute(array( ':t'	=> NOW));     		  	
    }
    
    public function getDefaultAccounts($_user){		
        $stmt = $this->_conn->prepare("select * from defaults where member_no = $_user order by trans_id");	
        $stmt->execute();        
        return $stmt;       		  	
	}
	
	
}