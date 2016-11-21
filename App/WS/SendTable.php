<?php

/**
 * @author Alexandre Pereira
 */

namespace App\WS;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\WS\Core;
use App\WS\SendPushSocket;
use App\Model\TableName;
use App\WS\SendPush;
use App\Db\Dao\Impl\TableNameDaoImpl;
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
    $tableArray = $push->list;
    if(is_array($tableArray) && !is_null($tableArray)) {
      $dao = new TableNameDaoImpl();

      $gcmArray = array();
      foreach ($tableArray as $tableItem) {

        $table = new TableName();
        $table->setNumber($tableItem->number);
        $table->setXPosInDpi($tableItem->xPosInDpi);
        $table->setYPosInDpi($tableItem->yPosDpi);
        $table->setMapPageNumber($tableItem->mapPageNumber);
        $table->setTime($tableItem->tableTime);
        $table->setFunctionary($tableItem->waiterAlterTable->id);
        if(isset($tableItem->clientTemp))
          $table->setClientTemp($tableItem->clientTemp);
        if($tableItem->show){
          $table->setShow(1);
        }
        else{
          $table->setShow(0);
        }

        $clientOk = true;
        if(isset($tableItem->client)){
          $table->setClient($tableItem->client->id);
          $objTableClient = $dao->listOneByClient($table->getClient());

          if(count($objTableClient) > 0){
            if($objTableClient[0]->number != $table->getNumber()){
              $response['code'] = 7;
              $clientOk = false;
            }
          }
        }

        if($clientOk){
          $obj = $dao->listOneByNumber($table->getNumber());

          if(count($obj) > 0){
            $table->setId($obj[0]->id);

            if(strtotime($table->getTime()) >=  strtotime($obj[0]->time)){
              $success = $dao->updateFromJson($table);
            }
            else{
              $success = false;
              $response['code'] = 1;
            }

            if($success){
              array_push($gcmArray, $table);
              $response['code'] = 1;
            }
          } else {
            $id = $dao->addFromJson($table);
            if($id > 0){
              array_push($gcmArray, $table);
              $response['code'] = 1;
            }
          }
        }
      }

      if(count($tableArray) > 0 && $response['code'] == 1){
        $pushSocket = new SendPushSocket();
        $pushSocket->setImeiException($push->imei);
        $pushSocket->sendPush($tableArray, 2);
      }
    }

    if($response['code'] != 1){
      $resultJson['message'] = "Erro ao enviar a mesa";
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
