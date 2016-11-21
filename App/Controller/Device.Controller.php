<?php

/**
* @author Jean Souza
*/
session_start();
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Device;
use App\Db\Dao\Impl\DeviceDaoImpl;
use App\Model\Login;
use App\Utility\Utilities;

$utilities = new Utilities();
$login = new Login();

if ($login->isUserLogged()) {

  $action = $utilities->getParameter('action');

  switch ($action) {
    case 'add':

    $dao = new DeviceDaoImpl();
    $device = $dao->listOneByImei($utilities->getParameter("imei"));

    if(count($device) == 0) {
      $object = new Device();
      $object->setImei($utilities->getParameter("imei"));
      $ret = $dao->add($object);
      header("Location:/listagem-de-dispositivos");
    } else {
      $utilities->alertJs("Campo \"IMEI\" inv&aacute;lido!");
    }

    break;

    case 'upd':

    $dao = new DeviceDaoImpl();
    $device = $dao->listOneByImei($utilities->getParameter("imei"));

    if(count($device) == 0 || (count($device) == 1 && $device[0]->id == $utilities->getParameter("id"))) {
      $object = new Device();
      $object->setId($utilities->getParameter("id"));
      $object->setImei($utilities->getParameter("imei"));
      $ret = $dao->update($object);
      header("Location:/listagem-de-dispositivos");
    } else {
      $utilities->alertJs("Campo \"IMEI\" inv&aacute;lido!");
    }

    break;

    case 'del':
    if (trim($utilities->getParameter("id")) != "") {
      $dao = new DeviceDaoImpl();
      $success = $dao->remove($utilities->getParameter("id"));
      
      if(!$success){
        $obj = $dao->listOne($utilities->getParameter("id"));
      
        $device = new Device();
        $device->setId($obj[0]->id);
        $device->setExclusionTime($obj[0]->exclusion_time);
        
        if(is_null($device->getExclusionTime())){
          $device->setExclusionTime(date("Y-m-d H:i:s"));
        }
        else{
          $device->setExclusionTime(null);
        }
        
        $dao->updateExclusionTime($device);
      }
    }
    header("Location:/listagem-de-dispositivos");
    break;

    default:
    header("Location:/listagem-de-dispositivos");
    break;
  }
} else {
  header('Location:/login');
}

unset($login);
?>
