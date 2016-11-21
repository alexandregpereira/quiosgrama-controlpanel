<?php

/**
 * @author Jean Souza
 */

namespace App\WebSocket\Sockets;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Db\Dao\Impl\RequestDaoImpl;
use App\Db\Dao\Impl\ProductRequestDaoImpl;

class RequestsPane {

  private $newRequests;
  private $newRequestsComplete;
  private $newRequestsCount;

  private function verifyNewRequests() {
    $dao = new RequestDaoImpl();
    $newRequestsTmp = $dao->listNewRequests(5);
    $newRequestsCompleteTmp = $dao->listNewRequests();
    $newRequestsCountTmp = $dao->countNewRequests();

    if((serialize($this->newRequests) != serialize($newRequestsTmp)) || (serialize($this->newRequestsComplete) != serialize($newRequestsCompleteTmp)) || (serialize($this->newRequestsCount) != serialize($newRequestsCountTmp))) {
      $this->newRequests = $newRequestsTmp;
      $this->newRequestsComplete = $newRequestsCompleteTmp;
      $this->newRequestsCount = $newRequestsCountTmp;
      return true;
    } else {
      return false;
    }
  }

  public function load() {
    $methods = array();
    $values = array();

    $send = false;

    if($this->verifyNewRequests()) {

      $newRequestsReturn = array();
      $newRequestsCompleteReturn = array();

      foreach($this->newRequests as $k => $v) {
        $currentDate = strtotime(date('M j, Y g:i:s A'));
        $requestTime = strtotime($v->time);
        $diffMinutes = round(($currentDate - $requestTime) / 60);

        $newRequestsReturn[$k] = clone $v;
        $newRequestsReturn[$k]->diff_minutes = $diffMinutes;
      }

      foreach($this->newRequestsComplete as $k => $v) {
        $currentDate = strtotime(date('M j, Y g:i:s A'));
        $requestTime = strtotime($v->time);
        $diffMinutes = round(($currentDate - $requestTime) / 60);

        $newRequestsCompleteReturn[$k] = clone $v;
        $newRequestsCompleteReturn[$k]->diff_minutes = $diffMinutes;
      }

      array_push($methods, "updateRequestsPane");
      array_push($values, array($newRequestsReturn, $newRequestsCompleteReturn, $this->newRequestsCount));

      $send = true;
    }

    $messageArray = array("methods" => $methods, "values" => $values, "key" => "RequestsPane");

    if($send) return $messageArray;
    else return array();
  }

  public function getRequest($id) {
    $dao = new RequestDaoImpl();
    $daoProductRequest = new ProductRequestDaoImpl();

    $request = $dao->listCompleteRequest($id);
    $products = $daoProductRequest->listAllByRequest($id);

    $return = array();

    $return["request"] = count($request) > 0 ? $request[0] : array();
    $return["products"] = count($products) > 0 ? $products : array();

    return $return;
  }

  public function markRequestAsViewed($id) {
    $dao = new RequestDaoImpl();
    $dao->markRequestAsViewed($id);
  }
}
