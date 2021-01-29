<?php

    class Notices extends Model{

        protected $_notices;

        public function __construct(){       
            parent::__construct();
            $this->_notices = array();
        }

        public function loadNotices(){            
            $_sql  = "select * from notices where status = 'ACTIVE' order by notice_id desc";
            $_stmt = $this->_conn->prepare($_sql);                                                  
            $_stmt->execute();
			return $_stmt->fetch(PDO::FETCH_ASSOC);
        } 
        
    }
