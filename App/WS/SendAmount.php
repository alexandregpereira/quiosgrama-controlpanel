<?php

/**
 * @author Alexandre Pereira
 */

namespace App\WS;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\WS\Core;
use App\Model\Amount;
use App\WS\SendPush;
use App\Db\Dao\Impl\AmountDaoImpl;
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
  if($core->isValidLicence()){
    $amountArray = $push->list;
    if(is_array($amountArray)) {
      $dao = new AmountDaoImpl();
      $gcmArray = array();
      foreach ($amountArray as $amountItem) {

        $amount = new Amount();
        $amount->setId($amountItem->id);
        $amount->setValue($amountItem->value);
        $amount->setPaidMethod($amountItem->paidMethod);
        $amount->setBill($amountItem->bill->id);

        $dao->add($amount);
        $obj = $dao->listOne($amount->getId());
        if(count($obj) > 0){
          $response['code'] = 1;
        }
        else{
          error_log(print_r("SendAmount: Erro ao gravar Amount", TRUE));
        }
      }
    }

    if($response['code'] != 1){
      $resultJson['message'] = "Erro ao enviar os valores pagos";
      $returnJson = json_encode($resultJson);
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
