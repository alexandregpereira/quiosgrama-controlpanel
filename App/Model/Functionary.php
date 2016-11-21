<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class Functionary {

    const WAITER_CODE = 0;
    const ADMIN_CODE = 1;
    const CLIENT_TABLE_CODE = 2;
    const CLIENT_WAITER_CODE = 3;

    function __construct() {

    }

    private $_id;
    private $_name;
    private $_device;
    private $_adminFlag;

    public function getTypeName(){
      if($this->_adminFlag == self::WAITER_CODE){
        return "Gar&ccedilom";
      }
      else if($this->_adminFlag == self::ADMIN_CODE){
        return "Administrador";
      }
      else if($this->_adminFlag == self::CLIENT_TABLE_CODE){
        return "Mesa";
      }
      else if($this->_adminFlag == self::CLIENT_WAITER_CODE){
        return "Cliente";
      }
      else{
        return "Erro";
      }
    }

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

    public function getDevice() {
        return $this->_device;
    }

    public function setDevice($device) {
        $this->_device = $device;
    }

    public function getAdminFlag() {
        return $this->_adminFlag;
    }

    public function setAdminFlag($adminFlag) {
        $this->_adminFlag = $adminFlag;
    }
}

?>
