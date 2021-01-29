<?php
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class mystatsdirController extends Controller{

        public function index($_id='', $_name=''){

            if(isset($_SESSION['member_no']) && ($_SESSION['member_no']<>'1000') && ($_SESSION['member_no']<>'82647')){
                $this->view('mystatsdir'.DS.'index',['name' => $_name,'id' => $_id], 'Dashboard - Wallet Booster');
                $this->_view->render();
            }elseif(isset($_SESSION['member_no']) && ($_SESSION['member_no']=='1000')){
                $this->view('mystatsdir'.DS.'admin',['name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                $this->_view->render();
            }elseif(isset($_SESSION['member_no']) && ($_SESSION['member_no']=='82647')){
                $this->view('mystatsdir'.DS.'adminmembers',['name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function withdrawals($_id='', $_name=''){

            if(isset($_SESSION['member_no'])){
                $this->view('mystatsdir'.DS.'withdrawals',['name' => $_name,'id' => $_id], '');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function Manipulate($_id='', $_name=''){

            $_m = new memberClass();
            $_a = new allocationsClass();
            $_t = new treeClass();
            $_i = new incentivesClass();
            $_b = new bankClass();

            if(isset($_SESSION['member_no'])){
                if(isset($_POST['Manipulate']) && isset($_POST['manipulateValue'])){
                    $_m->updateMembership($_POST['manipulateValue']);
                }
                $this->view('mystatsdir'.DS.'admin',
                [ 'msg'  => 'Membership Manipualated', 'typ'  => 'S','name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function Override($_id='', $_name=''){

            $_m = new memberClass();
            $_a = new allocationsClass();
            $_t = new treeClass();
            $_i = new incentivesClass();
            $_b = new bankClass();
            $_o = new overridesClass();

            if(isset($_SESSION['member_no'])){
                if(isset($_POST['Override']) && isset($_POST['overrideValue'])){
                    if($_m->usernameExists(trim($_POST['overrideValue']))){
                        $_row = $_m->findByUsername($_POST['overrideValue']);
                        $_member = $_row['member_no'];
                        if(!$_o->isOverriden($_member))
                            $_o->createOverrides($_member);
                        $this->view('mystatsdir'.DS.'admin',
                        [ 'msg'  => 'Override Created', 'typ'  => 'S','name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                        $this->_view->render();
                    }else{
                        $this->view('mystatsdir'.DS.'admin',
                        [ 'msg'  => 'Valid username Required', 'typ'  => 'F','name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                        $this->_view->render();
                    }
                }else{
                    $this->view('mystatsdir'.DS.'admin',
                    [ 'msg'  => 'Valid username Required', 'typ'  => 'F','name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                    $this->_view->render();
                }
                
            }else{
                header('Location: /default/index/');
            }
        }

        public function addDefaultSponsor($_id='', $_name=''){

            $_m = new memberClass();
            $_a = new allocationsClass();
            $_t = new treeClass();
            $_i = new incentivesClass();
            $_b = new bankClass();
            $_o = new overridesClass();

            if(isset($_SESSION['member_no'])){
                if(isset($_POST['createDefaultSponsor']) && isset($_POST['sponsorValue'])){
                    if($_m->usernameExists(trim($_POST['sponsorValue']))){
                        $_row = $_m->findByUsername($_POST['sponsorValue']);
                        $_member = $_row['member_no'];
                        if(!$_m->isDefaultSponsor($_member))
                            $_m->createSponsor($_member);
                        $this->view('mystatsdir'.DS.'admin',
                        [ 'msg'  => 'Sponsor Created', 'typ'  => 'S','name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                        $this->_view->render();
                    }else{
                        $this->view('mystatsdir'.DS.'admin',
                        [ 'msg'  => 'Valid username Required', 'typ'  => 'F','name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                        $this->_view->render();
                    }
                }else{
                    $this->view('mystatsdir'.DS.'admin',
                    [ 'msg'  => 'Valid username required', 'typ'  => 'F','name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                    $this->_view->render();
                }
                
            }else{
                header('Location: /default/index/');
            }
        }

        public function deleteSponsor($_id='', $_name=''){

            $_m = new memberClass();
            $_a = new allocationsClass();
            $_t = new treeClass();
            $_i = new incentivesClass();
            $_b = new bankClass();
            $_o = new overridesClass();

            if(isset($_SESSION['member_no'])){
                if(isset($_POST['deleteSponsor']) && isset($_POST['h_member_no'])){                                        
                    $_m->deleteSponsor($_POST['h_member_no']);
                    $this->view('mystatsdir'.DS.'admin',
                    [ 'msg'  => 'Sponsor Deleted', 'typ'  => 'S','name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                    $this->_view->render();
                }else{
                    $this->view('mystatsdir'.DS.'admin',
                    [ 'msg'  => 'Error encounter, contact support', 'typ'  => 'F','name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                    $this->_view->render();
                }
                
            }else{
                header('Location: /default/index/');
            }
        }

        public function deleteOverride($_id='', $_name=''){

            $_m = new memberClass();
            $_a = new allocationsClass();
            $_t = new treeClass();
            $_i = new incentivesClass();
            $_b = new bankClass();
            $_o = new overridesClass();

            if(isset($_SESSION['member_no'])){
                if(isset($_POST['deleteOverride']) && isset($_POST['hidden_trans_id'])){                                        
                    $_o->deleteOverrides($_POST['hidden_trans_id']);
                    $this->view('mystatsdir'.DS.'admin',
                    [ 'msg'  => 'Override Deleted', 'typ'  => 'S','name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                    $this->_view->render();
                }else{
                    $this->view('mystatsdir'.DS.'admin',
                    [ 'msg'  => 'Error encounter, contact support', 'typ'  => 'F','name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                    $this->_view->render();
                }
                
            }else{
                header('Location: /default/index/');
            }
        }

        public function manageDefaults($_id='', $_name=''){

            $_m = new memberClass();
            $_a = new allocationsClass();
            $_t = new treeClass();
            $_i = new incentivesClass();
            $_b = new bankClass();
            $_d = new defaultsClass();
            $_msg = '';

            if(isset($_SESSION['member_no'])){
                if(isset($_POST['deleteDefault']) && isset($_POST['hidden_trans_id'])){
                    $_d->deleteDefaults($_POST['hidden_trans_id']);
                    $_msg = 'Removed from defaults';
                }elseif(isset($_POST['updateDefault']) && isset($_POST['hidden_trans_id'])){
                    $_d->updateDefaults($_POST['occurence'],$_POST['amount'],$_POST['hidden_trans_id']);
                    $_msg = 'Defaults details changed';
                }elseif(isset($_POST['createDefault'])){
                    $_d->createDefaults($_POST['hidden_member_no'],$_POST['occurence'],$_POST['amount']);
                    $_msg = 'Added to defaults';
                }elseif(isset($_POST['updateUsername'])){
                    $_m->updateUsername($_POST['husername'],$_POST['username']);
                    $_msg = 'Username updated';
                }

                $this->view('mystatsdir'.DS.'admindefaults',
                [ 'msg'  => $_msg, 'typ'  => 'S','name' => $_name,'id' => $_id], 'Defaults - Wallet Booster');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function resetPWD($_id='', $_name=''){

            $_m = new memberClass();
            
            $_msg = '';

            if(isset($_SESSION['member_no'])){
                if(isset($_POST['resetPWD']) && isset($_POST['hidden_member_no'])){
                    $_r = $_m->resetPWD($_POST['hidden_member_no']);
                    $_msg = 'Password reset to '.$_r;
                }
                $this->view('mystatsdir'.DS.'adminmembers',
                [ 'msg'  => $_msg, 'typ'  => 'S','name' => $_name,'id' => $_id], 'Members - Wallet Booster');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function adminwithdrawals($_id='', $_name=''){

            if(isset($_SESSION['member_no']) && $_SESSION['member_no']=='1000'){
                if(isset($_POST['option'])){
                    echo '123';
                    $this->view('mystatsdir'.DS.'adminwithdrawals',['name' => $_name,'id' => $_POST['option']], '');
                    $this->_view->render();
                }else{
                    $this->view('mystatsdir'.DS.'adminwithdrawals',['name' => $_name,'id' => $_id], '');
                    $this->_view->render();
                }
            }else{
                header('Location: /default/index/');
            }
        }

        public function profile($_id='', $_name=''){

            if(isset($_SESSION['member_no'])){
                $this->view('mystatsdir'.DS.'profile',['msg' => '','typ' => 'S'], 'Wallet Booster - Account Settings');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function banking($_id='', $_name=''){

            if(isset($_SESSION['member_no'])){
                $this->view('mystatsdir'.DS.'banking',['name' => $_name,'id' => $_id], '');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function In($_id='', $_name=''){

            if(isset($_SESSION['member_no'])){
                $this->view('mystatsdir'.DS.'In',['name' => $_name,'id' => $_id], 'Wallet Booster - Incoming Payments');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function Out($_id='', $_name=''){

            if(isset($_SESSION['member_no'])){
                $this->view('mystatsdir'.DS.'Out',['name' => $_name,'id' => $_id], 'Wallet Booster - Outgoing Payments');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function tree($_id='', $_name=''){
            if(isset($_SESSION['member_no'])){
                $this->view('mystatsdir'.DS.'tree',['name' => $_name,'id' => $_id], 'Wallet Booster - Tree');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function teamList($_id='', $_name=''){
            if(isset($_SESSION['member_no'])){
                $this->view('mystatsdir'.DS.'list',['name' => $_name,'id' => $_id], 'Wallet Booster - List');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function tree_2($_id='', $_name=''){
            if(isset($_SESSION['member_no'])){
                $this->view('mystatsdir'.DS.'tree_2',['name' => $_name,'id' => $_id], 'Dl Tree - Wallet Booster');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function viewDlTree($_id='', $_name=''){

            if(isset($_SESSION['member_no']) && !(isset($_POST['viewDlTree']))){
                $this->view('mystatsdir'.DS.'tree_2',['name' => $_name,'id' => $_id], 'Dl Tree - Wallet Booster');
                $this->_view->render();
            }elseif(isset($_SESSION['member_no']) && isset($_POST['viewDlTree'])){
                $_SESSION['dl'] = $_POST['hidden_member_no'];
                $this->view('mystatsdir'.DS.'tree_2',['name' => $_name,'id' => $_id], 'Dl Tree - Wallet Booster');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function notifications($_id='', $_name=''){
            if(isset($_SESSION['member_no'])){
                $this->view('mystatsdir'.DS.'notifications',['name' => $_name,'id' => $_id], 'Wallet Booster - Notifications');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function sendBulkEmail($_id='', $_name=''){
            if(isset($_SESSION['member_no'])){
                $this->view('mystatsdir'.DS.'sendBulkEmail',['name' => $_name,'id' => $_id], 'Wallet Booster - sendBulkEmail');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function changeGlobalLevel($_id='', $_name=''){           
            if(isset($_POST['globalLevel'])){
               $_SESSION['level'] = $_POST['globalLevel'];
            }           
        }

        public function admin($_id='', $_name=''){

            
            if(isset($_SESSION['member_no']) and $_SESSION['username']=='administrator'){
                $this->view('mystatsdir'.DS.'admin',['name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                $this->_view->render();
            }elseif(isset($_SESSION['member_no']) and $_SESSION['username']=='admember'){
                $this->view('mystatsdir'.DS.'adminmembers',['name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function admindefaults($_id='', $_name=''){

            if(isset($_SESSION['member_no']) and $_SESSION['username']=='administrator'){
                $this->view('mystatsdir'.DS.'admindefaults',['name' => $_name,'id' => $_id], 'Defaults - Wallet Booster');
                $this->_view->render();
            }elseif(isset($_SESSION['member_no']) and $_SESSION['username']=='admember'){
                $this->view('mystatsdir'.DS.'adminmembers',['name' => $_name,'id' => $_id], 'Admin - Wallet Booster');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function adminmembers($_id='', $_name=''){

            if(isset($_SESSION['member_no']) and ($_SESSION['username']=='administrator' or $_SESSION['username']=='admember')){
                $this->view('mystatsdir'.DS.'adminmembers',['name' => $_name,'id' => $_id], 'Members - Wallet Booster');
                $this->_view->render();
            }else{
                header('Location: /default/index/');
            }
        }

        public function adminpayments($_id='', $_name=''){

            if(isset($_SESSION['member_no']) and $_SESSION['username']=='administrator'){
                $this->view('mystatsdir'.DS.'adminpayments',['name' => $_name,'id' => $_id], 'Payments - Wallet Booster');
                $this->_view->render();
            }elseif(isset($_SESSION['member_no']) and $_SESSION['username']=='admember'){
                header('Location: /mystatsdir/adminmembers/');
            }else{
                header('Location: /default/index/');
            }
        }

        public function adminevents($_id='', $_name=''){

            if(isset($_SESSION['member_no']) and $_SESSION['username']=='administrator'){
                $this->view('mystatsdir'.DS.'adminevents',['name' => $_name,'id' => $_id], 'Events - Wallet Booster');
                $this->_view->render();
            }elseif(isset($_SESSION['member_no']) and $_SESSION['username']=='admember'){
                header('Location: /mystatsdir/adminmembers/');
            }else{
                header('Location: /default/index/');
            }
        }

       

        public function adminnotifications($_id='', $_name=''){

            if(isset($_SESSION['member_no']) and $_SESSION['username']=='administrator'){
                $this->view('mystatsdir'.DS.'adminnotifications',['name' => $_name,'id' => $_id], 'Notifications - Wallet Booster');
                $this->_view->render();
            }elseif(isset($_SESSION['member_no']) and $_SESSION['username']=='admember'){
                header('Location: /mystatsdir/adminmembers/');
            }else{
                header('Location: /default/index/');
            }
        }

        public function admintestimonials($_id='', $_name=''){

            if(isset($_SESSION['member_no']) and $_SESSION['username']=='administrator'){
                $this->view('mystatsdir'.DS.'admintestimonials',['name' => $_name,'id' => $_id], 'Testimonials - Wallet Booster');
                $this->_view->render();
            }elseif(isset($_SESSION['member_no']) and $_SESSION['username']=='admember'){
                header('Location: /mystatsdir/adminmembers/');
            }else{
                header('Location: /default/index/');
            }
        }

        public function updateMemberDetails($_id='', $_name=''){

            if(isset($_SESSION['member_no'])){

                $_mem_array[] = '';

                $_firstname = $_POST['firstname'];
                $_lastname  = $_POST['lastname'];        
                $_mobile    = $_POST['mobile'];
                $_password  = $_POST['password'];

                $_mem_array['firstname']  = $_firstname;
                $_mem_array['lastname']   = $_lastname;
                $_mem_array['mobile']     = $_mobile;
                $_mem_array['password']   = $_password;

                $_obj = new memberClass();
                $_signup = $_obj->updateMember($_mem_array);
                echo $_signup;

            }else{
                header('Location: /default/index/');
            }
        } 

        public function activate($_id='', $_name=''){

            if(isset($_POST['member_no'])){
                $_obj = new memberClass();
                $_obj_2 = new treeClass();
                $_obj_3 = new allocationsClass();
                $_activate = $_obj->activateMember($_POST['member_no']);
                if($_activate=='Member activated'){
                    $_dl_user = $_POST['member_no'];
                    $_activate = $_obj_3->updateAllocation($_dl_user);
                    if($_activate=='Allocations updated'){
                        echo 'New account activated successfully';
                    }else{
                        echo $_activate;
                    }
                }else{
                    echo $_activate;
                }
            }else{
                header('Location: /default/index/');
            }
            
        } 

        public function deactivate($_id='', $_name=''){

            if(isset($_POST['member_no'])){
                $_obj = new memberClass();
                $_obj_2 = new treeClass();
                $_obj_3 = new allocationsClass();
                $_deactivate = $_obj->deactivateMember($_POST['member_no']);
                if($_deactivate=='Member deactivated'){
                    $_dl_user = $_POST['member_no'];
                    $_deactivate = $_obj_3->updateAllocation_2($_dl_user);
                    if($_deactivate=='Allocations updated'){
                        echo 'Account deactivated successfully';
                    }else{
                        echo $_deactivate;
                    }
                }else{
                    echo $_deactivate;
                }
            }else{
                header('Location: /default/index/');
            }
            
        } 

        public function updateStageForAll($_id='', $_name=''){

            $_tree = new treeClass();
            $_results = $_tree->updateStageForAll();

            echo $_results.' rows updated';
            
        } 

        public function placeAllMembers($_id='', $_name=''){

            $_tree = new treeClass();
            $_results = $_tree->placeAllMembers();

            echo $_results.' members placed';
            
        } 

        public function uploads($_id='', $_name=''){
            if(isset($_SESSION['member_no'])){
                
                $_model = new allocationsClass();
                
                
                if (isset($_POST['UPLOAD']) && isset($_FILES['POP'])){
    
                    $_assoc = $_POST['ASSOC'];
                    $_random = rand(1000,10000);
                    //$target_dir = 'C:\Users\Cindy_Cee\Documents\Work\The Adventure\root\uploads\\';
                    $target_dir = UPLOADS;
                    $target_file = $target_dir.'_'.$_random.'_pop_'.basename($_FILES["POP"]["name"]);
                    $temp = $_FILES['POP']['tmp_name'];
    
                    $file_name = '/uploads/_'.$_random.'_pop_'.basename($_FILES["POP"]["name"]);
    
                    if ($_FILES["POP"]["size"] < 2000000) {
                        if (move_uploaded_file($temp,$target_file)){
                            $_upload = $_model->uploadPoP($_assoc,$file_name);
                            $_SESSION['MSG'] = 'Uploaded';
                        }
                        else{
                            $_SESSION['MSG'] = 'Error Uploading';
                        }
                    }
                    else{
                        $_SESSION['MSG'] = 'File too large';
                    }
    
                }
                else{
                    $_SESSION['MSG'] = 'File required';
                }
    
                $this->view('mystatsdir'.DS.'statement',[
                    'msg' => $_SESSION['MSG'],
                    'typ' => ''
                ],'');
                $this->_view->render();         
            }else{   
                    header('Location: /default/index/');
            }
        }

        public function setIncentiveToPaid($_id='', $_name=''){

            if(isset($_POST['trans_id'])){
                $_obj_3 = new allocationsClass();
                $_upd = $_obj_3->setAllocationToPaid($_POST['trans_id']);
                if($_upd=='Allocations updated'){
                    echo 'Payment status updated';
                }else{
                    echo $_upd;
                }
            }else{
                header('Location: /default/index/');
            }
            
        } 

        public function confirmPayment($_id='', $_name=''){

            if(isset($_POST['trans_id'])){
                $_mem = new memberClass();
                $_obj_3 = new allocationsClass();
                $_upd = $_obj_3->confirmPayment($_POST['trans_id']);
                if($_upd=='Allocations updated'){
                    if($_obj_3->getDescription($_POST['trans_id'])=='Reg Fee'){
                        $_payer = $_obj_3->getPayer($_POST['trans_id']);
                        $_active = $_mem->activateMember($_payer);
                        echo 'Upgrade payment approved';
                    }else{ 
                        $_payer = $_obj_3->getPayer($_POST['trans_id']);
                        if(!$_mem->isActive_2($_payer))
                            $_active = $_mem->activateMember($_payer);
                            
                        echo 'Upgrade payment approved';
                    }
                    
                }else{
                    echo $_upd;
                }
            }else{
                header('Location: /default/index/');
            }               
        } 

        public function reversePayment($_id='', $_name=''){

            if(isset($_POST['trans_id'])){
                $_mem = new memberClass();
                $_inc = new incentivesClass();
                $_tree = new treeClass();
                $_obj_3 = new allocationsClass();
                $_upd = $_obj_3->reversePayment($_POST['trans_id']);
                $_receiver = $_obj_3->getReceiver($_POST['trans_id']);
                $_payer = $_obj_3->getPayer($_POST['trans_id']);
                if($_upd=='Payment deleted'){
                    if($_obj_3->getDescription($_POST['trans_id'])=='Reg Fee'){
                        $_active = $_mem->blockMember($_payer);                        
                        if($_active == 'Member deactivated'){
                            $_remove = $_tree->removeFromTree($_payer);
                            if($_remove=='Removed from dl1' || $_remove=='Removed from dl2'){
                                //$_tree->updateStageForAll();
                                echo $_remove;
                            }
                            else
                                echo 'Deleted but could not be removed from your tree';
                        }                                          
                    }elseif($_obj_3->getDescription($_POST['trans_id'])=='Stage 2'){
                        $_rev = $_inc->reverseIncentive($_payer,'1');
                        echo $_rev;
                    }elseif($_obj_3->getDescription($_POST['trans_id'])=='Stage 3'){
                        $_rev = $_inc->reverseIncentive($_payer,'2');
                        echo $_rev;
                    }elseif($_obj_3->getDescription($_POST['trans_id'])=='Stage 4'){
                        $_rev = $_inc->reverseIncentive($_payer,'3');
                        echo $_rev;
                    }
                }else{
                    echo $_upd;
                }
            }else{
                header('Location: /default/index/');
            }        
        }        

        public function unblockME($_id='', $_name=''){

            if(isset($_POST['date']) && isset($_POST['member_no'])){
                $_mem = new memberClass();
                $_all = new allocationsClass();

                $_act = $_mem->activateMember($_POST['member_no']);
                if($_act=='Member activated'){
                    $_restore = $_all->restoreAllocation($_POST['member_no'],$_POST['date']);
                    echo 'Membership has been reinstated';
                }else{
                    echo $_act;
                }
            }else{
                header('Location: /default/index/');
            }        
        }

        public function deletePayment($_id='', $_name=''){

            if(isset($_POST['trans_id']) && isset($_POST['member_no'])){
                $_mem = new memberClass();
                $_tree = new treeClass();
                $_obj_3 = new allocationsClass();
                $_upd = $_obj_3->deletePayment($_POST['trans_id']);
                if($_upd=='Allocations deleted'){
                    if($_obj_3->getDescription($_POST['trans_id'])=='Reg Fee'){
                        $_active = $_mem->blockMember($_POST['member_no']);                        
                        if($_active == 'Member deactivated'){
                            $_remove = $_tree->removeFromTree($_POST['member_no']);
                            if($_remove=='Removed from dl1' || $_remove=='Removed from dl2'){
                                //$_tree->updateStageForAll();
                                echo $_remove;
                            }
                            else
                                echo 'Deleted but could not be removed from your tree';
                        }                                          
                    }else{
                        $_active = $_mem->deactivateMember($_POST['member_no']);                        
                        if($_active == 'Member deactivated'){
                            echo 'Payment Deleted';
                        }
                    }
                }else{
                    echo $_upd;
                }
            }else{
                header('Location: /default/index/');
            }
            
        } 

        public function SendEmails($_id='', $_name=''){

            $_typ = 'S';
            $_msg = '';                
            
            if(isset($_SESSION['member_no'])){                
                if (isset($_POST['sendEmails'])){     

                    $this->_db        = Database::getInstance();
                    $this->_conn      = $this->_db->getConnection();
                    
                    $stmt='';
                    
                    if($_POST['category']=='Active'){
                        $stmt = $this->_conn->prepare("select distinct(email) as email from members where active = :status and email not in (select email from emailOverrides) order by member_no limit 50");
                        $stmt->execute(array(':status'=>'Yes'));
                    }elseif($_POST['category']=='Inactive'){
                        $stmt = $this->_conn->prepare("select distinct(email) as email from members where active = :status and email not in (select email from emailOverrides)limit 50");
                        $stmt->execute(array(':status'=>'No'));
                    }elseif($_POST['category']=='Blocked'){
                        $stmt = $this->_conn->prepare("select distinct(email) as email from members where active = :status and email not in (select email from emailOverrides)limit 50");
                        $stmt->execute(array(':status'=>'Blocked'));
                    }elseif($_POST['category']=='All members'){
                        $stmt = $this->_conn->prepare("select distinct(email) as email from members where email not in (select email from emailOverrides) order by rand() limit 60");
                        $stmt->execute(array(':status'=>'Blocked'));
                    }elseif($_POST['category']=='Blocked'){
                        $stmt = $this->_conn->prepare("select distinct(email) as email from members where username = :username and email not in (select email from emailOverrides)limit 50");
                        $stmt->execute(array(':username'=>'ethabs'));
                    }else{
                        $stmt = $this->_conn->prepare("select distinct(email) as email from members where username = :username and email not in (select email from emailOverrides)limit 50");
                        $stmt->execute(array(':username'=>'ethabs'));
                    }

                    $_counter = 0;

                    $_counter_0 = 0;

                    $_mem = new memberClass();

                    //set_time_limit(60);

                    while($_row = $stmt->fetch(PDO::FETCH_ASSOC)){

                        $_counter_0 += 1;
                        // Load Composer's autoloader
                        require 'vendor/autoload.php';

                        // Instantiation and passing `true` enables exceptions
                        $mail = new PHPMailer(true);
                                                
                        try {
                            //Server settings
                            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
                            $mail->isSMTP();                                            // Send using SMTP

                            /**disabled errors */
                            $mail->SMTPDebug = false;
                            $mail->do_debug = 0;

                            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
                            $mail->SMTPAuth   = true;                                // Enable SMTP authentication
                            $mail->Username   = 'moneyincrew200@gmail.com';          // SMTP username
                            $mail->Password   = 'mic@covid19';                       // SMTP password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                            $mail->Port       = 587;                                 // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                        
                            //Recipients
                            $mail->setFrom('moneyincrew200@gmail.com', 'Wallet Booster');
                            $mail->addAddress($_row['email']);         // Add a recipient
                            $mail->addReplyTo('moneyincrew200@gmail.com', 'Wallet Booster');
                            //$mail->addCC('cc@example.com');
                            //$mail->addBCC('bcc@example.com');
                        
                            // Attachments
                            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                        
                            // Content
                            $mail->isHTML(true);                                  // Set email format to HTML
                            $mail->Subject = $_POST['emailSubject'];
                            $mail->Body    = '<p style="white-space:pre-line;">'.$_POST['emailText'].'</p>';
                            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                        
                            $mail->send();
                            //$_msg = 'Message has been sent';
                            $_typ = 'S';
                            $_counter+=1;

                            $_createOverride = $_mem->createEmailOverride($_row['email']);

                        } catch (Exception $e) {
                            $_msg = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                            $_typ = 'F';
                        }
                    }
                }   

                if($_counter>0){
                    $_msg = $_counter. ' email(s) sent '.$_counter_0;
                    $_typ = 'S';
                }else{
                    //$_msg = $_counter. ' emails sent '.$_counter_0;
                    $_typ = 'F';
                }
                
                $this->view('mystatsdir'.DS.'sendBulkEmail',[
                    'msg' => $_msg,
                    'typ' => $_typ,
                    'subject' => $_POST['emailSubject'],
                    'email' => $_POST['emailText']
                ],'Send Emails');
                $this->_view->render();      
            }else{   
                    header('Location: /default/index/');
            }
        }

        public function upgrade($_id='', $_name=''){

            $_tree = new treeClass();
            $_inc = new incentivesClass();
            $_def = new defaultsClass();
            $_all = new allocationsClass();
            $_m = new memberClass();  
            $_o = new overridesClass();
            

            if(!$_all->hasPendingAllocation(ME)){

                $_upliner_details = '';

                $_allow_upgrade = false;

                if(isset($_POST['trans_id'])){
                    $_mytid = $_POST['trans_id'];
                    $_current_logged_in_member = $_SESSION['member_no'];
                    $_upgrading_user = $_inc->getMember($_mytid );

                    if($_current_logged_in_member==$_upgrading_user)
                        $_allow_upgrade = true;
                }

                /*if(!$_allow_upgrade){
                    header('Location: /auth/logout');
                    exit();
                }*/

                
                if(isset($_POST['trans_id']) && $_allow_upgrade){

                    $_stage = $_inc->getStage($_POST['trans_id']);
                    if($_stage == '1'){
                        
                        $_amount = 400;
                        $_description = 'Stage 2';
                        $_sponsor = $_tree->selectDUpliner($_tree->selectDUpliner(ME));

                        /*if(!$_all->checkIfUplinerHasUpgraded($_sponsor,$_description)){
                            $_upliner_details = 'Your upliner has not upgraded. Are you sure you want to skip your upliner';
                        }*/
                
                        if($_sponsor == 0 or $_sponsor == 1000 or $_sponsor == 1001){
                            $_receiver = $_def->getCurrentDefault($_amount);
                        }elseif($_o->isOverriden($_sponsor)){
                            $_receiver = $_sponsor;
                        }elseif($_all->checkIfUplinerHasUpgraded($_sponsor,$_description) && $_m->isActive($_sponsor)=='Active'){
                            $_receiver = $_sponsor;
                        }else{
                            $_receiver = $_def->getCurrentDefault($_amount);
                        }

                    }elseif($_stage == '2'){

                        $_amount = 800;
                        $_description = 'Stage 3';
                        $_sponsor = $_tree->selectDUpliner($_tree->selectDUpliner($_tree->selectDUpliner(ME)));
                        
                        /*if(!$_all->checkIfUplinerHasUpgraded($_sponsor,$_description)){
                            $_upliner_details = 'Your upliner has not upgraded. Are you sure you want to skip your upliner';
                        }*/

                        if($_sponsor == 0 or $_sponsor == 1000 or $_sponsor == 1001){
                            $_receiver = $_def->getCurrentDefault($_amount);
                        }elseif($_o->isOverriden($_sponsor)){
                            $_receiver = $_sponsor;
                        }elseif($_all->checkIfUplinerHasUpgraded($_sponsor,$_description) && $_m->isActive($_sponsor)=='Active' && !$_all->upgradeRoutingDue($_sponsor,$_amount)){
                            $_receiver = $_sponsor;
                        }else{
                            $_receiver = $_def->getCurrentDefault($_amount);
                        }

                        

                    }elseif($_stage == '3'){
                        
                        $_amount = 3000;
                        $_description = 'Stage 4';
                        $_sponsor = $_tree->selectDUpliner($_tree->selectDUpliner($_tree->selectDUpliner($_tree->selectDUpliner(ME))));
                        
                        /*if(!$_all->checkIfUplinerHasUpgraded($_sponsor,$_description)){
                            $_upliner_details = 'Your upliner has not upgraded. Are you sure you want to skip your upliner';
                        }*/

                        if($_sponsor == 0 or $_sponsor == 1000 or $_sponsor == 4990){
                            $_receiver = $_def->getCurrentDefault($_amount);
                        }elseif($_o->isOverriden($_sponsor)){
                            $_receiver = $_sponsor;
                        }elseif($_all->checkIfUplinerHasUpgraded($_sponsor,$_description) && $_m->isActive($_sponsor)=='Active' && !$_all->upgradeRoutingDue($_sponsor,$_amount)){
                            $_receiver = $_sponsor;
                        }else{
                            $_receiver = $_def->getCurrentDefault($_amount);
                        }
                    }
                
                    $_mem = new memberClass();
                    $_obj_3 = new allocationsClass();
                    $_payeruser = $_SESSION['member_no'];

                    $_claim  = $_obj_3->addAllocation($_payeruser,$_receiver,$_amount,$_description);

                        if($_claim=='Allocations details inserted'){

                            $_claim = $_inc->updateIncentive($_POST['trans_id']);
                            if($_claim=='Incentive Claimed'){
                                echo 'Upgrade to another level processed. Please pay within 12 hours to complete upgrade';
                            }else{
                                echo $_claim;
                            }
                        }else{
                            echo $_claim;
                        }
                }else{
                    echo 'Session expired, please login again';
                    //header('Location: /default/index/');
                }
            }else{
                echo 'Previous Upgrade still pending';
            }
        } 
    
        public function setIncentiveToPending($_id='', $_name=''){

            if(isset($_POST['trans_id'])){
                $_obj_3 = new allocationsClass();
                $_upd = $_obj_3->setAllocationToPending($_POST['trans_id']);
                if($_upd=='Allocations updated'){
                    echo 'Payment status updated';
                }else{
                    echo $_upd;
                }
            }else{
                header('Location: /default/index/');
            }
            
        } 

        public function addTestimonial($_id='', $_name=''){

            if(isset($_SESSION['member_no'])){
                
                if (isset($_POST['UPLOAD']) && isset($_FILES['POP'])){
    
                    $_random = rand(1000,10000);
                    //$target_dir = 'C:\Users\Cindy_Cee\Documents\Work\The Adventure\root\uploads\\';
                    $target_dir = UPLOADS;
                    $target_file = $target_dir.date('d').'_'.$_random.'_pop_'.basename($_FILES["POP"]["name"]);
                    $temp = $_FILES['POP']['tmp_name'];
    
                    $file_name = '/uploads/'.date('d').'_'.$_random.'_pop_'.basename($_FILES["POP"]["name"]);
    
                    if ($_FILES["POP"]["size"] < 2000000) {
                        if (move_uploaded_file($temp,$target_file)){
                            //$_upload = $_model->uploadPoP($_assoc,$file_name);

                            if(1==1){
                                $_test = new testimonialsClass();
                                $_mem  = new memberClass();
                
                                $_name = 'Testimonial';
                                $_add  = $_test->addTestimonial($_name, $file_name);
                                if($_add=='Testimonial Created'){
                                    $_msg = 'Testimony Received! Thank you';
                                }else{
                                    $_msg = $_add;
                                }
                            } 
                        }
                        else{
                            $_msg ='Error Uploading';
                        }
                    }
                    else{
                        $_msg = 'File too large';
                    }
    
                }
                else{
                    $_msg = 'File required';
                }    
                $this->view('mystatsdir'.DS.'admintestimonials',[
                    'msg' => $_msg,
                    'typ' => 'S'
                ],'');
                $this->_view->render();      
            }else{   
                    header('Location: /default/index/');
            }
                       
        } 

        public function deleteNewsUpdates(){
            if(isset($_SESSION['member_no'])){

                $msg = 'Could not remove update';
                $typ = 'F';
                $_n = new newsUpdatesClass();
                if($_n->deleteNewsUpdates($_POST['h_trans_id']))
                    {
                        $msg = 'Update removed';
                        $typ = 'S';
                    }
                    
                $this->view('mystatsdir'.DS.'adminnotifications',[
                    'msg' => $msg,
                    'typ' => $typ
                ],'');
                $this->_view->render();
                    

            }else{   
                header('Location: /default/index/');
            }     
        }

        public function deleteTestimonials(){
            if(isset($_SESSION['member_no'])){

                $msg = 'Could not remove testimonial';
                $typ = 'F';

                $_n = new testimonialsClass();
                if($_n->deleteTestimonials($_POST['h_trans_id']))
                    {
                        $msg = 'Testimonial removed';
                        $typ = 'S';
                    }
                    
                $this->view('mystatsdir'.DS.'admintestimonials',[
                    'msg' => $msg,
                    'typ' => $typ
                ],'');
                $this->_view->render();
                    

            }else{   
                header('Location: /default/index/');
            }     
        }

        public function addNewsUpdates($_id='', $_name=''){

            if(isset($_SESSION['member_no'])){
                
                if (isset($_POST['UPLOAD'])){
    
                    if(isset($_FILES['POP']) && !empty($_FILES["POP"]["name"])){
                        $_random = rand(1000,10000);
                        //$target_dir = 'C:\Users\Cindy_Cee\Documents\Work\The Adventure\root\uploads\\';
                        $target_dir = UPLOADS;
                        $target_file = $target_dir.date('d').'_'.$_random.'_update_'.basename($_FILES["POP"]["name"]);
                        $temp = $_FILES['POP']['tmp_name'];
        
                        $file_name = '/uploads/'.date('d').'_'.$_random.'_update_'.basename($_FILES["POP"]["name"]);
        
                        if ($_FILES["POP"]["size"] < 2000000) {
                            if (move_uploaded_file($temp,$target_file)){
                                //$_upload = $_model->uploadPoP($_assoc,$file_name);
    
                                if(1==1){
                                    $_test = new newsUpdatesClass();
                                    $_mem  = new memberClass();
                                                    
                                    $_name = 'Testimonial';
                                    $_add  = $_test->addNewsUpdates($_POST['title'], $_POST['news'], $file_name);
                                    if($_add=='News Updates Created'){
                                        $_msg = 'News updates created';
                                    }else{
                                        $_msg = $_add;
                                    }
                                } 
                            }
                            else{
                                $_msg ='Error Uploading';
                            }
                        }
                        else{
                            $_msg = 'File too large';
                        }    
                    }else{
                        $_test = new newsUpdatesClass();
                        $_mem  = new memberClass();
                                        
                        $_name = 'Testimonial';
                        $_add  = $_test->addNewsUpdates($_POST['title'], $_POST['news'],'');
                        if($_add=='News Updates Created'){
                            $_msg = 'News updates created';
                        }else{
                            $_msg = $_add;
                        }
                    }                                            
                }
                else{
                    $_msg = 'File required';
                }    
                $this->view('mystatsdir'.DS.'adminnotifications',[
                    'msg' => $_msg,
                    'typ' => 'S'
                ],'');
                $this->_view->render();      
            }else{   
                    header('Location: /default/index/');
            }                       
        }

        public function approveTestimonial($_id='', $_name=''){

            if(isset($_POST['trans_id'])){
                $_obj     = new testimonialsClass();
                $_approve = $_obj->updateTestimonial($_POST['trans_id'],'Approved');
                if($_approve=='Testimonial Updated'){
                    echo 'Testimonial Approved';
                }else{
                    echo $_activate;
                }
            }            
        } 

        public function declineTestimonial($_id='', $_name=''){

            if(isset($_POST['trans_id'])){
                $_obj     = new testimonialsClass();
                $_approve = $_obj->updateTestimonial($_POST['trans_id'],'Declined');
                if($_approve=='Testimonial Updated'){
                    echo 'Testimonial Declined';
                }else{
                    echo $_activate;
                }
            }            
        } 

        public function claim($_id='', $_name=''){

            if(isset($_POST['claim'])){

                $_tree  = new treeClass();
                $_mem  = new  memberClass();
                $_obj   = new incentivesClass();
                $_obj_3 = new allocationsClass();
                $_user_no = $_obj->getMember($_POST['claim']);
                $_stage   = $_obj->getStage($_POST['claim']);
                $_amount = 0;
                $_description = '';
                
                if($_stage == '1'){
                    $_amount = 100;
                    $_description = 'Stage 1 incentives';
                }elseif($_stage == '2'){
                    $_amount = 800;
                    $_description = 'Stage 2 incentives';
                }elseif($_stage == '3'){
                    $_amount = 5000;
                    $_description = 'Stage 3 incentives';
                }elseif($_stage == '4'){
                    $_amount = 50000;
                    $_description = 'Stage 4 incentives';
                }elseif($_stage == '5'){
                    $_amount = 150000;
                    $_description = 'Stage 5 incentives';
                }elseif($_stage == '6'){
                    $_amount = 1000000;
                    $_description = 'Stage 6 incentives';
                }

                $_arr_1 = $_user_no;

                if($_mem->isActive(ME)=='Inactive'){
                    echo 'Inactive accounts cannot claim incentives';
                }elseif( ($_stage=='1' && $_tree->getCurrentStage_2($_arr_1)>= 1) || 
                    ($_stage=='2' && $_tree->getCurrentStage_2($_arr_1)>= 2) ||
                    ($_stage=='3' && $_tree->getCurrentStage_2($_arr_1)>= 3) ||
                    ($_stage=='4' && $_tree->getCurrentStage_2($_arr_1)>= 4) ||
                    ($_stage=='5' && $_tree->getCurrentStage_2($_arr_1)>= 5) ||
                    ($_stage=='6' && $_tree->getCurrentStage_2($_arr_1)>= 6)){

                    $_claim   = $_obj_3->addAllocation('1000',$_user_no,$_amount,$_description);

                    if($_claim=='Allocations details inserted'){

                        $_claim = $_obj->updateIncentive($_POST['claim']);
                        if($_claim=='Incentive Claimed'){
                            echo 'Incentive Claimed';
                        }else{
                            echo $_claim;
                        }
                    }else{
                        echo $_claim;
                    }
                }else{
                    echo 'One or more of your downliners is inactive, incentive cannot be claimed';
                }
            }else{
                header('Location: /default/index/');
            }
            
        } 
        
        public function updateBankingDetails($_id='', $_name=''){

            if(isset($_SESSION['member_no']) && isset($_POST['UpdateBankingDetailsNew'])){

                $_mem_array[] = '';

                $_accholder = $_POST['AccHolder'];
                $_bank      = $_POST['Bank'];        
                $_accnumber = $_POST['AccNumber'];
                $_branchcode = $_POST['branchCode'];
                $_wallet = $_POST['wallet'];

                $_mem_array['accholder']    = $_accholder;
                $_mem_array['bank']         = $_bank;
                $_mem_array['accnumber']    = $_accnumber;
                $_mem_array['branchcode']   = $_branchcode;
                $_mem_array['wallet']       = $_wallet;

                $_obj = new bankClass();
                $_signup = $_obj->updateBanking($_mem_array);
            
                echo $_signup;

                $this->view('mystatsdir'.DS.'profile',[
                    'msg' => $_signup,
                    'typ' => 'S'
                ],'');
                $this->_view->render(); 

            }else{
                header('Location: /default/index/');
            }
        } 
        
    }
?>