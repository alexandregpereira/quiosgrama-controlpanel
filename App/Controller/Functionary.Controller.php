<?php

/**
 * @author Jean Souza
 */
session_start();
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Functionary;
use App\Db\Dao\Impl\FunctionaryDaoImpl;
use App\Model\Login;
use App\Utility\Utilities;
use App\WS\SendPushSocket;
use App\WS\Core;

$utilities = new Utilities();
$login = new Login();

if ($login->isUserLogged()) {

    $action = $utilities->getParameter('action');

    switch ($action) {
        case 'add':

        $dao = new FunctionaryDaoImpl();

        $object = new Functionary();
        $object->setName($utilities->getParameter("name"));
        $object->setDevice($utilities->getParameter("device"));
        $object->setAdminFlag($utilities->getParameter("functionaryType"));

        $functionary = $dao->listOneByName($utilities->getParameter("name"));

        $success = false;
        if (count($functionary) <= 0) {
          $ret = $dao->add($object);
          if($ret > 0){
            $success = true;
          }
        }
        else {
          $object->setId($functionary[0]->id);
          $success = $dao->update($object);
          $ret = $functionary[0]->id;
        }

        if($success){
          $obj = $dao->listOne($ret);
          $list = array($obj[0]);
          $core = new Core();
          $array = $core->buildFunctionaryJsonArray($list);
          $pushSocket = new SendPushSocket();
          $pushSocket->sendPush($array, 10);
        }

        header("Location:/listagem-de-funcionarios");

        break;

        case 'upd':

        $dao = new FunctionaryDaoImpl();

        $object = new Functionary();
        $object->setId($utilities->getParameter("id"));
        $object->setName($utilities->getParameter("name"));
        $object->setDevice($utilities->getParameter("device"));
        $object->setAdminFlag($utilities->getParameter("functionaryType"));

        $functionary = $dao->listOneByName($utilities->getParameter("name"));
        if (count($functionary) > 0) {
          $object->setDevice(null);
          $dao->updateDevice($object);

          $object->setId($functionary[0]->id);
          $object->setDevice($utilities->getParameter("device"));
        }

        $ret = $dao->update($object);

        if($ret){
          $message['status'] = "Functionary Changed";
          $array = array($message);
          $pushSocket = new SendPushSocket();
          $pushSocket->sendPush($array, -1);
        }

        header("Location:/listagem-de-funcionarios");

        break;

        case 'del':
        if (trim($utilities->getParameter("id")) != "") {
            $dao = new FunctionaryDaoImpl();
            $ret = $dao->remove($utilities->getParameter("id"));

            if(!$ret){
              $functionary = $dao->listOne($utilities->getParameter("id"));
              if(count($functionary) > 0){
                $object = new Functionary();
                $object->setId($utilities->getParameter("id"));
                $object->setName($functionary[0]->name);
                $object->setDevice(null);
                $object->setAdminFlag($functionary[0]->admin_flag);

                $ret = $dao->update($object);
              }
            }

            if($ret){
              $message['status'] = "Object Deleted";
              $array = array($message);
              $pushSocket = new SendPushSocket();
              $pushSocket->sendPush($array, -1);
            }
        }
        header("Location:/listagem-de-funcionarios");
        break;

        default:
        header("Location:/listagem-de-funcionarios");
        break;
    }
} else {
    header('Location:/login');
}

unset($login);
?>
