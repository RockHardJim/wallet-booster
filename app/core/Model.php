<?php

class Model{

    protected $_db;
    protected $_conn;
    protected $_msg;

    public function __construct(){
        $this->_db        = Database::getInstance();
        $this->_conn      = $this->_db->getConnection();
    }
}