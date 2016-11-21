<?php

ob_start();
session_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Login;
use App\Db\Dao\Impl\ClientDaoImpl;
use App\Db\Dao\Impl\TableNameDaoImpl;
use App\Utility\Utilities;
use App\Model\UserPermission;

$_CURRENT_USER = unserialize($_SESSION['QUIOSGRAMA']['USER']);

$login = new Login();
$permissionObject = new UserPermission();
$utilities = new Utilities();

if ($login->isUserLogged()) {
  if($permissionObject->accessPermitted()) {
    $daoTable = new TableNameDaoImpl();
    $tables = $daoTable->listAll();
    if (trim($utilities->getParameter("id")) != "") {
      $dao = new ClientDaoImpl();
      $client = $dao->listOne($utilities->getParameter("id"));
      $page_title = "Edi&ccedil&atilde;o de Cliente";
      $body_page_title = "Cliente";
      $body_page_subtitle = "Edi&ccedil;&atilde;o";
      $inc_pg_body = "ViewRegister.php";
      include(getenv('QUIOSGRAMA_SYSTEM_PATH') . Constants::TEMPLATE_MAIN);
    } else {
      $page_title = "Cadastro de Cliente";
      $body_page_title = "Cliente";
      $body_page_subtitle = "Cadastro";
      $inc_pg_body = "ViewRegister.php";
      include(getenv('QUIOSGRAMA_SYSTEM_PATH') . Constants::TEMPLATE_MAIN);
    }
  } else {
    $utilities->alertJs("Voc&ecirc; n&atilde;o possui permiss&atilde;o para acessar essa p&aacute;gina!");
  }
} else {
  header("Location:/dashboard");
}
?>
