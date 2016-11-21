<?php

/**
 * @author Alexandre Pereira
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class Amount {

    function __construct() {

    }

    private $_id;
    private $_value;
    private $_paidMethod;
    private $_bill;
    

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getValue() {
        return $this->_value;
    }

    public function setValue($value) {
        $this->_value = $value;
    }

    public function getPaidMethod() {
        return $this->_paidMethod;
    }

    public function setPaidMethod($paidMethod) {
        $this->_paidMethod = $paidMethod;
    }

    public function getBill() {
        return $this->_bill;
    }

    public function setBill($bill) {
        $this->_bill = $bill;
    }
}

?>
