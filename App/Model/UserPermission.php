<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Db\Dao\Impl\UserPermissionDaoImpl;
use App\Model\User;

class UserPermission {

    private $_id;
    private $_user;
    private $_module;
    private $_screen;

    public function getId() {
        return $this->_id;
    }

    public function getUser() {
        return $this->_user;
    }

    public function getModule() {
        return $this->_module;
    }

    public function getScreen() {
        return $this->_screen;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function setUser($user) {
        $this->_user = $user;
    }

    public function setModule($module) {
        $this->_module = $module;
    }

    public function setScreen($screen) {
        $this->_screen = $screen;
    }

    public function accessPermitted() {
        $daoPermission = new UserPermissionDaoImpl();
        $urlString = $_SERVER['REQUEST_URI'];
        $urlArray = explode('/', $urlString);

        $_CURRENT_USER = unserialize($_SESSION['QUIOSGRAMA']['USER']);

        return $daoPermission->accessPermitted('/' . $urlArray[1]);
    }

}
