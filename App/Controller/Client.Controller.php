<?php

/**
* @author Jean Souza
*/
session_start();
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Client;
use App\Db\Dao\Impl\ClientDaoImpl;
use App\Db\Dao\Impl\TableNameDaoImpl;
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

    $dao = new ClientDaoImpl();
    $client = $dao->listOneByCpf($utilities->getParameter("cpf"));

    if (count($client) == 0) {
      $object = new Client();

      $object->setName($utilities->getParameter("name"));
      $object->setCpf($utilities->removeSpecialCharacters($utilities->getParameter("cpf")));
      $object->setPhone($utilities->removeSpecialCharacters($utilities->getParameter("phone")));
      $object->setTempFlag($utilities->getParameter("tempFlag"));
      $object->setPresentFlag($utilities->getParameter("presentFlag"));

      $ret = $dao->add($object);

      if($ret > 0){
          $obj = $dao->listOne($ret);
          $list = array($obj[0]);
          $core = new Core();
          $array = $core->buildClientJsonArray($list);
          $pushSocket = new SendPushSocket();
          $pushSocket->sendPush($array, 7);
      }

      header("Location:/listagem-de-clientes");
    } else {
      $utilities->alertJs("Campo \"CPF\" inv&aacute;lido!");
    }

    break;

    case 'upd':

    $dao = new ClientDaoImpl();
    $client = $dao->listOneByCpf($utilities->getParameter("cpf"));

    if ((count($client) == 0) || (count($client) == 1 && $client[0]->id == $utilities->getParameter("id"))) {

      $object = new Client();
      $object->setId($utilities->getParameter("id"));
      $object->setName($utilities->getParameter("name"));
      $object->setCpf($utilities->removeSpecialCharacters($utilities->getParameter("cpf")));
      $object->setPhone($utilities->removeSpecialCharacters($utilities->getParameter("phone")));
      $object->setTempFlag($utilities->getParameter("tempFlag"));
      $object->setPresentFlag($utilities->getParameter("presentFlag"));

      $ret = $dao->update($object);

      if($ret){
              $obj = $dao->listOne($object->getId());
              $list = array($obj[0]);
              $core = new Core();
              $array = $core->buildClientJsonArray($list);
              $pushSocket = new SendPushSocket();
              $pushSocket->sendPush($array, 7);
            }

      header("Location:/listagem-de-clientes");
    } else {
      $utilities->alertJs("Campo \"CPF\" inv&aacute;lido!");
    }

    break;

    case 'del':
    if (trim($utilities->getParameter("id")) != "") {
      $dao = new ClientDaoImpl();
      $daoTable = new TableNameDaoImpl();

      $client = $dao->listOne($utilities->getParameter("id"));

      if(isset($client[0]->table) && $client[0]->table != null && $client[0]->table > 0) {
        $daoTable->updateClient(null, $client[0]->table);
      }

      $ret = $dao->remove($utilities->getParameter("id"));

      if($ret){
              $message['status'] = "Object Deleted";
              $array = array($message);
              $pushSocket = new SendPushSocket();
              $pushSocket->sendPush($array, -1);
            }
    }
    header("Location:/listagem-de-clientes");
    break;

    default:
    header("Location:/listagem-de-clientes");
    break;
  }
} else {
  header('Location:/login');
}

unset($login);
?>
