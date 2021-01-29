<?php

    class Banking extends Model{

        protected $_user;

        public function __construct(){       
            parent::__construct();
            $this->_user = array();
        }

        public function findAllBanking(){            
            $_sql  = "select * from banking";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        } 

        public function findByUserNo($_user){           
            $_sql  = "select * from banking where member_no = '$_user'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt;
        } 

        public function bankingSet($_user){           
            $_sql  = "select * from banking where member_no = '$_user'";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			if($_stmt->rowCount()==0){
                return false;
            }else
                return true;
        }
        
    }
