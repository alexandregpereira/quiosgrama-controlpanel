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

class ClientDaoImpl implements GenericDaoInterface {

  public function add($object) {
    $dados = array($object->getName(), $object->getCpf(), $object->getPhone(), $object->getTempFlag(), $object->getPresentFlag());
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".client (name, cpf, phone, temp_flag, present_flag) values(?, ?, ?, ?, ?);");
    $stmt->execute($dados);
    return $con->lastInsertId("id");
  }

  public function listAll() {
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".client;");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function listAllWithoutTable($id) {
    $dados = array($id);
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".client where isnull(`table`) or `table` = ?;");
    $stmt->execute($dados);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function listAllWS() {
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT cpf, id, name, phone,
      present_flag as present,
      case present_flag
      when 0 then 'false'
      when 1 then 'true'
      else 'false'
      end as presentFlag,
      temp_flag as temp,
      case temp_flag
      when 0 then 'false'
      when 1 then 'true'
      else 'false'
      end as tempFlag FROM " . Constants::SCHEMA_MAIN . ".client;");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOne($id) {
      $dados = array($id);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".client WHERE id = ?;");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneByCpf($cpf) {
      $dados = array($cpf);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".client WHERE cpf = ?;");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($id) {
      $dados = array($id);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".client WHERE id = ?;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      return $return[0] == '00000' && $return[1] == '';
    }

    public function update($object) {
      $dados = array($object->getName(), $object->getCpf(), $object->getPhone(), $object->getTempFlag(), $object->getPresentFlag(), $object->getId());
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".client SET name = ?, cpf = ?, phone = ?, temp_flag = ?, present_flag = ? WHERE id = ?;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      return $return[0] == '00000' && $return[1] == '';
    }

    public function updateTable($table, $id) {
      $dados = array($table, $id);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".client SET `table` = ? WHERE id = ?;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      return $return[0] == '00000' && $return[1] == '';
    }

  }

  ?>
