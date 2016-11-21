<?php

/**
* @author Jean Souza
*/
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
$login = new Login();

if ($login->isUserLogged()) {

  $userAtual = unserialize($_SESSION['QUIOSGRAMA']['USER']);

  if ($userAtual->getPassword() == $login->encryptPassword($utilities->getParameter("currentPassword"))) {
    if ($utilities->getParameter("newPassword") == $utilities->getParameter("newPasswordConfirmation")) {
      $userAtual->setPassword($login->encryptPassword($utilities->getParameter("newPassword")));
      $daoUser = new UserDaoImpl();
      $daoUser->update($userAtual);

      unset($login);
      header("Location:/dashboard");
    } else {
      $utilities->alertJs("As passwords digitadas nos campos Nova password e Confirma&ccedil;&atilde;o da nova password n&atilde;o s&atilde;o iguais!");
    }
  } else {
    $utilities->alertJs("Password atual incorreta!");
  }
} else {
  unset($login);
  header("Location:/login");
}
?>
