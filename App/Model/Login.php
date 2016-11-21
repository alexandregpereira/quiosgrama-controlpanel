<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Db\Dao\Impl\UserDaoImpl;

class Login {

  function __construct($user = "", $password = "") {
    $this->_user = $user;
    $this->_password = $password;
  }

  private $_user;
  private $_password;

  public function getUser() {
    return $this->_user;
  }

  public function getPassword() {
    return $this->_password;
  }

  public function setUser($user) {
    $this->_user = $user;
  }

  public function setPassword($password) {
    $this->_password = $password;
  }

  public function checkLogin() {
    $userDao = new UserDaoImpl();
    $data = $userDao->listOneByUser($this->_user);

    unset($userDao);
    if(count($data) == 1) {
      return $this->comparePasswords($data[0]->password, $this->_password);
    } else {
      return false;
    }
  }

  public function isUserLogged() {
    if(isset($_SESSION['QUIOSGRAMA']['USER'])) {
      return true;
    } else {
      if(isset($_COOKIE['QUIOSGRAMA-USER'])) {
        $_SESSION['QUIOSGRAMA']['USER'] = $_COOKIE['QUIOSGRAMA-USER'];
        return true;
      } else {
        return false;
      }
    }
  }

  private function comparePasswords($passwordUser, $passwordInserted) {
    $ret = strcmp($passwordUser, $this->encryptPassword($passwordInserted));

    return $ret == 0;
  }

  public function encryptPassword($password) {
    $salt = base64_encode(Constants::KEY_CRYPT);
    $returnAux = crypt($password, '$1$' . $salt . '$');
    $return = hash('sha512', $returnAux);

    return $return;
  }

  function generatePassword($length = 8, $strength = 8) {

    $vowels = 'aeuy';
    $consonants = 'bdghjmnpqrstvz';

    if($strength >= 1) {
      $consonants .= 'BDGHJLMNPQRSTVWXZ';
    } if($strength >= 2) {
      $vowels .= "AEUY";
    } if($strength >= 4) {
      $consonants .= '23456789';
    } if($strength >= 8) {
      $vowels .= '@#$%';
    }

    $password = '';
    $alt = time() % 2;

    for ($i = 0; $i < $length; $i++) {
      if($alt == 1) {
        $password .= $consonants[(rand() % strlen($consonants))];
        $alt = 0;
      } else {
        $password .= $vowels[(rand() % strlen($vowels))];
        $alt = 1;
      }
    }

    return $password;
  }

}

?>
