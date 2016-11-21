<?php

ob_start();
session_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Db\Dao\Impl\UserPermissionDaoImpl;
use App\Db\Dao\Impl\UserDaoImpl;
use App\Db\Dao\Impl\ModuleDaoImpl;
use App\Db\Dao\Impl\ScreenDaoImpl;
use App\Model\Login;
use App\Utility\Utilities;
use App\Model\UserPermission;
use App\Model\User;

$daoPermissao = new UserPermissionDaoImpl();
$_CURRENT_USER = unserialize($_SESSION['QUIOSGRAMA']['USER']);

$login = new Login();
$permissionObject = new UserPermission();
$utilities = new Utilities();

if ($login->isUserLogged()) {
    if($permissionObject->accessPermitted()) {
        $dao = new UserPermissionDaoImpl();
        $daoUser = new UserDaoImpl();
        $daoModule = new ModuleDaoImpl();
        $daoScreen = new ScreenDaoImpl();

        $userPermissions = $dao->listAll();

        $page_title = "Listagem de Permiss&otilde;es de Usu&aacute;rio";
        $body_page_title = "Permiss&otilde;es de Usu&aacute;rio";
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
