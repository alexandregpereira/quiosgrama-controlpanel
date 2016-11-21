<?php

/**
 * @author Alexandre Pereira
 */

namespace App\WS;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;

class BuildProductRequestJson{

  public function build($prodReqArray){
    if(!is_null($prodReqArray) && count($prodReqArray) > 0){
      $prodReqJsonArray = array();
      foreach ($prodReqArray as $prodReqObject) {
        $requestJson['id'] = $prodReqObject->request;
        $prodReqJson['request'] = $requestJson;

        $productJson['code'] = $prodReqObject->code;
        $prodReqJson['product'] = $productJson;

        if(!is_null($prodReqObject->description)){
          $complementJson['description'] = $prodReqObject->description;
          $prodReqJson['complement'] = $complementJson;
        }
        else{
          $prodReqJson['complement'] = null;
        }

        $prodReqJson['id'] = $prodReqObject->id_product_request;
        $prodReqJson['quantity'] = $prodReqObject->quantity;
	      $prodReqJson['valid'] = $prodReqObject->valid_product_request == 1;
        $prodReqJson['transferRoute'] = $prodReqObject->transfer_route;
        $prodReqJson['productRequestTime'] = $prodReqObject->product_request_time;
        $prodReqJson['status'] = $prodReqObject->status;
        if(isset($prodReqObject->print_time)){
          $prodReqJson['printTime'] = $prodReqObject->print_time;
        }

        if(isset($prodReqObject->printing)){
          $prodReqJson['printing'] = $prodReqObject->printing;
        }

        array_push($prodReqJsonArray, $prodReqJson);
      }

      return $prodReqJsonArray;
    }
  }

  public function buildBill($billArray){
    if(!is_null($billArray) && count($billArray) > 0){
      $billJsonArray = array();
      foreach ($billArray as $billObject) {
        $billJson['id'] = $billObject->id_bill;
        $billJson['openTime'] = $billObject->open_time;
        $billJson['closeTime'] = $billObject->close_time;
        $billJson['paidTime'] = $billObject->paid_time;

        $waiterOpenTableJson['id'] = $billObject->waiter_open_table;
        $billJson['waiterOpenTable'] = $waiterOpenTableJson;

        if($billObject->waiter_close_table != null){
          $waiterCloseTable['id'] = $billObject->waiter_close_table;
          $billJson['waiterCloseTable'] = $waiterCloseTable;
        }
        else{
          $billJson['waiterCloseTable'] = null;
        }

        $billJson['billTime'] = $billObject->bill_time;

        $tableJson['number'] = $billObject->table_number;

        $billJson['table'] = $tableJson;

        array_push($billJsonArray, $billJson);
      }

      return $billJsonArray;
    }
  }

  public function buildRequest($requestArray){
    if(!is_null($requestArray) && count($requestArray) > 0){
      $requestJsonArray = array();
      foreach ($requestArray as $requestObject) {
        $requestJson['id'] = $requestObject->id_request;
        $requestJson['requestTime'] = $requestObject->time;

        $billJson['id'] = $requestObject->bill;
        $requestJson['bill'] = $billJson;

        $waiterJson['id'] = $requestObject->functionary;
        $requestJson['waiter'] = $waiterJson;

        array_push($requestJsonArray, $requestJson);
      }

      return $requestJsonArray;
    }
  }

}

?>
