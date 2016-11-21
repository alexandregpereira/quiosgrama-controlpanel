<?php

/**
 * @author Alexandre Pereira
 */

namespace App\WS;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Utility\Utilities;
use App\Db\Dao\Impl\DeviceDaoImpl;
use App\Db\Dao\Impl\KioskDaoImpl;

class SendPushSocket {

  private $imeiException;

  public function sendPush($array, $resultCode) {
     try{
       $deviceDao = new DeviceDaoImpl();
       $deviceArray = $deviceDao->listAllWithRegistration();

       $kioskDaoImpl = new KioskDaoImpl();
       $kioskObj = $kioskDaoImpl->listOneKiosk();

       if(count($kioskObj) > 0 && !is_null($kioskObj[0]->licence)){
         if($deviceArray != null && count($deviceArray) > 0){
           $deviceJsonArray = array();
           foreach ($deviceArray as $device) {
             $insert = true;
             if(!is_null($this->imeiException)){
               if($device->imei == $this->imeiException){
                 $insert = false;
               }
             }

             if($insert){
               $deviceJson['imei'] = $device->imei;
               $deviceJson['registrationId'] = $device->registration_id;
               $deviceJson['ip'] = $device->ip;
               array_push($deviceJsonArray, $deviceJson);
             }
           }

           $dataObj['list'] = $array;
           $dataObj['resultCode'] = $resultCode;
           $dataObj['licence'] = $kioskObj[0]->licence;

           $pushObj['deviceList'] = $deviceJsonArray;
           $pushObj['data'] = $dataObj;

           $pushJson = json_encode($pushObj);

           $ch = curl_init('http://127.0.0.1:8080/Quioservice/sync/sendPush');
           curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
           curl_setopt($ch, CURLOPT_POSTFIELDS, $pushJson);
           curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           curl_setopt($ch, CURLOPT_HTTPHEADER, array(
               'Content-Type: application/json',
               'Content-Length: ' . strlen($pushJson),
               'Accept: application/json'
               )
           );

           $result = curl_exec($ch);
         }
       }
       else{
         error_log("SendPushSocket: Failed to obtain the licence");
       }
     } catch (Exception $e) {
	      error_log(print_r("\nSend to Push Server Failed\n", TRUE));
     }
   }

   public function setImeiException($imeiException) {
     $this->imeiException = $imeiException;
   }
 }


 ?>
