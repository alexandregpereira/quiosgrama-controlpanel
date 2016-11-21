<?php

/**
 * @author Alexandre Pereira
 */

namespace App\Db\Dao\Impl;

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Db\Dao\GenericDaoInterface;
use App\Db\Connection\ConnectionFactory;
use App\Model\User;
use \PDO;

class KioskDaoImpl implements GenericDaoInterface {

    public function add($object) {

    }

    public function listAll(){}

    public function listOne($id){
      $dados = array($id);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".kiosk WHERE id = ?;");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneKiosk(){
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".kiosk LIMIT 1;");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneKioskValidLicence(){
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT valid_licence FROM " . Constants::SCHEMA_MAIN . ".kiosk LIMIT 1;");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listLicence($id){
      $dados = array($id);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT licence FROM " . Constants::SCHEMA_MAIN . ".kiosk WHERE id = ?;");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneByLicence($licence){
      $dados = array($licence);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".kiosk WHERE licence = ?;");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($id){}

    public function updateExpirationTime($object){
      $dados = array($object->expiration_time, $object->licence);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".kiosk SET expiration_time = ?, valid_licence = 1 WHERE licence = ?;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $return[0] == '00000' && $return[1] == '';
    }

    public function invalidateLicence($licence){
      $dados = array($licence);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".kiosk SET valid_licence = 0 WHERE licence = ?;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $return[0] == '00000' && $return[1] == '';
    }

    public function update($object){}

    public function updateFromJson($object){
      $dados = array($object->name, $object->cnpj, $object->address, $object->licence);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".kiosk SET name = ?, cnpj = ?, address = ? WHERE licence = ?;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $return[0] == '00000' && $return[1] == '';
    }
}

?>
