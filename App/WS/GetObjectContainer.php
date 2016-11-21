<?php

/**
 * @author Alexandre Pereira
 */

namespace App\WS;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\WS\Core;
use App\WS\BuildProductRequestJson;
use App\Model\ProductRequest;
use App\Model\Complement;
use App\Model\Request;
use App\Model\Bill;
use App\Model\Product;
use App\Model\TableName;
use App\WS\SendPush;
use App\Db\Dao\Impl\ProductRequestDaoImpl;
use App\Db\Dao\Impl\TableNameDaoImpl;
use App\Db\Dao\Impl\ProductDaoImpl;
use App\Db\Dao\Impl\ComplementDaoImpl;
use App\Db\Dao\Impl\ProductTypeDaoImpl;
use App\Db\Dao\Impl\PoiDaoImpl;
use App\Db\Dao\Impl\ComplementTypeDaoImpl;
use App\Db\Dao\Impl\FunctionaryDaoImpl;
use App\Db\Dao\Impl\ClientDaoImpl;
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

$response['code'] = 1;

$json = file_get_contents('php://input');
$push = json_decode($json);
$core = new Core();

if(isset($push->imei) && $core->isValidImei($push->imei)) {

  $kioskDao = new KioskDaoImpl();
  $kisokObj = $kioskDao->listOneKiosk();

  if(count($kisokObj) > 0){
    if(isset($kisokObj[0]->licence) && !is_null($kisokObj[0]->licence)){
      $kioskJsonContainer = $core->validateLicence(json_encode($kisokObj[0]));
      $response['code'] = $kioskJsonContainer->code;
      if(isset($kioskJsonContainer->message) && !is_null($kioskJsonContainer->message)){
        $resultJson["message"] = $kioskJsonContainer->message;
      }

      if($response['code'] == 1){
        $success = $kioskDao->updateExpirationTime($kioskJsonContainer->kiosk);
        if(!$success){
          $response['code'] = 6;
          $resultJson["message"] = "Falha ao atualizar as informações da licença";
        }
      }
      else{
        $success = $kioskDao->invalidateLicence($kisokObj[0]->licence);
        if(!$success){
          $resultJson["message"] = "Falha ao atualizar as informações da licença";
        }
      }
    }
    else{
      $response['code'] = 6;
      $resultJson['message'] = $kisokObj[0]->name . ", a licença não esta cadastrada";
    }
  }
  else{
    $response['code'] = 6;
    $resultJson["message"] = "O Quiosque não esta cadastrado no sistema";
  }

  if($response['code'] == 1){
    $functionaryDao = new FunctionaryDaoImpl();
    $productDao = new ProductDaoImpl();
    $productTypeDao = new ProductTypeDaoImpl();
    $tableDao = new TableNameDaoImpl();
    $productRequestDao = new ProductRequestDaoImpl();
    $poiDao = new PoiDaoImpl();
    $complementDao = new ComplementDaoImpl();
    $clientDao = new ClientDaoImpl();
    $complementTypeDao = new ComplementTypeDaoImpl();

    $functionaryList = $functionaryDao->listAll();
    $productList = $productDao->listAllWithTax();
    $productTypeList = $productTypeDao->listAll();
    $tableList = $tableDao->listAll();
    $productRequestList = $productRequestDao->listAllToday();
    $poiList = $poiDao->listAll();
    $complementList = $complementDao->listAll();
    $clientList = $clientDao->listAll();

    //		buildComplementType(complementList);
    if($complementList != null){
      $complementObjectList = array();
      foreach ($complementList as $complement) {
        $complementObject = new Complement($complement);
        $complementTypeList = $complementTypeDao->listAllByComplementObject($complementObject);
    		if($complementTypeList != null){
          $typeArray = array();
    			foreach ($complementTypeList as $complementType) {
            array_push($typeArray, $complementType->product_type);
    			}
          $complementObject->setTypeArray($typeArray);
    		}
        array_push($complementObjectList, $complementObject);
      }
    }

    $tableObjectArray = array();
    foreach ($tableList as $tableData) {
      $tableObject = new TableName($tableData);
      array_push($tableObjectArray, $tableObject);
    }

    $functionaryJsonArray = $core->buildFunctionaryJsonArray($functionaryList);
    if($functionaryJsonArray != null)
      $container['functionaryList'] = $functionaryJsonArray;

    if(isset($complementObjectList)){
      $complementJsonArray = $core->buildComplementJsonArray($complementObjectList);
      if($complementJsonArray != null)
        $container['complementList'] = $complementJsonArray;
    }

    $productJsonArray = $core->buildProductJsonArray($productList);
    if($productJsonArray != null)
      $container['productList'] = $productJsonArray;

    $productTypeJsonArray = $core->buildProductTypeJsonArray($productTypeList);
    if($productTypeJsonArray != null)
      $container['productTypeList'] = $productTypeJsonArray;

    $tableJsonArray = $core->buildTableJsonArray($tableObjectArray);
    if($tableJsonArray != null)
      $container['tableList'] = $tableJsonArray;

    $buildProdReqJson = new BuildProductRequestJson();
    $billJsonArray = $buildProdReqJson->buildBill($productRequestList);
    if($billJsonArray != null)
      $container['billList'] = $billJsonArray;

    $requestJsonArray = $buildProdReqJson->buildRequest($productRequestList);
    if($requestJsonArray != null)
      $container['requestList'] = $requestJsonArray;

    $productRequestJsonArray = $buildProdReqJson->build($productRequestList);
    if($productRequestJsonArray != null)
      $container['productRequestList'] = $productRequestJsonArray;

    $poiJsonArray = $core->buildPoiJsonArray($poiList);
    if($poiJsonArray != null)
      $container['poiList'] = $poiJsonArray;

    $clientJsonArray = $core->buildClientJsonArray($clientList);
    if($clientJsonArray != null)
      $container['clientList'] = $clientJsonArray;

    $kioskJson['name'] = $kisokObj[0]->name;
    $kioskJson['address'] = $kisokObj[0]->address;
    $kioskJson['licence'] = $kisokObj[0]->licence;

    $resultJson['kiosk'] = $kioskJson;
    $resultJson['container'] = $container;
  }

  $response['status'] = $responseCode[ $response['code'] ]['HTTP Response'];
  // Return Response to browser
  $json = json_encode($resultJson);
  $core->deliverResponse($response, $json);
}
else{
  $response['code'] = 7;

  $resultJson['message'] = "Dispositivo não cadastrado, acesse a função zombie no menu para ler o QrCode do estabelecimento";
  $json = json_encode($resultJson);

  $response['status'] = $responseCode[ $response['code'] ]['HTTP Response'];
  // Return Response to browser
  $core->deliverResponse($response, $json);
}

?>
