<?php

/**
* @author Jean Souza
*/
session_start();
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\TableName;
use App\Db\Dao\Impl\TableNameDaoImpl;
use App\Db\Dao\Impl\ClientDaoImpl;
use App\Db\Dao\Impl\BillDaoImpl;
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

    $dao = new TableNameDaoImpl();
    $daoClient = new ClientDaoImpl();

    $tableNumberTmp = $utilities->getParameter("number");
    $tableNumberSequence = explode("-", $tableNumberTmp);
    $quantity = 0;
    if(count($tableNumberSequence) > 1){
      $quantity = $tableNumberSequence[1] - $tableNumberSequence[0];
      if($quantity == 0){
        $quantity = 1;
      }
      else if($quantity < 0){
        $tableNumber = $tableNumberSequence[1];
        $quantity *= -1;
      }
    }

    if(!isset($tableNumber)){
      $tableNumber = $tableNumberSequence[0];
    }

    $tableArray = array();
    for ($i=0; $i <= $quantity; $i++) {
      error_log("Mesa: " . $tableNumber);
      $tableName = $dao->listOneByNumber($tableNumber);

      $object = new TableName();
      $object->setNumber($tableNumber);
      $object->setShow($utilities->getParameter("show"));

      if($utilities->getParameter("client") != "" && $utilities->getParameter("client") != 0) {
        $object->setClient($utilities->getParameter("client"));
      }

      $object->setXPosInDpi(25);
      $object->setYPosInDpi(0);
      $object->setMapPageNumber(0);
      $object->setTime(date('Y-m-d H:i:s'));

      $clientOk = false;
      if (count($tableName) <= 0) {
        $ret = $dao->add($object);
        if($ret > 0){
          array_push($tableArray, $object);
        }
      }
      else{
        $object->setId($tableName[0]->id);
        $object->setShow(1);
        $object->setMapPageNumber(0);
        $object->setXPosInDpi(25);
        $object->setYPosInDpi(0);

        $success = $dao->updateFromJson($object);
        if($success){
          $ret = $object->getId();
          array_push($tableArray, $object);
        }
        else{
          $ret = -1;
        }
      }

      if($ret > 0 && $utilities->getParameter("client") != "" && $utilities->getParameter("client") != 0) {
        $client = $daoClient->listOne($utilities->getParameter("client"));

        if(isset($client[0]->id)) {
          $daoClient->updateTable($ret, $client[0]->id);
        }
      }

      $tableNumber++;
    }

    if(isset($ret) && $ret > 0 && count($tableArray) > 0){
      $core = new Core();
      $array = $core->buildTableJsonArray($tableArray);
      $pushSocket = new SendPushSocket();
      $pushSocket->sendPush($array, 2);
    }

    header("Location:/listagem-de-mesas");

    break;

    case 'upd':

    $dao = new TableNameDaoImpl();
    $daoClient = new ClientDaoImpl();

    $tableName = $dao->listOneByNumber($utilities->getParameter("number"));

    if ((count($tableName) == 0) || (count($tableName) == 1 && $tableName[0]->id == $utilities->getParameter("id"))) {
      $object = new TableName();
      $object->setId($utilities->getParameter("id"));
      $object->setNumber($utilities->getParameter("number"));
      $object->setShow($utilities->getParameter("show"));

      if($utilities->getParameter("client") != "" && $utilities->getParameter("client") != 0) {
        $object->setClient($utilities->getParameter("client"));
      }

      $table = $dao->listOne($utilities->getParameter("id"));

      if(isset($table[0]->client) && $table[0]->client != null && $table[0]->client > 0) {
        $daoClient->updateTable(null, $table[0]->client);
      }

      $ret = $dao->update($object);

      if($utilities->getParameter("client") != "" && $utilities->getParameter("client") != 0) {
        $client = $daoClient->listOne($utilities->getParameter("client"));

        if(isset($client[0]->id)) {
          $daoClient->updateTable($utilities->getParameter("id"), $client[0]->id);
        }
      }

      if($ret){
        $obj = $dao->listOne($object->getId());
        $tableObject = new TableName($obj[0]);
        $list = array($tableObject);

        $core = new Core();
        $array = $core->buildTableJsonArray($list);
        $pushSocket = new SendPushSocket();
        $pushSocket->sendPush($array, 2);
      }

      header("Location:/listagem-de-mesas");
    } else {
      $utilities->alertJs("Campo \"N&uacute;mero\" inv&aacute;lido!");
    }

    break;

    case 'del':
    if (trim($utilities->getParameter("id")) != "") {
      $dao = new TableNameDaoImpl();
      $daoClient = new ClientDaoImpl();

      $table = $dao->listOne($utilities->getParameter("id"));

      if(isset($table[0]->client) && $table[0]->client != null && $table[0]->client > 0) {
        $daoClient->updateTable(null, $table[0]->client);
      }

      $ret = $dao->remove($utilities->getParameter("id"));

      if(!$ret){
        $object = new TableName();
        $object->setId($utilities->getParameter("id"));
        $object->setShow(0);

        $billDao = new BillDaoImpl();
        $billObj = $billDao->listByNumber($table[0]->number);

        if(count($billObj) <= 0){
          $ret = $dao->updateShow($object);
        }
        else{
          $ret = false;
          $utilities->alertJs("A mesa " . $table[0]->number . " esta aberta!");
        }
      }

      if($ret){
        $message['status'] = "Object Deleted";
        $array = array($message);
        $pushSocket = new SendPushSocket();
        $pushSocket->sendPush($array, -1);
      }
    }
    header("Location:/listagem-de-mesas");
    break;

    default:
    header("Location:/listagem-de-mesas");
    break;
  }
} else {
  header('Location:/login');
}

unset($login);
?>
