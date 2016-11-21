<?php

/**
 * @author Alexandre Pereira
 */

namespace App\WS;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\WS\Core;
use App\Db\Dao\Impl\KioskDaoImpl;
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
$kioskJson = json_decode($json);
$core = new Core();

if(isset($kioskJson->licence) && isset($kioskJson->name)){
  $kioskDao = new KioskDaoImpl();
  $kisokObj = $kioskDao->listOneByLicence($kioskJson->licence);
  if(count($kisokObj) > 0){
    $success = $kioskDao->updateFromJson($kioskJson);
    if($success){
      $kiosk['expiration_time'] = $kisokObj[0]->expiration_time;
      $kiosk['licence'] = $kisokObj[0]->licence;
      $result['kiosk'] = $kiosk;

      $now = time(); // or your date as well
      $expirationTime = strtotime($kisokObj[0]->expiration_time);
      $datediff = $expirationTime - $now;
      $days = floor($datediff/(60*60*24)) + 1;

      $response['code'] = 1;
      if($days > 1 && $days <= 6){
        $result["message"] = $kisokObj[0]->name . ", a licença expira em " . $days . " dias";
      }
      else if($days == 1){
        $result["message"] = $kisokObj[0]->name . ", a licença expira amanhã";
      }
      else if($days == 0){
        $result["message"] = $kisokObj[0]->name . ", a licença expira hoje";
      }
      else if($days < 0){
        $response['code'] = 7;
        $result["message"] = $kisokObj[0]->name . ", a licença expirou!";
      }
    }
    else{
      $result["message"] = "Informações incorretas, contate o admin do sistema";
    }
  }
  else{
    $result['message'] = $kioskJson->name . ", a licença esta inválida";
  }

  if(isset($result)){
    $result['code'] = $response['code'];
    $jsonResult = json_encode($result);
  }
}

$response['status'] = $responseCode[ $response['code'] ]['HTTP Response'];
// Return Response to browser
if(isset($jsonResult)){
  $core->deliverResponse($response, $jsonResult);
}
else{
  $core->deliverResponse($response);
}

?>
