<?php

/**
* @author Jean Souza
*/

namespace App\Db\Dao\Impl;

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Db\Dao\GenericDaoInterface;
use App\Db\Connection\ConnectionFactory;
use App\Model\User;
use \PDO;

class DeviceDaoImpl implements GenericDaoInterface {

  public function add($object) {
    $dados = array($object->getImei());
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".device (imei) values(?);");
    $stmt->execute($dados);
    return $con->lastInsertId("id");
  }

  public function addFromJson($object) {
    $dados = array($object->getImei(), $object->getRegistrationId(), $object->getIp());
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".device (imei, registration_id, ip) values(?,?,?);");
    $stmt->execute($dados);
    return $con->lastInsertId("id");
  }

  public function listAll() {
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".device;");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function listAllWS() {
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT id, imei, registration_id as registrationId FROM " . Constants::SCHEMA_MAIN . ".device;");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function listAllWithRegistration() {
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".device WHERE ip IS NOT NULL or registration_id IS NOT NULL;");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function listOne($id) {
    $dados = array($id);
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".device WHERE id = ?;");
    $stmt->execute($dados);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function listOneWS($imei) {
    $dados = array($imei);
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".device WHERE imei = ?;");
    $stmt->execute($dados);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function listOneByImei($imei) {
    $dados = array($imei);
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".device WHERE imei = ? and exclusion_time IS NULL;");
    $stmt->execute($dados);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function remove($id) {
    $dados = array($id);
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".device WHERE id = ?;");
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    return $return[0] == '00000' && $return[1] == '';
  }

  public function update($object) {
    $dados = array($object->getImei(), $object->getId());
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".device SET imei = ? WHERE id = ?;");
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    return $return[0] == '00000' && $return[1] == '';
  }

  public function updateFromJson($object) {
    $dados = array($object->getRegistrationId(), $object->getIp(), $object->getExclusionTime(), $object->getId());
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".device SET registration_id = ?, ip = ?, exclusion_time = ? WHERE id = ?;");
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    return $return[0] == '00000' && $return[1] == '';
  }

  public function updateExclusionTime($object) {
    $dados = array($object->getExclusionTime(), $object->getId());
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".device SET exclusion_time = ? WHERE id = ?;");
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    return $return[0] == '00000' && $return[1] == '';
  }

}

?>
