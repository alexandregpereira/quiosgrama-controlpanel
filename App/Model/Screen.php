<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class Screen {

    function __construct() {

    }

    private $_id;
    private $_description;
    private $_url;
    private $_module;
    private $_listOnTheScreen;
    private $_needAdministratorPermission;

    public function getId() {
        return $this->_id;
    }

    public function getDescription() {
        return $this->_description;
    }

    public function getUrl() {
        return $this->_url;
    }

    public function getModule() {
        return $this->_module;
    }

    public function getListOnTheScreen() {
        return $this->_listOnTheScreen;
    }

    public function getNeedAdministratorPermission() {
        return $this->_needAdministratorPermission;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function setDescription($description) {
        $this->_description = $description;
    }

    public function setUrl($url) {
        $this->_url = $url;
    }

    public function setModule($module) {
        $this->_module = $module;
    }

    public function setListOnTheScreen($listOnTheScreen) {
        $this->_listOnTheScreen = $listOnTheScreen;
    }

    public function setNeedAdministratorPermission($needAdministratorPermission) {
        $this->_needAdministratorPermission = $needAdministratorPermission;
    }

}

?>
