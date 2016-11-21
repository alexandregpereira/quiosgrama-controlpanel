<?php

session_start();
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Login;
use App\Model\User;
use App\Db\Dao\Impl\UserDaoImpl;
use App\Utility\Utilities;

$utilities = new Utilities();

$user = $utilities->getParameter('user');
$password = $utilities->getParameter('password');

$login = new Login($user, $password);
$correctLogin = $login->checkLogin();

if($correctLogin) {
  $userDao = new UserDaoImpl();
  $data = $userDao->listOneByUser($user);

  $user = new User();

  $user->setId($data[0]->id);
  $user->setUser($data[0]->user);
  $user->setPassword($data[0]->password);
  $user->setName($data[0]->name);
  $user->setRegisterDate($data[0]->register_date);
  $user->setAdministrator($data[0]->administrator);

  $_SESSION['QUIOSGRAMA']['USER'] = serialize($user);

  if($utilities->getParameter("stayConnected") == "on") {
    setcookie('QUIOSGRAMA-USER', serialize($user));
  }

  unset($userDao);
  header('Location:/dashboard');
} else {
  header('Location:/login/login-incorreto');
}
?>
