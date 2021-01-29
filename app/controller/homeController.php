<?php

class homeController extends Controller{

    public function index($_id='', $_name=''){
        $this->view('home'.DS.'index',[
            'name' => $_name,
            'id'   => $_id
        ],'Wallet Booster | Home');

        $this->_view->render();

        /*$this->redirect('auth/index');
            exit();*/

    }

    public function faq($_id='', $_name=''){
        $this->view('home'.DS.'index',[
            'name' => $_name,
            'id'   => $_id
        ],'Wallet Booster | Home');

        $this->_view->render();

        /*$this->redirect('auth/index');
            exit();*/

    }

    public function news($_id='', $_name=''){

        if($_id==''){
            $_n = new newsUpdatesClass();
            $_id = $_n->getLatestNews();
        }

        $this->view('home'.DS.'news',[
            'name' => $_name,
            'id'   => $_id
        ],'Wallet Booster | News');

        $this->_view->render();

        /*$this->redirect('auth/index');
            exit();*/

    }

    public function notFound($_id='', $_name=''){
        /*$this->view('home'.DS.'index',[
            'name' => $_name,
            'id'   => $_id
        ],'Wallet Booster | P2P Sytem');

        $this->_view->render();*/

        $this->redirect('auth/index');
            exit();

    }

    /*public function contactus($_id='', $_name=''){
        $this->view('home'.DS.'contactus',[
            'name' => $_name,
            'id'   => $_id
        ],'Contact Us');

        $this->_view->render();
    }*/

}

?>