<?php
    class rauthController extends Controller{

        public function index($_id='', $_name=''){
            $this->view('rauth'.DS.'signup',['name' => $_name,'id' => $_id], 'Wallet Booster | Create account');
            $this->_view->render();
        }

        public function up($_id='', $_name=''){
            $this->view('rauth'.DS.'signup',['name' => $_name,'id' => $_id], 'Wallet Booster | Create account');
            $this->_view->render();
        }

        public function populate($_id='', $_name=''){
            if(isset($_POST['iso'])){
                if($_POST['iso'] != ''){
                    $_obj = new regionClass();
                    $_populateCountryCode =$_obj->getCountryCode($_POST['iso']);
                    echo $_populateCountryCode;
                }
            }      
        }

        public function checkIdExists($_id='', $_name=''){
            if(isset($_POST['id'])){
                if($_POST['id'] != ''){
                    $_obj = new memberClass();
                    $_checkIdExistance = $_obj->checkIdExists($_POST['id']);
                    echo $_checkIdExistance;
                }
            }      
        }

        public function checkPassportExists($_id='', $_name=''){
            if(isset($_POST['id'])){
                if($_POST['id'] != ''){
                    $_obj = new memberClass();
                    $_checkIdExistance = $_obj->checkPassportExists($_POST['id']);
                    echo $_checkIdExistance;
                }
            }      
        }

        public function checkEmailExists($_id='', $_name=''){
            if(isset($_POST['id'])){
                if($_POST['id'] != ''){
                    $_obj = new memberClass();
                    $_checkIdExistance = $_obj->checkEmailExists($_POST['id']);
                    echo $_checkIdExistance;
                }
            }      
        }
        
        public function checkUsernameExists($_id='', $_name=''){
            if(isset($_POST['id'])){
                if($_POST['id'] != ''){
                    $_obj = new memberClass();
                    $_checkIdExistance = $_obj->checkUsernameExists($_POST['id']);
                    echo $_checkIdExistance;
                }
            }      
        }

        public function checkActiveUsernameExists($_id='', $_name=''){
            if(isset($_POST['id'])){
                if($_POST['id'] != ''){
                    $_obj = new memberClass();
                    $_tree = new treeClass();

                    $_rrsposnor = $_obj->findByUserName($_POST['id']);           
                    $_rrsponsor = $_rrsposnor['member_no'];

                    
                    $_checkIdExistance = $_obj->checkActiveUsernameExists($_POST['id']);

                    if(!($_checkIdExistance=='Sponsor id not found')){
                        if ($_tree->hasActiveDownliners($_rrsponsor)){
                            echo $_checkIdExistance;
                        }else{
                            echo 'Full';
                        } 
                    }else{
                        echo $_checkIdExistance;
                    }
                                       
                }
            }      
        }
        
        public function signUp($_id='', $_name=''){

            $_mem = new memberClass();

            $_mem_array[] = '';
            $_firstname = $_POST['firstname'];
            $_lastname  = $_POST['lastname'];
            $_email     = $_POST['email'];           
            $_mobile    = $_POST['mobile'];
            $_username  = $_POST['username'];
            $_password  = $_POST['password'];
            $_accholder = $_POST['accholder'];
            $_bank      = $_POST['bank'];
            $_accnumber = $_POST['accnumber'];
            $_branchcode = $_POST['branchcode'];
            $_sponsor   = $_POST['sponsor'];

            $_region   = $_POST['country'];
            $_regions 	= new regionClass();
            $_country = $_regions->getCountry($_region);

            $_mem_array['firstname']  = $_firstname;
            $_mem_array['lastname']   = $_lastname;
            $_mem_array['email']      = $_email;
            $_mem_array['username']   = $_username;
            $_mem_array['mobile']     = $_mobile;

            $_found = 0;

            if(is_null($_sponsor) || $_sponsor == ''){
                $_mem_array['sponsor']  = $_mem->getDefaultSponsor();
                $_found = 1;
            }
            else
                $_mem_array['sponsor']  = $_sponsor;

            $_mem_array['password']   = $_password;
            $_mem_array['country']    = $_country;
            $_mem_array['accholder']  = $_accholder;
            $_mem_array['bank']       = $_bank;
            $_mem_array['branchcode'] = $_branchcode;
            $_mem_array['accnumber']  = $_accnumber;

            $_obj = new memberClass();

            if($_found==1 && $_mem_array['sponsor']=='MICTOPACCOUNT'){
                echo 'No sponsor found for you';
            }else{
                $_signup = $_obj->addMember($_mem_array);
                echo $_signup;
            }
    
        } 

        public function notFound($_id='', $_name=''){
            $this->view('hm'.DS.'404',['name' => $_name,'id'   => $_id],'Page not found');
            $this->_view->render();
        }
    }
?>