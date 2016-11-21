<?php

/**
* @author Jean Souza
*/
session_start();
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Complement;
use App\Db\Dao\Impl\ComplementDaoImpl;
use App\Db\Dao\Impl\ComplementTypeDaoImpl;
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

    $dao = new ComplementDaoImpl();
    $complement = $dao->listOneByDescription($utilities->getParameter("description"));

    if (count($complement) == 0) {
      $object = new Complement();
      $object->setDescription($utilities->getParameter("description"));
      $object->setPrice($utilities->getParameter("price"));
      $object->setDrawable($utilities->getParameter("drawable"));
      $ret = $dao->add($object);

      if($ret != 0) {
        $daoComplementType = new ComplementTypeDaoImpl();
        $productTypes = $utilities->getParameter("productTypes");

        for($i = 0; $i < count($productTypes); $i++) {
          $daoComplementType->addComplementType($productTypes[$i], $ret);
        }

        if($ret > 0){
          $complementList = $dao->listOne($ret);

          //		buildComplementType(complementList);
          if($complementList != null){
            $complementObjectList = array();
            foreach ($complementList as $complement) {
                $complementObject = new Complement($complement);
                $complementTypeList = $daoComplementType->listAllByComplementObject($complementObject);
                if($complementTypeList != null){
                    $typeArray = array();
                    foreach ($complementTypeList as $complementType) {
                        array_push($typeArray, $complementType->product_type);
                    }
                    $complementObject->setTypeArray($typeArray);
                }
                array_push($complementObjectList, $complementObject);
            }
          }

          $core = new Core();
          $array = $core->buildComplementJsonArray($complementObjectList);
          $pushSocket = new SendPushSocket();
          $pushSocket->sendPush($array, 8);
        }

        header("Location:/listagem-de-complementos");
      } else {
        $utilities->alertJs("Ocorreu um erro no processo, tente novamente.");
      }
    } else {
      $utilities->alertJs("Campo \"Descri&ccedil;&atilde;o\" inv&aacute;lido!");
    }

    break;

    case 'upd':

    $dao = new ComplementDaoImpl();
    $complement = $dao->listOneByDescription($utilities->getParameter("id"));

    if ((count($complement) == 0) || (count($complement) == 1 && $complement[0]->idComplement == $utilities->getParameter("id"))) {
      $object = new Complement();
      $object->setId($utilities->getParameter("id"));
      $object->setDescription($utilities->getParameter("description"));
      $object->setPrice($utilities->getParameter("price"));
      $object->setDrawable($utilities->getParameter("drawable"));
      $ret = $dao->update($object);

      if($ret) {
        $daoComplementType = new ComplementTypeDaoImpl();
        $productTypes = $utilities->getParameter("productTypes");

        $daoComplementType->removeComplementTypeByComplement($utilities->getParameter("id"));

        for($i = 0; $i < count($productTypes); $i++) {
          $daoComplementType->addComplementType($productTypes[$i], $utilities->getParameter("id"));
        }


        $complementList = $dao->listOne($ret);

          //		buildComplementType(complementList);
          if($complementList != null){
            $complementObjectList = array();
            foreach ($complementList as $complement) {
                $complementObject = new Complement($complement);
                $complementTypeList = $daoComplementType->listAllByComplementObject($complementObject);
                if($complementTypeList != null){
                    $typeArray = array();
                    foreach ($complementTypeList as $complementType) {
                        array_push($typeArray, $complementType->product_type);
                    }
                    $complementObject->setTypeArray($typeArray);
                }
                array_push($complementObjectList, $complementObject);
            }
          }

          $core = new Core();
          $array = $core->buildComplementJsonArray($complementObjectList);
          $pushSocket = new SendPushSocket();
          $pushSocket->sendPush($array, 8);

        header("Location:/listagem-de-complementos");
      } else {
        $utilities->alertJs("Ocorreu um erro no processo, tente novamente.");
      }
    } else {
      $utilities->alertJs("Campo \"Descri&ccedil;&atilde;o\" inv&aacute;lido!");
    }

    break;

    case 'del':
    if (trim($utilities->getParameter("id")) != "") {
      $daoComplementType = new ComplementTypeDaoImpl();
      $daoComplementType->removeComplementTypeByComplement($utilities->getParameter("id"));

      $dao = new ComplementDaoImpl();
      $ret = $dao->remove($utilities->getParameter("id"));

      if($ret){
              $message['status'] = "Object Deleted";
              $array = array($message);
              $pushSocket = new SendPushSocket();
              $pushSocket->sendPush($array, -1);
            }
    }
    header("Location:/listagem-de-complementos");
    break;

    default:
    header("Location:/listagem-de-complementos");
    break;
  }
} else {
  header('Location:/login');
}

unset($login);
?>
