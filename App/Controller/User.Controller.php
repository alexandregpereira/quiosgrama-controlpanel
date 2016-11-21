<?php

/**
* @author Jean Souza
*/
session_start();
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\User;
use App\Db\Dao\Impl\UserDaoImpl;
use App\Model\Login;
use App\Utility\Utilities;

$utilities = new Utilities();
$login = new Login();

if ($login->isUserLogged()) {
  $action = $utilities->getParameter('action');

  switch ($action) {
    case 'add':

    $dao = new UserDaoImpl();
    $user = $dao->listOneByUser($utilities->getParameter("user"));

    if (count($user) == 0) {
      $object = new User();
      $object->setUser($utilities->getParameter("user"));
      $object->setPassword($login->encryptPassword($utilities->getParameter("password")));
      $object->setName($utilities->replaceSpecialCharactersWithHTMLEntities($utilities->getParameter("name")));
      $object->setRegisterDate($utilities->getDate());
      $object->setEmail($utilities->getParameter("email"));

      if($utilities->getParameter("administrator") == "on") {
        $object->setAdministrator(1);
      } else {
        $object->setAdministrator(0);
      }

      $ret = $dao->add($object);

      header("Location:/listagem-de-usuarios");
    } else {
      $utilities->alertJs("Campo \"Usu&aacute;rio\" inv&aacute;lido!");
    }

    break;

    case 'upd':

    $dao = new UserDaoImpl();
    $user = $dao->listOneByUser($utilities->getParameter("user"));

    if ((count($user) == 0) || (count($user) == 1 && $user[0]->id == $utilities->getParameter("id"))) {

      $object = new User();
      $objAux = $dao->listOne($utilities->getParameter("id"));
      $object->setId($utilities->getParameter("id"));
      $object->setUser($utilities->getParameter("user"));
      $object->setEmail($utilities->getParameter("email"));

      $passwordUser = "";

      if (trim($utilities->getParameter("password")) != "") {
        $object->setPassword($login->encryptPassword($utilities->getParameter("password")));
        $passwordUser = $utilities->getParameter("password");
      } else {
        $object->setPassword($objAux[0]->password);
        $passwordUser = $objAux[0]->password;
      }
      $object->setName($utilities->replaceSpecialCharactersWithHTMLEntities($utilities->getParameter("name")));

      if($utilities->getParameter("administrator") == "on") {
        $object->setAdministrator(1);
      } else {
        $object->setAdministrator(0);
      }

      $object->setRegisterDate($objAux[0]->register_date);

      $ret = $dao->update($object);

      $_CURRENT_USER = unserialize($_SESSION['QUIOSGRAMA']['USER']);

      if($_CURRENT_USER->getId() == $object->getId()) {
        $_SESSION['QUIOSGRAMA']['USER'] = serialize($object);

        if(isset($_COOKIE['QUIOSGRAMA-USER'])) {
          setcookie('QUIOSGRAMA-USER', serialize($object));
        }
      }

      header("Location:/listagem-de-usuarios");
    } else {
      $utilities->alertJs("Campo \"Usu&aacute;rio\" inv&aacute;lido!");
    }

    break;

    case 'del':
    if (trim($utilities->getParameter("id")) != "") {
      $dao = new UserDaoImpl();
      $dao->remove($utilities->getParameter("id"));
    }
    header("Location:/listagem-de-usuarios");
    break;

    default:
    header("Location:/listagem-de-usuarios");
    break;
  }
} else {
  header('Location:/login');
}

unset($login);
?>
