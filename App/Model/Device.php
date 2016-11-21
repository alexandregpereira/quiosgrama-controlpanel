<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class Device {

    function __construct() {

    }

    private $_id;
    private $_imei;
    private $_registrationId;
    private $_ip;
    private $_exclusionTime;

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getImei() {
        return $this->_imei;
    }

    public function setImei($imei) {
        $this->_imei = $imei;
    }

    public function getRegistrationId() {
        return $this->_registrationId;
    }

    public function setRegistrationId($registrationId) {
        $this->_registrationId = $registrationId;
    }

    public function getIp() {
        return $this->_ip;
    }

    public function setIp($ip) {
        $this->_ip = $ip;
    }
    
    public function getExclusionTime() {
        return $this->_exclusionTime;
    }

    public function setExclusionTime($exclusionTime) {
        $this->_exclusionTime = $exclusionTime;
    }
}

?>
