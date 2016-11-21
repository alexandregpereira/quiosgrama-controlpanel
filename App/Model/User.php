<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class User {

    function __construct() {

    }

    private $_id;
    private $_user;
    private $_password;
    private $_name;
    private $_registerDate;
    private $_administrator;
    private $_email;

    public function getId() {
        return $this->_id;
    }

    public function getUser() {
        return $this->_user;
    }

    public function getPassword() {
        return $this->_password;
    }

    public function getName() {
        return $this->_name;
    }

    public function getRegisterDate() {
        return $this->_registerDate;
    }

    public function getAdministrator() {
        return $this->_administrator;
    }

    public function getEmail() {
        return $this->_email;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function setUser($user) {
        $this->_user = $user;
    }

    public function setPassword($password) {
        $this->_password = $password;
    }

    public function setName($name) {
        $this->_name = $name;
    }

    public function setRegisterDate($registerDate) {
        $this->_registerDate = $registerDate;
    }

    public function setAdministrator($administrator) {
        $this->_administrator = $administrator;
    }

    public function setEmail($email) {
        $this->_email = $email;
    }

}

?>
