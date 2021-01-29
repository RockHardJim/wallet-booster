<?php

class authController extends Controller{

    protected $_check_cookies = 'NO';

    public function index($_id='', $_name=''){

        //$_model = new loginModel('','');

        if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
            $this->_check_cookies = 'YES';
            $this->authenticate();
            exit();
        }elseif($this->isLoggedIn()){
            $this->redirect('mystatsdir/index/');
            exit();
		}

        $this->view('auth'.DS.'in',[
            'msg'  => '',
            'id'   => ''
        ],'Sign In');

        $this->_view->render();

    }

    public function authenticate(){

        //require_once(CORE.'recaptchalib.php');

        $_email         = '';
        $_password      = '';
        $_remember_me   = '';

        $_remember_me = 'NO';

        if(isset($_POST['login'])){

            $secretKey = "6LefuEAaAAAAAETBHhZ73SNYyYsq2haKOTNKuGhU";
            $ip = $_SERVER['REMOTE_ADDR'];

                if($this->_check_cookies == 'YES'){

                    $_email     = $_COOKIE['email'];
                    $_password  = $_COOKIE['password']; 

                }elseif(isset($_POST['email']) && isset($_POST['password'])){

                    $_email     = trim($_POST['email']);
                    $_password  = trim($_POST['password']);
                    $_remember_me = isset($_POST['remember']) ? 'YES' : 'NO';

                }else{
                    $this->redirect('auth/index');
                    exit();
                }  



                $_model = new loginModel($_email,$_password,$_remember_me);

                $_login = $_model->authenticate();
                $_a = new allocationsClass();
                $_t = new treeClass();
                $_i = new incentivesClass();

                if($_model->authenticate()[0]=='success' && ($_email<>'administrator' || $_email<>'admember' )){
                    $_a->deleteAllPayments();
                    $_t->updateStages();
                    $_i->updateIncentives();
                    $this->redirect('mystatsdir/index/');
                    exit();
                }elseif($_model->authenticate()[0]=='success' && ($_email=='administrator' || $_email=='admember')){
                    $_a->deleteAllPayments();
                    $_t->updateStages();
                    $_i->updateIncentives();
                    $this->redirect('mystatsdir/admin/');
                    exit();
                }else{
                    $this->view('auth'.DS.'in',[
                        'msg' => $_login[0],
                        'typ' => $_login[1],
                        'eml' => $_email 
                    ],'LOGIN');
                
                    $this->_view->render();
                }
        }
        
    }

    public function fp(){

        $_member = array();
        $_msg_1 = '';
        $_typ = '';
        $_eml = '';
        $_title = '';
        $_mbl = '';

        if(isset($_POST['username']) && isset($_POST['mobile'])){

            $_msg = new Msg();

            $_members = new Members();
            $_messages = new Msg();

            $_eml = trim($_POST['email']);
            $_usr = trim($_POST['username']);
            $_mbl = trim($_POST['mobile']);
            
            $_member = $_members->findByUsername($_usr);                       
            $_username = isset($_member['username']) ? $_member['username'] : '';
            
            if(!empty($_username)){  
                $_mobile = $_member['mobile'];               
                if($_members->compareMobile($_mbl,$_mobile)){
                    $_sms = new MySMS($_mobile,'Greetings '.$_member['firstname'].'! '.$_msg->getMsg(119).' '.$_member['password']);							                                                                                                
                    //$_send = $_sms->sendSMS(); 
                    $_msg_1 = 'Request successful';
                    $_typ = 'S';
                }else{
                    $_msg_1 = 'Details do not match';
                    $_typ = 'F'; 
                }
            }  else {
                $_msg_1 = 'Account data does not correspond';
                $_typ = 'F'; 
            }  

            $_title = $_eml;
        }

        $this->view('auth'.DS.'fp',[
            'msg'  => $_msg_1,
            'typ'  => $_typ,
            'eml'  => $_eml,
            'mobile' => $_mbl 
        ],'Wallet Booster - Password Reminder');

        $this->_view->render();
    }

    public function logout($_id='', $_name=''){

        $_model = new loginModel('','','');

        $this->view('auth'.DS.'in',[
            'msg' => $_model->unsetSession()[0],
            'typ' => $_model->unsetSession()[1]
        ],'Sign In');

        $this->_view->render();

    }

}