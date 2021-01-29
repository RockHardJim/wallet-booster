<?php

class newsUpdatesClass extends Model
{	

	private $_now;	

	public function __construct(){
		parent::__construct();
		$this->_now = NOW;
	}
    
	public function addNewsupdates($_title, $_news, $_poster){
		$_poster = $this->_conn->quote($_poster);		
        $stmt = $this->_conn->prepare("insert 
                                         into newsUpdates 
                                       values (0,'$_title', '$_news', $_poster,'Approved','$this->_now')");	
		$stmt->execute();
													  
		if($stmt){
			return 'News Updates Created';		  
		}else{	
			return 'Not created';		
		}	    					  	
    }

    public function deleteNewsUpdates($id){	
        $stmt = $this->_conn->prepare("delete from newsUpdates where trans_id = '$id' order by rand()");	
        $stmt->execute();          
        return  $stmt;            			  	
	}

    public function displayNewsUpdates(){		
        $stmt = $this->_conn->prepare("select * from testimonials where status = 'Approved' order by rand()");	
		$stmt->execute();                    

		return $stmt;  

		/*if($_count>0){
			return $stmt;  
		}else{	
			return '';		
		}*/   			  	
	}
	

	public function getLatestNews(){		
        $stmt = $this->_conn->prepare("select max(trans_id) as max from newsUpdates");	
		$stmt->execute();                    

		$_row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $_row['max'];

		/*if($_count>0){
			return $stmt;  
		}else{	
			return '';		
		}*/   			  	
	}

	public function getSelectedNews($id){		
        $stmt = $this->_conn->prepare("select * from newsUpdates where trans_id = '$id'");	
		$stmt->execute();                    

		//$_row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $stmt;   			  	
	}

	public function displayNewsUpdatesToBeApproved(){		
        $stmt = $this->_conn->prepare("select * from newsUpdates order by trans_id desc limit 10");	
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
	

}