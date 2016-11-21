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
use App\WS\SendPush;

class SendPushSocket {

  private $imeiException;

  public function sendPush($array, $resultCode) {
    $pushService = new SendPush();
    $pushService->setImeiException($this->imeiException);
    $pushService->sendPush($array, $resultCode);

   }

   public function setImeiException($imeiException) {
     $this->imeiException = $imeiException;
   }
 }


 ?>
