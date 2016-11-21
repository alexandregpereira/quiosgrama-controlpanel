<?php

/**
 * @author Alexandre Pereira
 */

namespace App\WS;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\WS\Core;
use App\WS\SendPushSocket;
use App\Model\ProductRequest;
use App\Model\Complement;
use App\Model\Request;
use App\Model\Bill;
use App\Model\Product;
use App\Model\TableName;
use App\WS\SendPush;
use App\Db\Dao\Impl\ProductRequestDaoImpl;
use App\Db\Dao\Impl\TableNameDaoImpl;
use App\Db\Dao\Impl\RequestDaoImpl;
use App\Db\Dao\Impl\ProductDaoImpl;
use App\Db\Dao\Impl\ComplementDaoImpl;
use App\Db\Dao\Impl\BillDaoImpl;
use App\Utility\Utilities;

// Define API response codes and their related HTTP response
$responseCode = array(
  1 => array('HTTP Response' => 200, 'Message' => 'Success'),
  6 => array('HTTP Response' => 201, 'Message' => 'Error'),
  7 => array('HTTP Response' => 202, 'Message' => 'Error'),
  8 => array('HTTP Response' => 203, 'Message' => 'Object Rejected'),
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
    $prodReqArray = $push->list;
    if(is_array($prodReqArray)) {
    	if(isset($prodReqArray)){
    	  foreach ($prodReqArray as $prodReqItem) {
           if(isset($prodReqItem)){
      		    $request = new Request();
              $request->setId($prodReqItem->request->id);
              $request->setTime($prodReqItem->request->requestTime);
              $request->setBill($prodReqItem->request->bill->id);
              $request->setWaiter($prodReqItem->request->waiter->id);

              $bill = new Bill();
              $bill->setId($prodReqItem->request->bill->id);
              if(isset($prodReqItem->request->bill->openTime))
                $bill->setOpenTime($prodReqItem->request->bill->openTime);
              if(isset($prodReqItem->request->bill->closeTime))
                $bill->setCloseTime($prodReqItem->request->bill->closeTime);
              if(isset($prodReqItem->request->bill->paidTime))
                $bill->setPaidTime($prodReqItem->request->bill->paidTime);
              if(isset($prodReqItem->request->bill->waiterOpenTable))
                $bill->setWaiterOpenTable($prodReqItem->request->bill->waiterOpenTable->id);
              if(isset($prodReqItem->request->bill->waiterCloseTable))
                $bill->setWaiterCloseTable($prodReqItem->request->bill->waiterCloseTable->id);
              $bill->setTable($prodReqItem->request->bill->table->number);
              $bill->setTime($prodReqItem->request->bill->billTime);
              if(isset($prodReqItem->request->bill->servicePaid)){
                if(!$prodReqItem->request->bill->servicePaid){
                   $bill->setServicePaid(0);
                }
                else{
                  $bill->setServicePaid(1);
                }
              }
              else {
                $bill->setServicePaid(0);
              }

              $table = new TableName();
              $table->setNumber($prodReqItem->request->bill->table->number);
              $table->setXPosInDpi($prodReqItem->request->bill->table->xPosInDpi);
              $table->setYPosInDpi($prodReqItem->request->bill->table->yPosDpi);
              $table->setMapPageNumber($prodReqItem->request->bill->table->mapPageNumber);
              $table->setTime($prodReqItem->request->bill->table->tableTime);
              if(isset($prodReqItem->request->bill->table->clientTemp))
                $table->setClientTemp($prodReqItem->request->bill->table->clientTemp);
              if(isset($prodReqItem->request->bill->table->waiterAlterTable) && $prodReqItem->request->bill->table->waiterAlterTable != null)
                $table->setFunctionary($prodReqItem->request->bill->table->waiterAlterTable->id);
              if(isset($prodReqItem->request->bill->table->client) && $prodReqItem->request->bill->table->client != null)
                $table->setClient($prodReqItem->request->bill->table->client->id);
              $table->setShow($prodReqItem->request->bill->table->show);

        			$complementDao = new ComplementDaoImpl();
              $obj = $complementDao->listOneByDescription($prodReqItem->complement->description);
              $complement = new Complement();
              if(count($obj) > 0){
                $complement->setId($obj[0]->id);
              }

              $complement->setDescription($prodReqItem->complement->description);
              $complement->setPrice($prodReqItem->complement->price);
              if(isset($prodReqItem->complement->drawable))
                $complement->setDrawable($prodReqItem->complement->drawable);

              if(is_null($complement->getId())){
                $idComplement = $complementDao->add($complement);
                $complement->setId($idComplement);
              }

              $productDao = new ProductDaoImpl();
              $obj = $productDao->listOneByCode($prodReqItem->product->code);
              $product = new Product();
              if(count($obj) > 0){
                $product->setId($obj[0]->id);
              }

              $product->setCode($prodReqItem->product->code);
              $product->setName($prodReqItem->product->name);
              if(isset($prodReqItem->product->popularity))
                $product->setPopularity($prodReqItem->product->popularity);
              if(isset($prodReqItem->product->description))
                $product->setDescription($prodReqItem->product->description);
              $product->setPrice($prodReqItem->product->price);
              if(isset($prodReqItem->product->type))
                $product->setProductType($prodReqItem->product->type->id);

              $prodReq = new ProductRequest();
              $prodReqItem->quantity = $prodReqItem->product->quantity;
              $prodReq->setQuantity($prodReqItem->quantity);
              $prodReq->setRequest($prodReqItem->request->id);
              $prodReq->setProduct($product->getId());
              $prodReq->setComplement($complement->getId());
              if(!$prodReqItem->valid){
  	             $prodReq->setValid(0);
              }
              else{
                $prodReq->setValid(1);
              }
              if(isset($prodReqItem->transferRoute))
                $prodReq->setTransferRoute($prodReqItem->transferRoute);
              if(isset($prodReqItem->productRequestTime))
                $prodReq->setProductRequestTime($prodReqItem->productRequestTime);
              if(isset($prodReqItem->status))
                $prodReq->setStatus($prodReqItem->status);

              $insert = true;
              $tableDao = new TableNameDaoImpl();
              $obj = $tableDao->listOneByNumber($prodReqItem->request->bill->table->number);

              if(count($obj) > 0){
                $table->setId($obj[0]->id);
                if(strtotime($table->getTime()) >=  strtotime($obj[0]->time)){
                  $insert = $tableDao->updateFromJson($table);
                }
              }
              else{
                $id = $tableDao->addFromJson($table);
                if($id <= 0)
                  $insert = false;
              }

              $gcmBillArray = array();
        			if($insert){
                $billDao = new BillDaoImpl();
                $obj = $billDao->listOne($bill->getId());
                if(count($obj) <= 0){
                  $obj = $billDao->listByNumber($bill->getTable());
                  if(count($obj) > 0){
                    $bill->setId($obj[0]->id);
                    $request->setBill($obj[0]->id);
                  }
                }

                if(count($obj) > 0){
                  if(strtotime($bill->getTime()) >= strtotime($obj[0]->bill_time)){
                    $insert = $billDao->update($bill);
                  }
                }
                else{
                  $billDao->add($bill);
                  $obj = $billDao->listOne($bill->getId());
                  if(count($obj) > 0){
                    $insert = true;
                    array_push($gcmBillArray, $bill);
                  }
                  else{
                    $insert = false;
                  }
                }
        			}
              else{
                error_log(print_r("SendProductRequest: Erro ao gravar a mesa", TRUE));
              }

        			if($insert){
                $requestDao = new RequestDaoImpl();
                if(count($requestDao->listOne($request->getId())) > 0){
                  $insert = $requestDao->update($request);
                }
                else{
                  $insert = $requestDao->add($request);
                }
        			}
              else{
                error_log(print_r("SendProductRequest: Erro ao gravar a bill", TRUE));
              }

        			if($prodReq->getQuantity() > 0 && $insert){
                  $prodRequestDao = new ProductRequestDaoImpl();
                  $obj = $prodRequestDao->listOneComposer($prodReq);
                  if(count($obj) > 0){
                    if(strtotime($prodReq->getProductRequestTime()) >= strtotime($obj[0]->product_request_time)){
                      $prodReq->setId($obj[0]->id);

                      $insert = $prodRequestDao->update($prodReq);
                      if($insert){
                        $response['code'] = 1;
                      }
                      else{
                        error_log("SendProductRequest: Erro ao atualizar o product request\n" . print_r($prodReq, TRUE));
                        error_log("JSON: " . $json);
                      }
                    }
                    else{
                      $response['code'] = 8;
                    }
                  }
                  else{
                    $id = $prodRequestDao->add($prodReq);
                    if($id > 0){
                      $response['code'] = 1;
                    }
                    else{
                      error_log("SendProductRequest: Erro ao inserir o product request\n" . print_r($prodReq, TRUE));
                      error_log("JSON: " . $json);
                    }
                  }
        			}
              else{
                error_log("SendProductRequest: Erro ao inserir o request\n" . print_r($request, TRUE));
                error_log("JSON: " . $json);
              }
    		    }
        }
      }

      if(count($prodReqArray) > 0 && $response['code'] == 1){
        $pushSocket = new SendPushSocket();
        $pushSocket->setImeiException($push->imei);
        $pushSocket->sendPush($prodReqArray, 1);
      }
    }

    if($response['code'] != 1 && $response['code'] != 8){
      $resultJson['message'] = "Erro ao enviar o pedido";
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
