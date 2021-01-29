<?php

class MySMS
{
	private $_cellphone;
	private $_message;
	private $_sms_object = null;
	
	public function __construct($_cellphone,$_message){
		
		//Initialise member variables
		$this->_cellphone 		= $_cellphone;
		$this->_message 		= $_message;		
	}
    
	public function sendSMS(){
		
		$_sendsms_no 		= $this->_cellphone;
		$_sendsms_message 	= $this->_message;
		
		if (substr($_sendsms_no,0,3)=='268' or substr($_sendsms_no,0,4)=='+268'){
			$_sendsms_no = '+'.$_sendsms_no;
			$_sms_object = new MyMobileAPISwaziland();
		}elseif (substr($_sendsms_no,0,3)=='266' or substr($_sendsms_no,0,4)=='+266'){
			$_sendsms_no = '+'.$_sendsms_no;
			$_sms_object = new MyMobileAPILesotho();
		}elseif (substr($_sendsms_no,0,3)=='264' or substr($_sendsms_no,0,4)=='+264'){
			$_sendsms_no = '+'.$_sendsms_no;
			$_sms_object = new MyMobileAPINamibia();
		}elseif (substr($_sendsms_no,0,3)=='265' or substr($_sendsms_no,0,4)=='+265'){
			$_sendsms_no = '+'.$_sendsms_no;
			$_sms_object = new MyMobileAPIMalawi();
		}elseif (substr($_sendsms_no,0,3)=='267' or substr($_sendsms_no,0,4)=='+267'){
			$_sendsms_no = '+'.$_sendsms_no;
			$_sms_object = new MyMobileAPIBotswana();
		}elseif (substr($_sendsms_no,0,3)=='263' or substr($_sendsms_no,0,4)=='+263'){
			$_sendsms_no = '+'.$_sendsms_no;
			$_sms_object = new MyMobileAPIZimbabwe();
		}elseif (substr($_sendsms_no,0,3)=='234' or substr($_sendsms_no,0,4)=='+234'){
			$_sendsms_no = '+'.$_sendsms_no;
			$_sms_object = new MyMobileAPINigeria();
		}else{
			$_sms_object = new MyMobileAPI();
		}
		
		$_sms_object->sendSms($_sendsms_no,$_sendsms_message ); //Send SMS			
				  	
	}
       
}

?>