<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class Request {

    function __construct() {

    }

    private $_id;
    private $_time;
    private $_bill;
    private $_waiter;

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getTime() {
        return $this->_time;
    }

    public function setTime($time) {
        $this->_time = $time;
    }

    public function getBill() {
        return $this->_bill;
    }

    public function setBill($bill) {
        $this->_bill = $bill;
    }

    public function getWaiter() {
        return $this->_waiter;
    }

    public function setWaiter($waiter) {
        $this->_waiter = $waiter;
    }
}

?>
