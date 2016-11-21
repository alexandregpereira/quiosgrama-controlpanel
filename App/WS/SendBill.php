<?php

/**
 * @author Alexandre Pereira
 */

namespace App\WS;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\WS\Core;
use App\WS\SendPushSocket;
use App\Model\Bill;
use App\WS\SendPush;
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
$success = false;

$json = file_get_contents('php://input');
$push = json_decode($json);
$core = new Core();

if(isset($push->imei) && $core->isValidImei($push->imei)) {
  if($core->isValidLicence()){
    $billArray = $push->list;
    if(!is_null($billArray) && is_array($billArray)) {
      $dao = new BillDaoImpl();
      foreach ($billArray as $billItem) {

        $bill = new Bill();
        $bill->setId($billItem->id);
        $bill->setOpenTime($billItem->openTime);
        if(isset($billItem->closeTime))
        	$bill->setCloseTime($billItem->closeTime);
        if(isset($billItem->paidTime))
        	$bill->setPaidTime($billItem->paidTime);
        $bill->setWaiterOpenTable($billItem->waiterOpenTable->id);
        if(isset($billItem->waiterCloseTable) && !is_null($billItem->waiterCloseTable))
        	$bill->setWaiterCloseTable($billItem->waiterCloseTable->id);
        $bill->setTable($billItem->table->number);
        $bill->setTime($billItem->billTime);
        if(isset($billItem->syncStatus))
        	$bill->setSyncStatus($billItem->syncStatus);
        if(isset($billItem->servicePaid)){
          if(!$billItem->servicePaid){
             $bill->setServicePaid(0);
          }
          else{
            $bill->setServicePaid(1);
          }
        }
        else {
          $bill->setServicePaid(0);
        }

        $obj = $dao->listOne($bill->getId());
        if(count($obj) <= 0){
          $obj = $dao->listByNumber($bill->getTable());
          if(count($obj) > 0){
            $bill->setId($obj[0]->id);
          }
        }

        if(count($obj) > 0){
          if(strtotime($bill->getTime()) >=  strtotime($obj[0]->bill_time)){
            $success = $dao->update($bill);
          }
          else{
            $success = true;
          }

          if($success){
            $response['code'] = 1;
          }
          else{
            error_log(print_r("SendBill: Erro ao atualizar a Bill", TRUE));
          }
        }
        else {
          $id = $dao->add($bill);
          if($id > 0){
            $success = true;
            $response['code'] = 1;
          }
          else{
            error_log(print_r("SendBill: Erro ao inserir a Bill", TRUE));
          }
        }

        if(count($billArray) > 0 && $success){
          $pushSocket = new SendPushSocket();
          $pushSocket->setImeiException($push->imei);
          $pushSocket->sendPush($billArray, 4);
        }
      }
    }
  }
  else{
    $response['code'] = 7;

    $resultJson['message'] = "A licença precisa ser autenticada, sincronize o app";
    $returnJson = json_encode($resultJson);
  }
}
else{
  $response['code'] = 7;

  $resultJson['message'] = "Dispositivo não cadastrado, acesse a função zombie no menu para ler o QrCode do estabelecimento";
  $returnJson = json_encode($resultJson);
}

$response['status'] = $responseCode[ $response['code'] ]['HTTP Response'];
// Return Response to browser
if(isset($returnJson)){
  $core->deliverResponse($response, $returnJson);
}
else{
  $core->deliverResponse($response);
}

?>
