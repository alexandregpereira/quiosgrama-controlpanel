<?php

ob_start();
session_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Login;
use App\Utility\Utilities;
use App\Model\UserPermission;
use App\Db\Dao\Impl\UserPermissionDaoImpl;
use App\Db\Dao\Impl\TableNameDaoImpl;
use App\Db\Dao\Impl\RequestDaoImpl;
use App\Db\Dao\Impl\ProductRequestDaoImpl;

$daoPermissao = new UserPermissionDaoImpl();
$_CURRENT_USER = unserialize($_SESSION['QUIOSGRAMA']['USER']);

$login = new Login();
$permissionObject = new UserPermission();
$utilities = new Utilities();

if ($login->isUserLogged()) {
	if($permissionObject->accessPermitted()) {

		$daoTables = new TableNameDaoImpl();
		$daoRequest = new RequestDaoImpl();
		$daoProductRequest = new ProductRequestDaoImpl();

		$tablesDashboardTemp = $daoTables->listAllDashboard();
		$tablesDashboard = array();

		$qtdOpen = 0;
		$qtdClose = 0;
		$qtdAlert = 0;

		$lastTable = null;

		foreach($tablesDashboardTemp as $table) {
			if(!is_null($lastTable) && $lastTable->number == $table->number) {
				$lastOpenDate = date("Y-m-d H:i:s", strtotime($lastTable->open_time));
				$openDate = date("Y-m-d H:i:s", strtotime($table->open_time));

				if($openDate > $lastOpenDate) {
					$lastTable = $table;
				}
			} elseif(!is_null($lastTable)) {
				array_push($tablesDashboard, $lastTable);
				$lastTable = $table;
			} else {
				$lastTable = $table;
			}
		}

		if(!is_null($lastTable)) array_push($tablesDashboard, $lastTable);

		foreach($tablesDashboard as $table) {
			if(is_null($table->open_time) || (strtotime(date("Y-m-d", strtotime($table->open_time))) != strtotime(date("Y-m-d")))) {
				$qtdClose++;
			} else {
				if(is_null($table->close_time)) {
					$qtdOpen++;
				} else {
					if(is_null($table->paid_time)) {
						$qtdAlert++;
					} else {
						$qtdClose++;
					}
				}
			}
		}

		$page_title = "Dashboard";
		$body_page_title = "Dashboard";
		$body_page_subtitle = "";
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
