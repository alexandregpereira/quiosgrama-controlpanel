<?php

ob_start();
session_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Db\Dao\Impl\TableNameDaoImpl;
use App\Db\Dao\Impl\ClientDaoImpl;
use App\Model\Login;
use App\Utility\Utilities;
use App\Model\UserPermission;
use App\Db\Dao\Impl\UserPermissionDaoImpl;

$daoPermissao = new UserPermissionDaoImpl();
$_CURRENT_USER = unserialize($_SESSION['QUIOSGRAMA']['USER']);

$login = new Login();
$permissionObject = new UserPermission();
$utilities = new Utilities();

if ($login->isUserLogged()) {
	if($permissionObject->accessPermitted()) {
		$dao = new TableNameDaoImpl();
		$daoClient = new ClientDaoImpl();

		$tables = $dao->listAll();

		$page_title = "Listagem de Mesas";
		$body_page_title = "Mesa";
		$body_page_subtitle = "Listagem";
		$inc_pg_body = "ViewIndex.php";
		include(getenv('QUIOSGRAMA_SYSTEM_PATH') . Constants::TEMPLATE_MAIN);
	} else {
		$utilities->alertJs("Voc&ecirc; n&atilde;o possui permiss&atilde;o para acessar essa p&aacute;gina!");
	}
} else {
	header('Location:/login');
}

unset($login);
?>
