<?php

class registerModel extends Model{

    protected $_member_details = array();
    protected $_msg_array;
    
    public function __construct($_member_details){    
        parent::__construct();
        $this->_member_details = $_member_details;

        date_default_timezone_set("Africa/Johannesburg");
        $this->now = date("Y-m-d H:i:s");
        
        $this->_msg       = new Msg(); 
        $this->_msg_array = array();
    }

    public function save()
    {           
        $_mbl = $this->_member_details['mobile'];

        if(substr($_mbl,0,1)=='0'){
            $_mbl='+27'.substr($_mbl,1);
        }elseif(substr($_mbl,0,1)=='2'){
            $_mbl='+'.substr($_mbl,0);
        }

        $_activation_code = rand(100000,999999);

        $_tree = new treeClass();
        $_mbs = new Members();
        
        $_rrsposnor = $_mbs->findByUserName($this->_member_details['sponsor']);           
        $_rrsponsor = $_rrsposnor['member_no'];

        if ($_tree->hasActiveDownliners($_rrsponsor)){
            $_sql = "insert 
                       into members
                     values (null,
                             '',
                             :name,
                             '',
                             :surname,
                             '',
                             '',
                             '',
                             :email,
                             :mobile,
                             :username,
                             :password,
                             :country,
                             'No',
                             :timestamp)";

            $stmt = $this->_conn->prepare($_sql);

            $_save = $stmt->execute(array(':name'        =>$this->_member_details['name'], 
                                          ':surname'     =>$this->_member_details['surname'],
                                          ':username'    =>$this->_member_details['username'],
                                          ':password'    =>$this->_member_details['password'], 
                                          ':email'       =>$this->_member_details['email'],
                                          ':country'     =>$this->_member_details['country'],
                                          ':mobile'      =>$_mbl, 
                                          ':timestamp'   => NOW ));

            $_insert_id = $this->_conn->lastInsertId();

            $_sql_2 = "insert 
                       into banking
                     values ($_insert_id,
                             :holder,
                             :bank,
                             :accnumber,
                             :timestamp)";

            $stmt_2 = $this->_conn->prepare($_sql_2);

            $_save_2 = $stmt_2->execute(array(':holder'       =>$this->_member_details['holder'], 
                                              ':bank'         =>$this->_member_details['bank'],
                                              ':accnumber'    =>$this->_member_details['accnumber'],
                                              ':timestamp'    => NOW ));

            if($_save && $_save_2){
                
                $_association = new Associations(); 
                $_pl = new Pledges();

                $_rsposnor = $_mbs->findByUserName($this->_member_details['sponsor']);           
                $_sponsor = $_rsposnor['member_no'];

                $_obj_2 = new treeClass();
                $_activate = $_obj_2->placeDownliners($_sponsor,$_insert_id);

                $_obj = new treeClass();
                $_results = $_obj->addMember($_insert_id,$_sponsor);

                /*if($_results=='Tree details inserted'){
                    $_allClass = new allocationsClass();
                    $_results = $_allClass->addAllocation($_insert_id, $_sponsor,'500','Reg Fee');
                }*/

                array_push($this->_msg_array,$this->_msg->getMsg(106),$this->_msg->getTyp(106));
		    	return $this->_msg_array;

            }else{
                array_push($this->_msg_array,$this->_msg->getMsg(107),$this->_msg->getTyp(107));
		    	return $this->_msg_array;
            }
        }else{
            array_push($this->_msg_array,'No slots available under the recruiter','F');
		    return $this->_msg_array;
        }
        
    }

}