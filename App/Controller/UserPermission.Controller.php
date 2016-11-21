<?php

/**
 * @author Jean Souza
 */
session_start();
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\UserPermission;
use App\Db\Dao\Impl\UserPermissionDaoImpl;
use App\Model\Login;
use App\Utility\Utilities;

$utilities = new Utilities();
$login = new Login();

if ($login->isUserLogged()) {

    $action = $utilities->getParameter('action');

    switch ($action) {
        case 'add':

        if ($utilities->getParameter("permissionType") == "module") {
            $dao = new UserPermissionDaoImpl();
            $permission = $dao->listOneByUserAndModule($utilities->getParameter("user"), $utilities->getParameter("module"));

            if (count($permission) == 0) {
                $object = new UserPermission();
                $object->setUser($utilities->getParameter("user"));
                $object->setModule($utilities->getParameter("module"));
                $ret = $dao->addModule($object);
                header("Location:/listagem-de-permissoes");
            } else {
                $utilities->alertJs('Permiss&atilde;o j&aacute; existente!');
            }
        } else {
            $dao = new UserPermissionDaoImpl();
            $permission = $dao->listOneByUserAndScreen($utilities->getParameter("user"), $utilities->getParameter("screen"));

            if (count($permission) == 0) {
                $object = new UserPermission();
                $object->setUser($utilities->getParameter("user"));
                $object->setScreen($utilities->getParameter("screen"));
                $ret = $dao->addScreen($object);
                header("Location:/listagem-de-permissoes");
            } else {
                $utilities->alertJs('Permiss&atilde;o j&aacute; existente!');
            }
        }

        break;

        case 'upd':

        if ($utilities->getParameter("permissionType") == "module") {
            $dao = new UserPermissionDaoImpl();
            $permission = $dao->listOneByUserAndModule($utilities->getParameter("user"), $utilities->getParameter("module"));

            if ((count($permission) == 0) || (count($permission) == 1 && $permission[0]->id == $utilities->getParameter("id"))) {

                $object = new UserPermission();
                $objAux = $dao->listOne($utilities->getParameter("id"));
                $object->setId($utilities->getParameter("id"));
                $object->setUser($utilities->getParameter("user"));
                $object->setModule($utilities->getParameter("module"));
                $object->setScreen(null);
                $ret = $dao->updatePermissionModule($object);

                header("Location:/listagem-de-permissoes");
            } else {
                $utilities->alertJs('Permiss&atilde;o j&aacute; existente!');
            }
        } else {
            $dao = new UserPermissionDaoImpl();
            $permission = $dao->listOneByUserAndScreen($utilities->getParameter("user"), $utilities->getParameter("screen"));

            if ((count($permission) == 0) || (count($permission) == 1 && $permission[0]->id == $utilities->getParameter("id"))) {

                $object = new UserPermission();
                $objAux = $dao->listOne($utilities->getParameter("id"));
                $object->setId($utilities->getParameter("id"));
                $object->setUser($utilities->getParameter("user"));
                $object->setScreen($utilities->getParameter("screen"));
                $object->setModule(null);
                $ret = $dao->updatePermissionScreen($object);

                header("Location:/listagem-de-permissoes");
            } else {
                $utilities->alertJs('Permiss&atilde;o j&aacute; existente!');
            }
        }

        break;

        case 'del':
        if (trim($utilities->getParameter("id")) != "") {
            $dao = new UserPermissionDaoImpl();
            $dao->remove($utilities->getParameter("id"));
        }
        header("Location:/listagem-de-permissoes");
        break;

        default:
        header("Location:/listagem-de-permissoes");
        break;
    }
} else {
    header('Location:/login');
}

unset($login);
?>
