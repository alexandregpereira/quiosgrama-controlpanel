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

class AmountDaoImpl implements GenericDaoInterface {

    public function add($object) {
        $dados = array($object->getId(), $object->getValue(), $object->getPaidMethod(), $object->getBill());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".amount (id, value, paid_method, bill) values(?, ?, ?, ?);");
        $stmt->execute($dados);
        return $con->lastInsertId("id");
    }

    public function listAll(){}

    public function listOne($id){
      $dados = array($id);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".amount WHERE id = ?;");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listReport($date){
      $dados = array($date);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT value, paid_method, service_paid FROM " . Constants::SCHEMA_MAIN . ".amount
                              LEFT outer join " . Constants::SCHEMA_MAIN . ".bill b on b.id = bill
                              where DATE(paid_time) = ?;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($id){}

    public function update($entity){}
}

?>
