<?php

/**
 * @author Jean Souza
 */
session_start();
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Screen;
use App\Db\Dao\Impl\ScreenDaoImpl;
use App\Model\Login;
use App\Utility\Utilities;

$utilities = new Utilities();
$login = new Login();

if ($login->isUserLogged()) {

    $action = $utilities->getParameter('action');

    switch ($action) {
        case 'add':

        $dao = new ScreenDaoImpl();
        $screen = $dao->listOneByDescriptionOrUrl($utilities->getParameter("description"), $utilities->getParameter("url"));

        if (count($screen) == 0) {
            $object = new Screen();
            $object->setDescription($utilities->replaceSpecialCharactersWithHTMLEntities($utilities->getParameter("description")));
            $object->setUrl($utilities->getParameter("url"));
            $object->setModule($utilities->getParameter("module"));

            if($utilities->getParameter("listOnTheScreen") == "on") {
                $object->setListOnTheScreen(1);
            } else {
                $object->setListOnTheScreen(0);
            }

            if($utilities->getParameter("needAdministratorPermission") == "on") {
                $object->setNeedAdministratorPermission(1);
            } else {
                $object->setNeedAdministratorPermission(0);
            }

            $ret = $dao->add($object);
            header("Location:/listagem-de-telas");
        } else {
            $utilities->alertJs("Campo \"Descri&ccedil;&atilde;o\" e / ou \"Caminho\" inv&aacute;lidos!");
        }

        break;

        case 'upd':

        $dao = new ScreenDaoImpl();
        $screen = $dao->listOneByDescriptionOrUrl($utilities->getParameter("description"), $utilities->getParameter("url"));

        if ((count($screen) == 0) || (count($screen) == 1 && $screen[0]->id == $utilities->getParameter("id"))) {

            $object = new Screen();
            $object->setId($utilities->getParameter("id"));
            $object->setDescription($utilities->replaceSpecialCharactersWithHTMLEntities($utilities->getParameter("description")));
            $object->setUrl($utilities->getParameter("url"));
            $object->setModule($utilities->getParameter("module"));

            if($utilities->getParameter("listOnTheScreen") == "on") {
                $object->setListOnTheScreen(1);
            } else {
                $object->setListOnTheScreen(0);
            }

            if($utilities->getParameter("needAdministratorPermission") == "on") {
                $object->setNeedAdministratorPermission(1);
            } else {
                $object->setNeedAdministratorPermission(0);
            }

            $ret = $dao->update($object);

            header("Location:/listagem-de-telas");
        } else {
            $utilities->alertJs("Campo \"Descri&ccedil;&atilde;o\" e / ou \"Caminho\" inv&aacute;lidos!");
        }

        break;

        case 'del':
        if (trim($utilities->getParameter("id")) != "") {
            $dao = new ScreenDaoImpl();
            $dao->remove($utilities->getParameter("id"));
        }
        header("Location:/listagem-de-telas");
        break;

        default:
        header("Location:/listagem-de-telas");
        break;
    }
} else {
    header('Location:/login');
}

unset($login);
?>
