<?php

/**
 * @author Alexandre Pereira
 */

namespace App\WS;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\WS\Core;
use App\Db\Dao\Impl\ProductRequestDaoImpl;
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

$response['code'] = 1;

$json = file_get_contents('php://input');
$push = json_decode($json);
$core = new Core();

if(isset($push->imei) && $core->isValidImei($push->imei)) {

  $prodReqDao = new ProductRequestDaoImpl();
  $prodReqObjArray = $prodReqDao->listPrint();

  if(count($prodReqObjArray) > 0){
    $printList = array();
    $noPrintList = array();
    $prodReqArray = $core->buildProductRequestJsonArray($prodReqObjArray);
    foreach ($prodReqArray as $prodReq) {
      $prodReqObj = (object) $prodReq;
      if($prodReqObj->printing == 1){
        array_push($noPrintList, $prodReqObj);
      }
      else{
        array_push($printList, $prodReqObj);
      }
    }

    if(count($noPrintList) > 0){
      foreach ($noPrintList as $prodReqObj) {
        $timeFirst  = strtotime($prodReqObj->printTime);
        $timeSecond = strtotime(date('Y-M-d H:i:s'));
        $differenceInSeconds = $timeSecond - $timeFirst;

        if($differenceInSeconds > 10){
          array_push($printList, $prodReqObj);
        }
      }
    }

    if(count($printList) > 0){
      $success = $prodReqDao->updatePrint($printList);
      if($success){
        $resultJson['productRequestList'] = $printList;
      }
      else{
        $response['code'] = 6;
        error_log("getProdReqPrintList: Erro ao atualizar a lista");
      }
    }
  }

  $response['status'] = $responseCode[ $response['code'] ]['HTTP Response'];
  // Return Response to browser

  if(isset($resultJson)){
    $json = json_encode($resultJson);
    $core->deliverResponse($response, $json);
  }
  else{
    $core->deliverResponse($response);
  }
}
else{
  $response['code'] = 7;

  $resultJson['message'] = "Dispositivo não cadastrado, acesse a função zombie no menu para ler o QrCode do estabelecimento";
  $json = json_encode($resultJson);

  $response['status'] = $responseCode[ $response['code'] ]['HTTP Response'];
  // Return Response to browser
  $core->deliverResponse($response, $json);
}

?>
