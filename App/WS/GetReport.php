<?php

/**
 * @author Alexandre Pereira
 */

namespace App\WS;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\WS\Core;
use App\Model\Amount;
use App\Db\Dao\Impl\ProductRequestDaoImpl;
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
  $result = "";
  if(isset($push->date) && !is_null($push->date)){
    $amountDao = new AmountDaoImpl();
    $prodReqDao = new ProductRequestDaoImpl();

    $amountObj = $amountDao->listReport($push->date);

    if(count($amountObj) > 0){
      $moneyMethod = 0;
      $cardMethod = 0;
      $noServiceTotal = 0;
      $serviceTotal = 0;

      foreach($amountObj as $obj){
        if($obj->paid_method == 1){
          $moneyMethod += $obj->value;
        }
        else{
          $cardMethod += $obj->value;
        }

        if($obj->service_paid == 1){
          $serviceTotal += $obj->value;
        }
        else{
          $noServiceTotal += $obj->value;
        }
      }

      $result .= "Entrada em dinheiro: " . $moneyMethod;
      $result .= "<br/>Entrada em cartao: " . $cardMethod;
      $result .= "<br/>Total de entrada: " . ($moneyMethod + $cardMethod);

      $noService = $serviceTotal / 1.1;
      $noServiceTotalReceived = $noService + $noServiceTotal;
      $result .= "<br/><br/>Total sem servico: " . $noServiceTotalReceived;
      $result .= "<br/>Total do servico: " . ($serviceTotal - $noService);
    }

    $prodReqObj = $prodReqDao->listReportByFunctionary($push->date);
    if(count($prodReqObj) > 0){
      $result .= "<br/><br/>Vendas por garcom";
      $total = 0;
      foreach($prodReqObj as $obj){
        $result .= "<br/>" . $obj->name . ": " . $obj->total;
        $total += $obj->total;
      }
      $result .= "<br/>Total: " . $total;

      if(isset($noServiceTotalReceived)){
        if($total > $noServiceTotalReceived){
          $result .= "<br/><br/>Faltando: " . ($total - $noServiceTotalReceived);
        }
        else if($total < $noServiceTotal){
          $result .= "<br/>\Sobrando: " . ($noServiceTotalReceived - $total);
        }
      }
    }

    $prodReqObj = $prodReqDao->listReportByInvalidItens($push->date);
    if(count($prodReqObj) > 0){
      $result .= "<br/><br/>Itens cancelados";
      foreach($prodReqObj as $obj){
        $result .= "<br/>" . $obj->quantity . " x " . $obj->name;
      }
    }

    $prodReqObj = $prodReqDao->listReportByValidItens($push->date);
    if(count($prodReqObj) > 0){
      $result .= "<br/><br/>Vendas por item";
      foreach($prodReqObj as $obj){
        $result .= "<br/>" . $obj->quantity . " x " . $obj->name . ": " . $obj->total;
      }
    }

    $response['code'] = 1;
  }

  $response['status'] = $responseCode[ $response['code'] ]['HTTP Response'];
  $core->deliverTextResponse($response, $result);
}
else{
  $response['code'] = 3;

  $response['status'] = $responseCode[ $response['code'] ]['HTTP Response'];
  // Return Response to browser
  $core->deliverResponse($response);
}

?>
