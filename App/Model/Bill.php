<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class Bill {

    function __construct() {

    }

    private $_id;
    private $_openTime;
    private $_closeTime;
    private $_paidTime;
    private $_waiterOpenTable;
    private $_waiterCloseTable;
    private $_table;
    private $_time;
    private $_syncStatus;
    private $_servicePaid;

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getOpenTime() {
        return $this->_openTime;
    }

    public function setOpenTime($openTime) {
        $this->_openTime = $openTime;
    }

    public function getCloseTime() {
        return $this->_closeTime;
    }

    public function setCloseTime($closeTime) {
        $this->_closeTime = $closeTime;
    }

    public function getPaidTime() {
        return $this->_paidTime;
    }

    public function setPaidTime($paidTime) {
        $this->_paidTime = $paidTime;
    }

    public function getWaiterOpenTable() {
        return $this->_waiterOpenTable;
    }

    public function setWaiterOpenTable($waiterOpenTable) {
        $this->_waiterOpenTable = $waiterOpenTable;
    }

    public function getWaiterCloseTable() {
        return $this->_waiterCloseTable;
    }

    public function setWaiterCloseTable($waiterCloseTable) {
        $this->_waiterCloseTable = $waiterCloseTable;
    }

    public function getTable() {
        return $this->_table;
    }

    public function setTable($table) {
        $this->_table = $table;
    }

    public function getTime() {
        return $this->_time;
    }

    public function setTime($time) {
        $this->_time = $time;
    }

    public function getSyncStatus() {
        return $this->_syncStatus;
    }

    public function setSyncStatus($syncStatus) {
        $this->_syncStatus = $syncStatus;
    }

    public function getServicePaid() {
        return $this->_servicePaid;
    }

    public function setServicePaid($servicePaid) {
        $this->_servicePaid = $servicePaid;
    }
}

?>
