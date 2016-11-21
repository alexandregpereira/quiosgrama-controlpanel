<?php

/**
 * @author Alexandre Pereira
 */

namespace App\WS;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\WS\Core;
use App\Model\Device;
use App\Model\Functionary;
use App\WS\SendPush;
use App\Db\Dao\Impl\DeviceDaoImpl;
use App\Db\Dao\Impl\FunctionaryDaoImpl;
use App\Db\Dao\Impl\BillDaoImpl;
use App\Utility\Utilities;

// Define API response codes and their related HTTP response
$responseCode = array(
  1 => array('HTTP Response' => 200, 'Message' => 'Success'),
  6 => array('HTTP Response' => 201, 'Message' => 'Error'),
  7 => array('HTTP Response' => 202, 'Message' => 'Error'),
  0 => array('HTTP Response' => 400, 'Message' => 'Unknown Error'),
  2 => array('HTTP Response' => 403, 'Message' => 'HTTPS Required'),
  3 => array('HTTP Response' => 401, 'Message' => 'Authentication Required'),
  4 => array('HTTP Response' => 401, 'Message' => 'Authentication Failed'),
  5 => array('HTTP Response' => 404, 'Message' => 'Invalid Request')
);

$response['code'] = 6;

$json = file_get_contents('php://input');
$push = json_decode($json);
$core = new Core();

if(isset($push->imei) && $core->isValidImei($push->imei)) {
  $array = $push->list;
  $deviceJson = $array[0];
  if(!is_null($deviceJson)) {
    $dao = new DeviceDaoImpl();

    $device = new Device();
    $device->setImei($deviceJson->imei);
    if(isset($deviceJson->registrationId))
      $device->setRegistrationId($deviceJson->registrationId);
    if(isset($deviceJson->ip))
      $device->setIp($deviceJson->ip);

    $obj = $dao->listOneWS($device->getImei());

    if(count($obj) > 0){
      $device->setId($obj[0]->id);
      $success = $dao->updateFromJson($device);

      if($success){
        $response['code'] = 1;
      }
      else{
        error_log(print_r("SendRegistration: Erro ao atualizar", TRUE));
      }
    }
    else{
      $id = $dao->addFromJson($device);
      if($id > 0){
        $device->setId($id);
        $response['code'] = 1;
      }
      else{
        error_log(print_r("SendRegistration: Erro ao gravar", TRUE));
      }
    }

    if($response['code'] == 1 && $device->getImei() != $push->imei){
      $functionaryDao = new FunctionaryDaoImpl();
      $obj = $functionaryDao->listOneByDevice($device->getId());
      $admin = $functionaryDao->listAdminByImei($push->imei);

      $functionary = new Functionary();
      $functionary->setName($device->getImei());
      $functionary->setDevice($device->getId());
      $functionary->setAdminFlag($core->getAdminFlag($push->imei));

      $success = false;
      if(count($obj) > 0){
        foreach ($obj as $functionaryObj) {
          if($functionaryObj->admin_flag == Functionary::CLIENT_WAITER_CODE){
            $success = $functionaryDao->updateDisableAllDevices($functionary->getDevice());
            if(!$success){
              $response['code'] = 6;
              error_log(print_r("SendRegistration: Erro ao dispositivos dos funcionarios", TRUE));
            }
            break;
          }
        }
      }

      if($success){
        $obj = array();
      }

      if($response['code'] == 1){
        //Em caso de não ter funcionarios com o dispositivo que foi cadastrado, nome do imei, admin cadastrando
        if(count($obj) <= 0 && (count($admin) > 0 || $core->isValidCode($push->imei))){
          $functionaryName = $functionaryDao->listOneByName($functionary->getName());
          //Se já existir um funcionário com o nome do imei
          if(count($functionaryName) > 0){
            $functionary->setId($functionaryName[0]->id);
            $success = $functionaryDao->update($functionary);
            if(!$success){
              $response['code'] = 6;
              error_log(print_r("SendRegistration: Erro ao atualizar o novo funcionario", TRUE));
            }
          }
          //cria o novo funcionário com o nome do imei
          else{
            $id = $functionaryDao->add($functionary);
            if($id <= 0){
              $response['code'] = 6;
              error_log(print_r("SendRegistration: Erro ao gravar o novo funcionario", TRUE));
            }
          }
        }
        //Caso já exista funcionário com o dispositivo cadastrado, admin cadastrando
        else if(count($obj) > 0 && (count($admin) > 0 || $core->isValidCode($push->imei))){
          $functionary->setId($obj[0]->id);
          $functionary->setName($obj[0]->name);
          $success = $functionaryDao->update($functionary);
          if(!$success){
            $response['code'] = 6;
            error_log(print_r("SendRegistration: Erro ao atualizar o novo funcionario", TRUE));
          }
        }
        //Caso não seja um admin cadastrando. Cadastro de cliente
        else if(count($admin) <= 0 && isset($deviceJson->billId) && !is_null($deviceJson->billId)){
          $billDao = new BillDaoImpl();
          $billObj = $billDao->listOne($deviceJson->billId);
          if(count($billObj) > 0 && !is_null($billObj[0]->open_time) && is_null($billObj[0]->paid_time)){
            $noAdmin = $functionaryDao->listNoAdminByImei($push->imei);

            //Caso seja um não admin cadastrando
            if(count($noAdmin) > 0){
              $functionary->setName("Mesa " . $billObj[0]->table_number);
              $functionary->setAdminFlag(Functionary::CLIENT_WAITER_CODE);

              $success = $functionaryDao->updateDisableAllDevices($device->getId());
              if(!$success){
                $response['code'] = 6;
                error_log(print_r("SendRegistration: Erro ao dispositivos dos funcionarios", TRUE));
              }
              else if(count($obj) > 0){
                $functionary->setId($obj[0]->id);
                $success = $functionaryDao->update($functionary);
                if(!$success){
                  $response['code'] = 6;
                  error_log(print_r("SendRegistration: Erro ao atualizar o novo funcionario cliente", TRUE));
                }
              }
              else{
                $ret = $functionaryDao->add($functionary);
                if($ret <= 0){
                  $response['code'] = 6;
                  error_log(print_r("SendRegistration: Erro ao cadastrar o novo funcionario cliente", TRUE));
                }
              }
            }
          }
        }
      }
    }
  }
}
else{
  $response['code'] = 3;
}

$response['status'] = $responseCode[ $response['code'] ]['HTTP Response'];
// Return Response to browser
$core->deliverResponse($response);

?>
