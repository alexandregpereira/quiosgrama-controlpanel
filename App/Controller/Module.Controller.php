<?php

/**
 * @author Jean Souza
 */
session_start();
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Module;
use App\Db\Dao\Impl\ModuleDaoImpl;
use App\Model\Login;
use App\Utility\Utilities;

$utilities = new Utilities();
$login = new Login();

if ($login->isUserLogged()) {

    $action = $utilities->getParameter('action');

    switch ($action) {
        case 'add':

        $dao = new ModuleDaoImpl();
        $module = $dao->listOneByDescription($utilities->getParameter("description"));

        if (count($module) == 0) {
            $object = new Module();
            $object->setDescription($utilities->replaceSpecialCharactersWithHTMLEntities($utilities->getParameter("description")));
            $ret = $dao->add($object);
            header("Location:/listagem-de-modulos");
        } else {
            $utilities->alertJs("Campo \"Descri&ccedil;&atilde;o\" inv&aacute;lido!");
        }

        break;

        case 'upd':

        $dao = new ModuleDaoImpl();
        $module = $dao->listOneByDescription($utilities->getParameter("description"));

        if ((count($module) == 0) || (count($module) == 1 && $module[0]->id == $utilities->getParameter("id"))) {
            $object = new Module();
            $object->setId($utilities->getParameter("id"));
            $object->setDescription($utilities->replaceSpecialCharactersWithHTMLEntities($utilities->getParameter("description")));
            $ret = $dao->update($object);

            header("Location:/listagem-de-modulos");
        } else {
            $utilities->alertJs("Campo \"Descri&ccedil;&atilde;o\" inv&aacute;lido!");
        }

        break;

        case 'del':
        if (trim($utilities->getParameter("id")) != "") {
            $dao = new ModuleDaoImpl();
            $dao->remove($utilities->getParameter("id"));
        }
        header("Location:/listagem-de-modulos");
        break;

        default:
        header("Location:/listagem-de-modulos");
        break;
    }
} else {
    header('Location:/login');
}

unset($login);
?>
