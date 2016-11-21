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

class DestinationDaoImpl implements GenericDaoInterface {

    public function add($object) {
      $dados = array($object->getName(), $object->getIconName());
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".destination (name, icon_name) values(?, ?);");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $con->lastInsertId("id");
    }

    public function listAll() {
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".destination;");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOne($id) {

    }

    public function listOneByName($name) {
      $dados = array($name);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".destination WHERE LOWER(`name`) = ?;");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($id) {

    }

    public function update($object) {
      $dados = array($object->getName(), $object->getIconName(), $object->getId());
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".destination SET name = ?, icon_name = ? WHERE id = ?;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $return[0] == '00000' && $return[1] == '';
    }

}

?>
