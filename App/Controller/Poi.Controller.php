<?php

/**
 * @author Jean Souza
 */
session_start();
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Poi;
use App\Db\Dao\Impl\PoiDaoImpl;
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

        $dao = new PoiDaoImpl();
        $poi = $dao->listOneByName($utilities->getParameter("name"));

        if (count($poi) == 0) {
            $object = new Poi();
            $object->setName($utilities->getParameter("name"));
            $object->setImage($utilities->getParameter("image"));

            $object->setXPosInDpi(0);
            $object->setYPosInDpi(0);
            $object->setMapPageNumber(0);

            $object->setTime(date('Y-m-d H:i:s'));
            $ret = $dao->add($object);

            if($ret > 0){
              $obj = $dao->listOne($ret);
              $list = array($obj[0]);
              $core = new Core();
              $array = $core->buildPoiJsonArray($list);
              $pushSocket = new SendPushSocket();
              $pushSocket->sendPush($array, 3);
            }

            header("Location:/listagem-de-pois");
        } else {
            $utilities->alertJs("Campo \"Nome\" inv&aacute;lido!");
        }

        break;

        case 'upd':

        $dao = new PoiDaoImpl();
        $poi = $dao->listOneByName($utilities->getParameter("name"));

        if ((count($poi) == 0) || (count($poi) == 1 && $poi[0]->id == $utilities->getParameter("id"))) {
            $object = new Poi();
            $object->setId($utilities->getParameter("id"));
            $object->setName($utilities->getParameter("name"));
            $object->setImage($utilities->getParameter("image"));
            $ret = $dao->update($object);

            if($ret){
              $obj = $dao->listOne($object->getId());
              $list = array($obj[0]);
              $core = new Core();
              $array = $core->buildPoiJsonArray($list);
              $pushSocket = new SendPushSocket();
              $pushSocket->sendPush($array, 3);
            }

            header("Location:/listagem-de-pois");
        } else {
            $utilities->alertJs("Campo \"Nome\" inv&aacute;lido!");
        }

        break;

        case 'del':
        if (trim($utilities->getParameter("id")) != "") {
            $dao = new PoiDaoImpl();
            $ret = $dao->remove($utilities->getParameter("id"));

            if($ret){
              $message['status'] = "Object Deleted";
              $array = array($message);
              $pushSocket = new SendPushSocket();
              $pushSocket->sendPush($array, -1);
            }
        }
        header("Location:/listagem-de-pois");
        break;

        default:
        header("Location:/listagem-de-pois");
        break;
    }
} else {
    header('Location:/login');
}

unset($login);
?>
