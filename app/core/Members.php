<?php

    class Members extends Model{

        protected $_user;
        
        public function __construct(){       
            parent::__construct();
            $this->_user = array();
            $this->_now = date("Y-m-d H:i:s");
        }

        public function findAllMembers(){            
            $_sql  = "select * from members";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        } 
		
		public function findAllMembers_2(){            
            $_sql  = "select * from members";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt;
        }

        public function findByEmail($_email){           
            $_sql  = "select * from members where email = '$_email'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        } 

        public function findByUserNo($_user_no){           
            $_sql  = "select * from members where member_no = '$_user_no'";
            $_stmt = $this->_conn->prepare($_sql);                                                 
            $_stmt->execute();
			return $_stmt;
        } 

        public function findByUserName($_username){            
            $_sql  = "select * from members where username = '$_username'";
            $_stmt = $this->_conn->prepare($_sql);
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        } 
        
        public function findByUserNameApproved($_username){            
            $_sql  = "select * from members where username = '$_username' and active='Yes'";
            $_stmt = $this->_conn->prepare($_sql);
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
		} 

        public function findByMobile($_mobile){            
            if(substr($_mobile,0,1)=='0'){
                $_mobile='+27'.substr($_mobile,1);
            }elseif(substr($_mobile,0,1)=='2'){
                $_mobile='+'.substr($_mobile,0);
            }
            $_sql  = "select * from members where mobile = '$_mobile'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function findBySponsor($_sponsor){            
            $_sql  = "select * from members where sponsor = '$_sponsor'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            return $_stmt;
        } 

        public function findUpliner1($_user){            
            $_sql  = "select user_no 
                        from members
                       where username in (select sponsor 
                                           from members 
                                          where member_no = '$_user')";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);

            return $_row['user_no'];
        } 

        public function findUpliner2($_user){            
            $_sql  = "select member_no 
                        from members
                       where username in (select sponsor 
                                           from members 
                                          where member_no = '$_user')";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);

            return $this->findUpliner1($_row['member_no']);
        }

        public function findUpliner3($_user){            
            $_sql  = "select member_no 
                        from members
                       where username in (select sponsor 
                                           from members 
                                          where member_no = '$_user')";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);

            return $this->findUpliner2($_row['member_no']);
        }
        
        public function compareMobile($_mobile_1, $_mobile_2){            
            if(substr($_mobile_1,0,1)=='0' or substr($_mobile_1,0,2)=='27' or substr($_mobile_1,0,3)=='+27'){
                if(substr($_mobile_1,-9)==substr($_mobile_2,-9)){
                    return true;
                }
                else{
                    return false;
                }
            }elseif(substr($_mobile_1,0,1)=='2'){
                $_mobile_1 = '+'.$_mobile_1;
                if($_mobile_1==$_mobile_2){
                    return true;
                }
                else{
                    return false;
                }
            }else{
                if($_mobile_1==$_mobile_2){
                    return true;
                }
                else{
                    return false;
                }
            }
        } 

        /*STATIC FUNCTIONS*/
        public function isApproved($_user){
            $_sql  = "select * from members where member_no = '$_user'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            
            if($_row['approved']=='Y')
                return true;
            else
                return false;
        }

        public function block($_user){
            $_sql  = "update members set active = 'N', approved = 'N', timestamp = '$this->_now' where user_no = '$_user'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
        }

        public function unblock($_user){
            $_sql  = "update members set active = 'Y', approved = 'Y', timestamp = '$this->_now' where user_no = '$_user'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
        }

        public function activate($_user){
            $_sql  = "update members set active = 'Y', approved = 'Y', timestamp = '$this->_now' where user_no = '$_user'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            if($_stmt->execute()){
                $_sql  = "delete from deposits where user_no = '$_user'";
                $_stmt = $this->_conn->prepare($_sql);
                
                if($_stmt->execute()){
                    $_sql  = "delete from assoc where ph_user_number = '$_user'";
                    $_stmt = $this->_conn->prepare($_sql);  

                    $_stmt->execute();
                }
            }
        }

        public function deactivate($_user){
            $_sql  = "update members set active = 'Y', approved = 'Y', timestamp = '$this->_now' where user_no = '$_user'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            if($_stmt->execute()){
                $_sql  = "delete from deposits where user_no = '$_user'";
                $_stmt = $this->_conn->prepare($_sql);
                
                if($_stmt->execute()){
                    $_sql  = "delete from assoc where ph_user_number = '$_user'";
                    $_stmt = $this->_conn->prepare($_sql);  

                    $_stmt->execute();
                }
            }
        }

        public function countAll(){            
            $_sql  = "select count(*) as count from members";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        } 

        public function countApproved(){            
            $_sql  = "select count(*) as count from members'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        } 

        public function countDirectDownliners($_sponsor){            
            $_sql  = "select count(*) as count from members where sponsor = '$_sponsor'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        } 

        public function countPending(){            
            $_sql  = "select count(*) as count from members where approved = 'N' and active='Y'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        }
        
        public function countBlocked(){            
            $_sql  = "select count(*) as count from members where active = 'N'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            return $_row['count'];
        } 

        public function getFororos(){            
            $_sql  = "select * from members where active = 'N' order by rand() limit 3 ";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
            return $_stmt;
        } 
        
    }
