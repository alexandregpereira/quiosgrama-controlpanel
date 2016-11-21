<?php

/**
 * @author Alexandre Pereira
 */

namespace App\WS;

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Utility\Utilities;
use App\Db\Dao\Impl\DeviceDaoImpl;
use App\Db\Dao\Impl\KioskDaoImpl;

class SendPush{

   const LIMIT_LIST = 3;

   private $imeiException;

   function __construct() {

   }

   public function sendPush($array, $resultCode){
     //$util = new Utilities();
     //$util->printArray($array);
     //$jsonT = json_encode($array);
     //echo $jsonT;
     try{
       if(isset($array) && count($array) > 0){
          $map = self::divideListToSend($array);

          $deviceDao = new DeviceDaoImpl();
          $deviceArray = $deviceDao->listAllWS();

          $kioskDaoImpl = new KioskDaoImpl();
          $kioskObj = $kioskDaoImpl->listOneKiosk();

          if(count($kioskObj) > 0 && !is_null($kioskObj[0]->licence)){
            if(isset($deviceArray) && count($deviceArray) > 0){
              $registrationIdArray = array();
              foreach ($deviceArray as $device) {
                if(isset($device->registrationId) && $device->imei != $this->imeiException){
                  array_push($registrationIdArray, $device->registrationId);
                }
              }
            }

            foreach ($map as $entry) {
              if(isset($registrationIdArray) && count($registrationIdArray) > 0){
                $gcmBodyJson['registration_ids'] = $registrationIdArray;

                //$jsonT = json_encode($entry);
                //echo $jsonT;

                $data['list'] = $entry;
                $data['resultCode'] = $resultCode;
                $data['licence'] = $kioskObj[0]->licence;
                $gcmBodyJson['data'] = $data;

      					$json = json_encode($gcmBodyJson);


                $ch = curl_init('https://android.googleapis.com/gcm/send');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($json),
                    'Accept: application/json',
                    'Authorization: Key=AIzaSyCa66MZHNyRsGevD-LNgiXkWTDlxkdKawc'
                    )
                );

                curl_exec($ch);
              }
            }
          }
          else{
            error_log("SendPush: Failed to obtain the licence");
          }
        }
      } catch (Exception $e) {
        error_log(print_r("\nSend to Push Server Failed\n", TRUE));
      }
   }

   private function divideListToSend($array){
     $map = array();
     $arrayTemp = array();
     $m = 1;

    // $jsonT = json_encode($array);
    // echo $jsonT;

     foreach ($array as $obj) {
       if(count($arrayTemp) == 3){
          $map[$m++] = $arrayTemp;
          unset($arrayTemp);
          $arrayTemp = array();
       }

       array_push($arrayTemp, $obj);
     }

     if(count($arrayTemp) > 0){
       $map[$m++] = $arrayTemp;
     }

     return $map;
   }

   public function setImeiException($imeiException) {
     $this->imeiException = $imeiException;
   }
 }

?>
