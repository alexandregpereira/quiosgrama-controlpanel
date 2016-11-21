<?php

/**
 * @author Alexandre Pereira
 */

namespace App\WS;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\WS\Core;
use App\WS\SendPushSocket;
use App\Model\Poi;
use App\WS\SendPush;
use App\Db\Dao\Impl\PoiDaoImpl;
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
    $poiArray = $push->list;
    if(is_array($poiArray)) {
      $dao = new PoiDaoImpl();
      $gcmArray = array();
      $success = false;
      foreach ($poiArray as $poiItem) {

        $poi = new Poi();
        $poi->setId($poiItem->idPoi);
        $poi->setXPosInDpi($poiItem->xPosDpi);
        $poi->setYPosInDpi($poiItem->yPosDpi);
        $poi->setMapPageNumber($poiItem->mapPageNumber);
        $poi->setImage($poiItem->image);
        $poi->setTime($poiItem->poiTime);
        $poi->setFunctionary($poiItem->waiterAlterPoi->id);

        $obj = $dao->listOne($poi->getId());

        if(count($obj) > 0){
          if(strtotime($poi->getTime()) >=  strtotime($obj[0]->time)){
            $success = $dao->updateFromJson($poi);
          }
          else{
            $success = false;
            $response['code'] = 1;
          }

          if($success){
            $response['code'] = 1;
          }
        } else {
          $id = $dao->add($poi);
          if($id > 0){
            $success = true;
            $response['code'] = 1;
          }
        }
      }

      if(count($poiArray) > 0 && $success){
        $pushSocket = new SendPushSocket();
        $pushSocket->setImeiException($push->imei);
        $pushSocket->sendPush($poiArray, 3);
      }
    }

    if($response['code'] != 1){
      $resultJson['message'] = "Erro ao enviar o POI";
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
