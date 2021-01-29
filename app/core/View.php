<?php

class View{

    protected $_view_file;
    protected $_view_data;
    protected $_title;

    public function __construct($_view_file,$_view_data,$_title){
           $this->_view_file = $_view_file; 
           $this->_view_data = $_view_data; 
           $this->_title     = $_title; 
    }

    public function render(){  
        if(file_exists(VIEW.$this->_view_file.'.phtml')){
            if($this->getController()<>'rauth' && $this->getController()<>'auth' && $this->getController()<>'mystatsdir')
                include VIEW.'header.phtml';
            elseif($this->getController()=='mystatsdir')
                include VIEW.'header_1.phtml';
        else
            include VIEW.'header.phtml';
            include VIEW.$this->_view_file.'.phtml';
            include VIEW.'footer.phtml';            
        }
    }



    public function getAction(){
        return (explode(DS,$this->_view_file)[1]);        
    }

    public function getController(){
        return (explode(DS,$this->_view_file)[0]);        
    }

    public function getTitle(){
        return (isset($this->_title) ? $this->_title : 'Welcome to my site');
    }

    public function getMessage(){
        return (isset($this->_view_data['msg']) ? $this->_view_data['msg'] : '');
    }

    public function getId(){
        return (isset($this->_view_data['id']) ? $this->_view_data['id'] : '');
    }

    public function getMessageType(){        
        $_mtype = isset($this->_view_data['typ']) ? $this->_view_data['typ'] : '';

        if($_mtype=='S'){
            return 'alert-success';
        }else{
            return 'alert-danger';
        }
           
    }

    public function getSponsor(){
        return (isset($this->_view_data['id']) ? $this->_view_data['id'] : '');
    }

    public function getFailedName(){
        return (isset($this->_view_data['name']) ? $this->_view_data['name'] : '');
    }

    public function getErrorName(){
        return (isset($this->_view_data['r_name']) ? $this->_view_data['r_name'] : '');
    }

    public function getFailedSurname(){
        return (isset($this->_view_data['surname']) ? $this->_view_data['surname'] : '');
    }

    public function getErrorSurname(){
        return (isset($this->_view_data['r_surname']) ? $this->_view_data['r_surname'] : '');
    }

    public function getFailedUsername(){
        return (isset($this->_view_data['username']) ? $this->_view_data['username'] : '');
    }

    public function getErrorUsername(){
        return (isset($this->_view_data['r_username']) ? $this->_view_data['r_username'] : '');
    }

    public function getFailedPassword(){
        return (isset($this->_view_data['password']) ? $this->_view_data['password'] : '');
    }

    public function getErrorPassword(){
        return (isset($this->_view_data['r_password']) ? $this->_view_data['r_password'] : '');
    }

    public function getFailedPassword_c(){
        return (isset($this->_view_data['password_c']) ? $this->_view_data['password_c'] : '');
    }

    public function getErrorPassword_c(){
        return (isset($this->_view_data['r_password_c']) ? $this->_view_data['r_password_c'] : '');
    }

    public function getFailedEmail(){
        return (isset($this->_view_data['eml']) ? $this->_view_data['eml'] : '');
    } 

    public function getErrorEmail(){
        return (isset($this->_view_data['r_eml']) ? $this->_view_data['r_eml'] : '');
    } 
    
    public function getFailedMobile(){
        return (isset($this->_view_data['mobile']) ? $this->_view_data['mobile'] : '');
    } 

    public function getErrorMobile(){
        return (isset($this->_view_data['r_mobile']) ? $this->_view_data['r_mobile'] : '');
    } 

    public function getFailedSponsor(){
        return (isset($this->_view_data['sponsor']) ? $this->_view_data['sponsor'] : '');
    } 

    public function getErrorSponsor(){
        return (isset($this->_view_data['r_sponsor']) ? $this->_view_data['r_sponsor'] : '');
    } 

    public function getSponsorID(){
        return (isset($this->_view_data['sponsor_id']) ? $this->_view_data['sponsor_id'] : '');       
    }

    public function getErrorAccHolder(){
        return (isset($this->_view_data['r_accHolder']) ? $this->_view_data['r_accHolder'] : '');
    } 

    public function getFailedAccHolder(){
        return (isset($this->_view_data['accHolder']) ? $this->_view_data['accHolder'] : '');       
    }

    public function getErrorAccNumber(){
        return (isset($this->_view_data['r_accNumber']) ? $this->_view_data['r_accNumber'] : '');
    } 

    public function getFailedAccNumber(){
        return (isset($this->_view_data['accNumber']) ? $this->_view_data['accNumber'] : '');       
    }

    public function getFailedBank(){
        return (isset($this->_view_data['bank']) ? $this->_view_data['bank'] : '');       
    }

    public function getErrorBank_2(){
        return (isset($this->_view_data['r_bank']) ? $this->_view_data['r_bank'] : '');
    } 

    public function getErrorAccHolder_2(){
        return (isset($this->_view_data['r_holder']) ? $this->_view_data['r_holder'] : '');
    } 

    public function getFailedAccHolder_2(){
        return (isset($this->_view_data['holder']) ? $this->_view_data['holder'] : '');       
    }

    public function getErrorAccNumber_2(){
        return (isset($this->_view_data['r_accnumber']) ? $this->_view_data['r_accnumber'] : '');
    } 

    public function getFailedAccNumber_2(){
        return (isset($this->_view_data['accnumber']) ? $this->_view_data['accnumber'] : '');       
    }

    public function getErrorCountry_2(){
        return (isset($this->_view_data['r_country']) ? $this->_view_data['r_country'] : '');
    } 

    public function getFailedCountry_2(){
        return (isset($this->_view_data['country']) ? $this->_view_data['country'] : '');       
    }

    public function getFailedBank_2(){
        return (isset($this->_view_data['bank']) ? $this->_view_data['bank'] : '');       
    }

    public function getDownliners(){
        return (isset($this->_view_data['downliners']) ? $this->_view_data['downliners'] : '');       
    }

    public function getPledges(){
        return (isset($this->_view_data['pledges']) ? $this->_view_data['pledges'] : '');       
    }

    public function getOutAllocs(){
        return (isset($this->_view_data['out_allocs']) ? $this->_view_data['out_allocs'] : '');       
    }

    public function getInAllocs(){
        return (isset($this->_view_data['in_allocs']) ? $this->_view_data['in_allocs'] : '');       
    }

    public function getToView(){
        return (isset($this->_view_data['to_view']) ? $this->_view_data['to_view'] : '');       
    }

    public function getSubject(){
        return (isset($this->_view_data['subject']) ? $this->_view_data['subject'] : '');
    } 

    public function getEmailBody(){
        return (isset($this->_view_data['email']) ? $this->_view_data['email'] : '');
    }


}