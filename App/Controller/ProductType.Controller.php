<?php

/**
 * @author Jean Souza
 */
session_start();
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\ProductType;
use App\Model\Destination;
use App\Db\Dao\Impl\ProductTypeDaoImpl;
use App\Db\Dao\Impl\DestinationDaoImpl;
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

        $dao = new ProductTypeDaoImpl();
        $productType = $dao->listOneByName($utilities->getParameter("name"));

        if (count($productType) == 0) {
            $object = new ProductType();

            $destinationName = $utilities->getParameter("destinationName");
            if(!is_null($destinationName) && $destinationName != ''){
              $daoDestination = new DestinationDaoImpl();
              $destinationObj = $daoDestination->listOneByName(strtolower($destinationName));

              $destinationObj = new Destination();
              $destinationObj->setName($destinationName);
              $destinationObj->setIconName($utilities->getParameter("iconName"));
              if(count($destinationData) > 0){
                $object->setDestination($destinationData[0]->id);
                $destinationObj->setId($destinationData[0]->id);
                $daoDestination->update($destinationObj);
              }
              else{
                $destinationId = $daoDestination->add($destinationObj);
                if($destinationId > 0){
                  $object->setDestination($destinationId);
                }
              }
            }
            else{
              $object->setDestination($utilities->getParameter("destination"));
            }

            $object->setName($utilities->getParameter("name"));
            $object->setPriority($utilities->getParameter("priority"));
            $object->setColor($utilities->getParameter("color"));

            $iconImage = $utilities->getParameter("buttonImage");
            $object->setTabImage("tab_" . substr($iconImage, 3));
            $object->setButtonImage($iconImage);
            $object->setIconImage("ic_info_" . substr($iconImage, 3));
            $ret = $dao->add($object);

            if($ret > 0){
              $obj = $dao->listOne($ret);
              $list = array($obj[0]);
              $core = new Core();
              $array = $core->buildProductTypeJsonArray($list);
              $pushSocket = new SendPushSocket();
              $pushSocket->sendPush($array, 5);
            }

            header("Location:/listagem-de-tipos-de-produto");
        } else {
            $utilities->alertJs("Campo \"Nome\" inv&aacute;lido!");
        }

        break;

        case 'upd':

        $dao = new ProductTypeDaoImpl();
        $productType = $dao->listOneByName($utilities->getParameter("name"));

        if ((count($productType) == 0) || (count($productType) == 1 && $productType[0]->id == $utilities->getParameter("id"))) {
            $object = new ProductType();

            $destinationName = $utilities->getParameter("destinationName");
            if(!is_null($destinationName) && $destinationName != ''){
              $daoDestination = new DestinationDaoImpl();
              $destinationData = $daoDestination->listOneByName(strtolower($destinationName));

              $destinationObj = new Destination();
              $destinationObj->setName($destinationName);
              $destinationObj->setIconName($utilities->getParameter("iconName"));
              if(count($destinationData) > 0){
                $object->setDestination($destinationData[0]->id);
                $destinationObj->setId($destinationData[0]->id);
                $daoDestination->update($destinationObj);
              }
              else{
                $destinationId = $daoDestination->add($destinationObj);
                if($destinationId > 0){
                  $object->setDestination($destinationId);
                }
              }
            }
            else{
              $object->setDestination($utilities->getParameter("destination"));
            }

            $object->setId($utilities->getParameter("id"));
            $object->setName($utilities->getParameter("name"));
            $object->setPriority($utilities->getParameter("priority"));
            $object->setColor($utilities->getParameter("color"));

            $iconImage = $utilities->getParameter("buttonImage");
            $object->setTabImage("tab_" . substr($iconImage, 3));
            $object->setButtonImage($iconImage);
            $object->setIconImage("ic_info_" . substr($iconImage, 3));
            $ret = $dao->update($object);

            if($ret){
              $obj = $dao->listOne($object->getId());
              $list = array($obj[0]);
              $core = new Core();
              $array = $core->buildProductTypeJsonArray($list);
              $pushSocket = new SendPushSocket();
              $pushSocket->sendPush($array, 5);
            }

            header("Location:/listagem-de-tipos-de-produto");
        } else {
            $utilities->alertJs("Campo \"Nome\" inv&aacute;lido!");
        }

        break;

        case 'del':
        if (trim($utilities->getParameter("id")) != "") {
            $dao = new ProductTypeDaoImpl();
            $ret = $dao->remove($utilities->getParameter("id"));

            if($ret){
              $message['status'] = "Object Deleted";
              $array = array($message);
              $pushSocket = new SendPushSocket();
              $pushSocket->sendPush($array, -1);
            }
        }
        header("Location:/listagem-de-tipos-de-produto");
        break;

        default:
        header("Location:/listagem-de-tipos-de-produto");
        break;
    }
} else {
    header('Location:/login');
}

unset($login);
?>
