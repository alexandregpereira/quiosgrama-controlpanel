<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class Client {

    function __construct() {

    }

    private $_id;
    private $_name;
    private $_cpf;
    private $_phone;
    private $_tempFlag;
    private $_presentFlag;
    private $_table;

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getName() {
        return $this->_name;
    }

    public function setName($name) {
        $this->_name = $name;
    }

    public function getCpf() {
        return $this->_cpf;
    }

    public function setCpf($cpf) {
        $this->_cpf = $cpf;
    }

    public function getPhone() {
        return $this->_phone;
    }

    public function setPhone($phone) {
        $this->_phone = $phone;
    }

    public function getTempFlag() {
        return $this->_tempFlag;
    }

    public function setTempFlag($tempFlag) {
        $this->_tempFlag = $tempFlag;
    }

    public function getPresentFlag() {
        return $this->_presentFlag;
    }

    public function setPresentFlag($presentFlag) {
        $this->_presentFlag = $presentFlag;
    }

    public function getTable() {
        return $this->_table;
    }

    public function setTable($table) {
        $this->_table = $table;
    }

}

?>
