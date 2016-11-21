<?php

/**
 * @author Alexandre Pereira
 */

namespace App\WS;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Functionary;
use App\Db\Dao\Impl\FunctionaryDaoImpl;
use App\Db\Dao\Impl\ClientDaoImpl;
use App\Db\Dao\Impl\DeviceDaoImpl;
use App\Db\Dao\Impl\ProductDaoImpl;
use App\Db\Dao\Impl\ComplementDaoImpl;
use App\Db\Dao\Impl\KioskDaoImpl;

class Core {
  function deliverResponse($api_response, $json = null){

      $http_response_code = array(
          200 => 'OK',
          201 => 'Failed',
          202 => 'Client already associated',
          203 => 'Object Rejected',
          400 => 'Bad Request',
          401 => 'Unauthorized',
          403 => 'Forbidden',
          404 => 'Not Found'
      );

      header('HTTP/1.1 '.$api_response['status'].' '.$http_response_code[ $api_response['status'] ]);
      header('Content-Type: application/json; charset=utf-8');

      if($json != null){
        echo $json;
      }

      exit;

  }

  function deliverTextResponse($api_response, $json = null){
      $http_response_code = array(
          200 => 'OK',
          201 => 'Failed',
          202 => 'Client already associated',
          203 => 'Object Rejected',
          400 => 'Bad Request',
          401 => 'Unauthorized',
          403 => 'Forbidden',
          404 => 'Not Found'
      );

      header('HTTP/1.1 '.$api_response['status'].' '.$http_response_code[ $api_response['status'] ]);
      header('Content-Type: text/plan; charset=utf-8');

      if($json != null){
        echo $json;
      }

      exit;
  }

  public function isValidLicence(){
    $kioskDao = new KioskDaoImpl();
    $kisokObj = $kioskDao->listOneKioskValidLicence();

    if(count($kisokObj) > 0){
      if($kisokObj[0]->valid_licence == 1){
        return true;
      }
      else{
        return false;
      }
    }
    else{
      return false;
    }
  }

  public function validateLicence($json, $isLocal = false){
    try{
      if(!$isLocal){
        $url = Constants::LICENCE_VALIDATOR_URL;
      }
      else{
        $url = Constants::LOCAL_LICENCE_VALIDATOR_URL;
      }
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,2);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($json),
          'Accept: application/json',
          )
      );

      $result = curl_exec($ch);
      if(!is_null($result) && $result == ''){

        error_log("Licence Validator Failed: " . $url);
        if(!$isLocal){
          return self::validateLicence($json, true);
        }
        else{
          $kioskJson['code'] = 7;
          $kioskJson['message'] = "Falha ao validar a licen�a";
          return (object) $kioskJson;
        }
      }
      else{
        return json_decode($result);
      }
    } catch (Exception $e) {
      error_log("Licence Validator Failed Exception: " . $url);
      if(!$isLocal){
        return self::validateLicence($json, true);
      }
      else{
        $kioskJson['code'] = 7;
        $kioskJson['message'] = "Falha ao validar a licen�a";
        return (object) $kioskJson;
      }
    }
  }

  public function getAdminFlag($code){
    $adminCode = 23;
    $waiterCode = 22;

    if($code == $adminCode){
      return Functionary::ADMIN_CODE;
    }
    else{
      return Functionary::WAITER_CODE;
    }
  }

  public function isValidImei($imei){
    if(!is_null($imei)){
      $deviceDao = new DeviceDaoImpl();
      $obj = $deviceDao->listOneByImei($imei);
      if(count($obj) > 0 || $this->isValidCode($imei)){
        return true;
      }
      else {
        return false;
      }
    }

    return false;
  }

  public function isValidCode($code){
    $adminCode = 23;
    $waiterCode = 22;

    if($code == $adminCode || $code == $waiterCode){
      return true;
    }
    else{
      return false;
    }
  }

  public function buildZombie($zombieObj){
    $zombieJson['email'] = $zombieObj->email;
    $zombieJson['fullName'] = $zombieObj->full_name;
    $zombieJson['firstName'] = $zombieObj->first_name;
    $zombieJson['lastName'] = $zombieObj->last_name;
    $zombieJson['cpf'] = $zombieObj->cpf;
    $zombieJson['phone'] = $zombieObj->phone;
    $zombieJson['registrationId'] = $zombieObj->registration_id;

    return $zombieJson;
  }

  public function buildTableJsonArray($tableArray){
    if(isset($tableArray) && count($tableArray) > 0){
      $tableJsonArray = array();
      $functionaryDao = new FunctionaryDaoImpl();
      $clientDao = new ClientDaoImpl();
      foreach ($tableArray as $tableObject) {
        $tableJson['id'] = $tableObject->getId();
        $tableJson['number'] = $tableObject->getNumber();
        $tableJson['xPosInDpi'] = $tableObject->getXPosInDpi();
        $tableJson['yPosDpi'] = $tableObject->getYPosInDpi();
        $tableJson['mapPageNumber'] = $tableObject->getMapPageNumber();
        $tableJson['tableTime'] = $tableObject->getTime();
        $tableJson['clientTemp'] = $tableObject->getClientTemp();
        $tableJson['show'] = $tableObject->getShow() == 1;

        $idFunctionary = $tableObject->getFunctionary();
        //$functionary = functionaryDao->listOne($idFunctionary);
        if($idFunctionary != null){
          $functionaryJson['id'] = $idFunctionary;
          //$functionaryJson['name'] = $idFunctionary->name;
          //$functionaryJson['imei'] = $idFunctionary->id;
          $tableJson['waiterAlterTable'] = $functionaryJson;
        }
        else{
          $tableJson['waiterAlterTable'] = null;
        }

        $idClient = $tableObject->getClient();
        if(!is_null($idClient)){
          $clientJson['id'] = $idClient;
          $tableJson['client'] = $clientJson;
        }
        else{
          $tableJson['client'] = null;
        }

        array_push($tableJsonArray, $tableJson);
      }

      return $tableJsonArray;
    }
  }

  public function buildProductRequestJsonArray($prodReqArray){
    if(!is_null($prodReqArray) && count($prodReqArray) > 0){
      $prodReqJsonArray = array();
      $productDao = new ProductDaoImpl();
      $complementDao = new ComplementDaoImpl();
      foreach ($prodReqArray as $prodReqObject) {
        $requestJson['id'] = $prodReqObject->request;
        $prodReqJson['request'] = $requestJson;

        $productData = $productDao->listOne($prodReqObject->product);
        $productJson['code'] = $productData[0]->code;
        $prodReqJson['product'] = $productJson;

        $complementData = $complementDao->listOne($prodReqObject->complement);
        if(count($complementData) > 0){
          $complementJson['description'] = $complementData[0]->description;
          $prodReqJson['complement'] = $complementJson;
        }
        else{
          $prodReqJson['complement'] = null;
        }

        $prodReqJson['id'] = $prodReqObject->id;
        $prodReqJson['quantity'] = $prodReqObject->quantity;
	      $prodReqJson['valid'] = $prodReqObject->valid == 1;
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

  public function buildFunctionaryJsonArray($functionaryArray){
    if(!is_null($functionaryArray) && count($functionaryArray) > 0){
      $functionaryJsonArray = array();
      $deviceDao = new DeviceDaoImpl();
      foreach ($functionaryArray as $functionaryObject) {
        $functionaryJson['id'] = $functionaryObject->id;
        $functionaryJson['name'] = $functionaryObject->name;
        $functionaryJson['adminFlag'] = $functionaryObject->admin_flag;
        $device = $deviceDao->listOne($functionaryObject->device);
        if(count($device) > 0){
          $functionaryJson['imei'] = $device[0]->imei;
        }
        else{
          $functionaryJson['imei'] = null;
        }

        array_push($functionaryJsonArray, $functionaryJson);
      }

      return $functionaryJsonArray;
    }
  }

  public function buildComplementJsonArray($complementArray){
    if(!is_null($complementArray) && count($complementArray) > 0){
      $complementJsonArray = array();
      foreach ($complementArray as $complementObject) {
        $complementJson['id'] = $complementObject->getId();
        $complementJson['description'] = $complementObject->getDescription();
        $complementJson['price'] = $complementObject->getPrice();
        $complementJson['drawable'] = $complementObject->getDrawable();

        $typeArray = $complementObject->getTypeArray();
        if($typeArray != null){
          $typeJsonArray = array();
          foreach ($typeArray as $type) {
            $typeJson['id'] = $type;
            array_push($typeJsonArray, $typeJson);
          }

          $complementJson['typeSet'] = $typeJsonArray;
        }
        else {
          $complementJson['typeSet'] = null;
        }

        array_push($complementJsonArray, $complementJson);
      }

      return $complementJsonArray;
    }
  }

  public function buildProductJsonArray($productArray){
    if(!is_null($productArray) && count($productArray) > 0){
      $productJsonArray = array();
      foreach ($productArray as $productObject) {
        //$productJson['id'] = $productObject->id;
        $productJson['code'] = $productObject->code;
        $productJson['name'] = $productObject->name;
        $productJson['description'] = $productObject->description;
        $productJson['price'] = $productObject->price;
        $productJson['popularity'] = $productObject->popularity;
        $typeJson['id'] = $productObject->product_type;
        $productJson['type'] = $typeJson;

        if(isset($productObject->ncm) && !is_null($productObject->cnm)){
          $productJson['tax'] = self::buildTaxJson($productObject);
        }

        array_push($productJsonArray, $productJson);
      }

      return $productJsonArray;
    }
  }

  public function buildTaxJson($productObject){
    $taxJson['NCM'] = $productObject->ncm;
    $taxJson['vItem12741'] = $productObject->v_item12741;
    $taxJson['ICMS'] = self::buildIcmsJson($productObject);
    $taxJson['PIS'] = self::buildPisJson($productObject);
    $taxJson['COFINS'] = self::buildCofinsJson($productObject);

    return json_encode($taxJson);
  }

  public function buildIcmsJson($productObject){
    $icmsJson['description'] = $productObject->icms_description;
    $icmsJson['Orig'] = $productObject->orig;

    if(isset($productObject->icms_cst) && !is_null($productObject->icms_cst)){
      $icmsJson['CST'] = $productObject->icms_cst;
    }

    if(isset($productObject->csosn) && !is_null($productObject->csosn)){
      $icmsJson['CSOSN'] = $productObject->csosn;
    }

    if(isset($productObject->p_icms) && !is_null($productObject->p_icms)){
      $icmsJson['pICMS'] = $productObject->p_icms;
    }

    return $icmsJson;
  }

  public function buildPisJson($productObject){
    $pisJson['description'] = $productObject->pis_description;

    if(isset($productObject->pis_cst) && !is_null($productObject->pis_cst)){
      $pisJson['CST'] = $productObject->pis_cst;
    }

    if(isset($productObject->pis_v_bc) && !is_null($productObject->pis_v_bc)){
      $pisJson['vBC'] = $productObject->pis_v_bc;
    }

    if(isset($productObject->p_pis) && !is_null($productObject->p_pis)){
      $pisJson['pPIS'] = $productObject->p_pis;
    }

    if(isset($productObject->pis_q_bc_prod) && !is_null($productObject->pis_q_bc_prod)){
      $pisJson['qBCProd'] = $productObject->pis_q_bc_prod;
    }

    if(isset($productObject->pis_v_aliq_prod) && !is_null($productObject->pis_v_aliq_prod)){
      $pisJson['vAliqProd'] = $productObject->pis_v_aliq_prod;
    }

    return $pisJson;
  }

  public function buildCofinsJson($productObject){
    $cofinsJson['description'] = $productObject->cofins_description;

    if(isset($productObject->cofins_cst) && !is_null($productObject->cofins_cst)){
      $cofinsJson['CST'] = $productObject->cofins_cst;
    }

    if(isset($productObject->cofins_v_bc) && !is_null($productObject->cofins_v_bc)){
      $cofinsJson['vBC'] = $productObject->cofins_v_bc;
    }

    if(isset($productObject->p_cofins) && !is_null($productObject->p_cofins)){
      $cofinsJson['pCOFINS'] = $productObject->p_cofins;
    }

    if(isset($productObject->cofins_q_bc_prod) && !is_null($productObject->cofins_q_bc_prod)){
      $cofinsJson['qBCProd'] = $productObject->cofins_q_bc_prod;
    }

    if(isset($productObject->cofins_v_aliq_prod) && !is_null($productObject->cofins_v_aliq_prod)){
      $cofinsJson['vAliqProd'] = $productObject->cofins_v_aliq_prod;
    }

    return $cofinsJson;
  }

  public function buildProductTypeJsonArray($productTypeArray){
    if(!is_null($productTypeArray) && count($productTypeArray) > 0){
      $productTypeJsonArray = array();
      foreach ($productTypeArray as $productTypeObject) {
        $productTypeJson['buttonImage'] = $productTypeObject->button_image;
        $productTypeJson['id'] = $productTypeObject->id;
        $productTypeJson['name'] = $productTypeObject->name;
        $productTypeJson['colorId'] = $productTypeObject->color;
        $productTypeJson['imageInfo'] = $productTypeObject->icon_image;
        $productTypeJson['priority'] = $productTypeObject->priority;
        $productTypeJson['tabImage'] = $productTypeObject->tab_image;
        $productTypeJson['destination'] = $productTypeObject->destination;
        $productTypeJson['destinationName'] = $productTypeObject->destination_name;
        $productTypeJson['destinationIcon'] = $productTypeObject->icon_name;
        $productTypeJson['printerIp'] = $productTypeObject->printer_ip;

        array_push($productTypeJsonArray, $productTypeJson);
      }

      return $productTypeJsonArray;
    }
  }

  public function buildBillJsonArray($billArray){
    if(!is_null($billArray) && count($billArray) > 0){
      $billJsonArray = array();
      foreach ($billArray as $billObject) {
        $billJson['id'] = $billObject->id;
        $billJson['openTime'] = $billObject->open_time;
        $billJson['closeTime'] = $billObject->close_time;
        $billJson['paidTime'] = $billObject->paid_time;
        //$billJson['syncStatus'] = $billObject->sync_status;

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

  public function buildRequestJsonArray($requestArray){
    if(!is_null($requestArray) && count($requestArray) > 0){
      $requestJsonArray = array();
      foreach ($requestArray as $requestObject) {
        $requestJson['id'] = $requestObject->id;
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

  public function buildPoiJsonArray($poiArray){
    if(!is_null($poiArray) && count($poiArray) > 0){
      $poiJsonArray = array();
      foreach ($poiArray as $poiObject) {
        $poiJson['idPoi'] = $poiObject->id;
        $poiJson['name'] = $poiObject->name;
        $poiJson['xPosDpi'] = $poiObject->x_pos_in_dpi;
        $poiJson['yPosDpi'] = $poiObject->y_pos_in_dpi;
        $poiJson['image'] = $poiObject->image;
        $poiJson['mapPageNumber'] = $poiObject->map_page_number;
        $poiJson['poiTime'] = $poiObject->time;
        if($poiObject->functionary != null){
          $waiterJson['id'] = $poiObject->functionary;
          $poiJson['waiterAlterPoi'] = $waiterJson;
        }
        else{
          $poiJson['waiterAlterPoi'] = null;
        }

        array_push($poiJsonArray, $poiJson);
      }

      return $poiJsonArray;
    }
  }

  public function buildClientJsonArray($clientArray){
    if(!is_null($clientArray) && count($clientArray) > 0){
      $clientJsonArray = array();
      foreach ($clientArray as $clientObject) {
        $clientJson['id'] = $clientObject->id;
        $clientJson['name'] = $clientObject->name;
        $clientJson['cpf'] = $clientObject->cpf;
        $clientJson['phone'] = $clientObject->phone;
        $clientJson['tempFlag'] = $clientObject->temp_flag == 1;
        $clientJson['presentFlag'] = $clientObject->present_flag == 1;

        array_push($clientJsonArray, $clientJson);
      }

      return $clientJsonArray;
    }
  }
}

?>
