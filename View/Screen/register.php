<?php

ob_start();
session_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Login;
use App\Db\Dao\Impl\ScreenDaoImpl;
use App\Db\Dao\Impl\ModuleDaoImpl;
use App\Utility\Utilities;
use App\Model\UserPermission;

$login = new Login();
$permissionObject = new UserPermission();
$utilities = new Utilities();

if ($login->isUserLogged()) {
    if($permissionObject->accessPermitted()) {
        $daoModule = new ModuleDaoImpl();
        $modules = $daoModule->listAll();
        if (trim($utilities->getParameter("id")) != "") {
            $dao = new ScreenDaoImpl();
            $screen = $dao->listOne($utilities->getParameter("id"));
            $page_title = "Edi&ccedil&atilde;o de Tela";
            $body_page_title = "Tela";
            $body_page_subtitle = "Edi&ccedil;&atilde;o";
            $inc_pg_body = "ViewRegister.php";
            include(getenv('QUIOSGRAMA_SYSTEM_PATH') . Constants::TEMPLATE_MAIN);
        } else {
            $page_title = "Cadastro de Tela";
            $body_page_title = "Tela";
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
