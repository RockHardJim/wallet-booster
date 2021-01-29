<?php

class testimonialsClass extends Model
{	

	private $_now;	

	public function __construct(){
		parent::__construct();
		$this->_now = NOW;
	}
    
	public function addTestimonial($_name, $_msg){
		$_msg = $this->_conn->quote($_msg);		
        $stmt = $this->_conn->prepare("insert 
                                         into testimonials 
                                       values (0,'$_name',$_msg,'Approved','$this->_now')");	
		$stmt->execute();
													  
		if($stmt){
			return 'Testimonial Created';		  
		}else{	
			return 'Not created';		
		}	    					  	
    }

    public function displayTestimonial(){		
        $stmt = $this->_conn->prepare("select * from testimonials where status = 'Approved' order by rand()");	
		$stmt->execute();                    

		return $stmt;  

		/*if($_count>0){
			return $stmt;  
		}else{	
			return '';		
		}*/   			  	
	}
	
	public function displayTestimonialToBeApproved(){		
        $stmt = $this->_conn->prepare("select * from testimonials order by trans_id desc limit 30");	
		$stmt->execute();                    

		return $stmt;  

		/*if($_count>0){
			return $stmt;  
		}else{	
			return '';		
		}*/   			  	
    }

    public function updateTestimonial($_id,$_status){		
        $stmt = $this->_conn->prepare("update testimonials set status = '$_status',timestamp = '$this->_now' where trans_id = '$_id'");	
		$stmt->execute();
                
		if($stmt)
			return 'Testimonial Updated';	 
		else	
			return 'Error approving testimonial';		
			   			  	
	}

	public function deleteTestimonials($id){	
        $stmt = $this->_conn->prepare("delete from testimonials where trans_id = '$id' order by rand()");	
        $stmt->execute();          
        return  $stmt;            			  	
	}
	

}