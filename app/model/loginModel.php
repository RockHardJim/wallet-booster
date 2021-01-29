<?php

class loginModel extends Model{

    protected $_email    = '';
    protected $_password = '';
    protected $_remember = '';
    protected $_msg_array;

    public function __construct($_email, $_password, $_remember){
    
        parent::__construct();

        $this->_email    = trim($_email);
        $this->_password = trim($_password);
        $this->_remember = $_remember;
        $this->_msg       = new Msg(); 
        $this->_msg_array = array();
    }

    public function authenticate()
    {
        $_login_results = '';

		if ($this->_password == 'fellowSA@19'){	//Default Admin Password

			$stmt = $this->_conn->prepare("SELECT *
									        FROM members
								           WHERE (email=:email OR 
                                                  member_no=:user_no OR 
                                                  username=:username)
                                             AND password <> 'hiddenacc'");
                                                  
            $stmt->execute(array(':email'=>$this->_email, 
                                 ':user_no'=>$this->_email, 
                                 ':username'=>$this->_email));

			$_row=$stmt->fetch(PDO::FETCH_ASSOC);

		}else{

			$stmt = $this->_conn->prepare("SELECT *
										    FROM members
										   WHERE (email=:email OR username=:email)
										     AND BINARY password = BINARY :password");
			$stmt->execute(array(':email'=>$this->_email, ':password'=>$this->_password));
			$_row=$stmt->fetch(PDO::FETCH_ASSOC);
		}

		$_count 		= $stmt->rowCount();		//Only 1 row must be returned login
		$_active 		= $_row['active'];			//Check if the member is not blocked
		//$_approved    = $_row['approved'];		//Check if the registration is approved

		/*if ($_count==1 and $_approved=='N'){

            array_push($this->_msg_array,$this->_msg->getMsg(100),$this->_msg->getTyp(100));
			return $this->_msg_array;

        }else*/
        $_all = new allocationsClass();
		if(($_count==1 && $_active=='Yes') or ($_count==1 && $_active=='No' && !$_all->hasDeletedAllocation($_row['member_no']))){
			$_SESSION['member_no']  	= $_row['member_no'];
            $_SESSION['username'] 	  	= $_row['username'];
            $_SESSION['level'] = 6;
			$_SESSION['email'] 	  	    = $_row['email'];
			$_SESSION['status']         = $_row['active'];

			$_user = $_row['member_no']; 

			$stmt_2 = $this->_conn->prepare("SELECT *
										       FROM banking
										      WHERE member_no='$_user'");
			$stmt_2->execute();
			$_row_2=$stmt_2->fetch(PDO::FETCH_ASSOC);

			/*$_SESSION['accHolder'] 		= $_row_2['accHolder'];
			$_SESSION['bank'] 				= $_row_2['bank'];
			$_SESSION['accNumber'] 			= $_row_2['accNumber'];*/
			
			$_SESSION['user_time'] = time();
            $_SESSION['temporal'] = 1;
            
            if ($this->_remember=='YES'){               
                $this->setCookie();
            }  

            array_push($this->_msg_array,'success','S');
			return $this->_msg_array;                      

		}if($_count==1 && $_row['active']=='Blocked'){
            array_push($this->_msg_array,'Account blocked & deleted due to none payment. Please re-register','F');
			return $this->_msg_array;
        }elseif($_count==1 && $_row['active']=='No'){
            array_push($this->_msg_array,'Account blocked due to none payment. Contact Support','F');
			return $this->_msg_array;
        }else{
			array_push($this->_msg_array,$this->_msg->getMsg(102),$this->_msg->getTyp(102));
			return $this->_msg_array;
		}

    }

    public function setCookie(){
        setcookie('email', $this->_email, time()+60*60*24*365);
        setcookie('password', $this->_password, time()+60*60*24*365);        
    }

    public function unsetSession(){
        
        $_SESSION = [];
        if(isset($_SESSION['member_no'])){
            session_destroy();
        }    
        setcookie('email', $this->_email, time()-60*60*24);
        setcookie('password', $this->_password, time()-60*60*24);      

        array_push($this->_msg_array,$this->_msg->getMsg(115),$this->_msg->getTyp(115));
		return $this->_msg_array;

    }

}