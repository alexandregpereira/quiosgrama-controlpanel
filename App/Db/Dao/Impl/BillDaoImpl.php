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

class BillDaoImpl implements GenericDaoInterface {

  public function add($object) {
    $dados = array($object->getId(), $object->getOpenTime(), $object->getCloseTime(), $object->getPaidTime(),
                    $object->getWaiterOpenTable(), $object->getWaiterCloseTable(), $object->getTable(), $object->getTime());
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".bill (id, open_time, close_time, paid_time, waiter_open_table, waiter_close_table, table_number, bill_time) values(?, ?, ?, ?, ?, ?, ?, ?);");
    $stmt->execute($dados);
    //$return = $stmt->errorInfo();
    //$utility = new Utilities();
    //$utility->printArray($return);
    //error_log(print_r($return, TRUE));
    return $con->lastInsertId("id");
  }

  public function listAll() {
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".bill;");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function listAllToday() {
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".bill where paid_time IS NULL and DATE(`bill_time`) = CURDATE();");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function listOne($id) {
    $dados = array($id);
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".bill WHERE id = ?;");
    $stmt->execute($dados);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function listByNumber($number) {
    $dados = array($number);
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".bill WHERE table_number = ? and paid_time is null and DATE(`bill_time`) = CURDATE();");
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    if(!($return[0] == '00000' && $return[1] == '')){
      error_log(print_r($return, TRUE));
    }
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function remove($id) {
    $dados = array($id);
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".bill WHERE id = ?;");
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    return $return[0] == '00000' && $return[1] == '';
  }

  public function update($object) {
    $dados = array($object->getOpenTime(), $object->getCloseTime(), $object->getPaidTime(), $object->getWaiterOpenTable(), $object->getWaiterCloseTable(), $object->getTable(), $object->getTime(), $object->getServicePaid(), $object->getId());
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".bill SET open_time = ?, close_time = ?, paid_time = ?, waiter_open_table = ?, waiter_close_table = ?, table_number = ?, bill_time = ?, service_paid = ? WHERE id = ?;");
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    if(!($return[0] == '00000' && $return[1] == '')){
      error_log(print_r($return, TRUE));
    }
    return $return[0] == '00000' && $return[1] == '';
  }

  public function closeTable($bill, $closed) {
    if($closed) {
      $dados = array(date('Y-m-d H:i:s'), $bill);
      $sql = "UPDATE " . Constants::SCHEMA_MAIN . ".bill SET paid_time = ? WHERE id = ?;";
    } else {
      $dados = array(date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $bill);
      $sql = "UPDATE " . Constants::SCHEMA_MAIN . ".bill SET close_time = ?, paid_time = ? WHERE id = ?;";
    }

    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    return $return[0] == '00000' && $return[1] == '';
  }

  public function setGetaway($bill, $closed) {
    if($closed) {
      $dados = array(date('Y-m-d H:i:s'), 1, $bill);
      $sql = "UPDATE " . Constants::SCHEMA_MAIN . ".bill SET paid_time = ?, getaway = ? WHERE id = ?;";
    } else {
      $dados = array(date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), 1, $bill);
      $sql = "UPDATE " . Constants::SCHEMA_MAIN . ".bill SET close_time = ?, paid_time = ?, getaway = ? WHERE id = ?;";
    }

    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    return $return[0] == '00000' && $return[1] == '';
  }

}

?>
