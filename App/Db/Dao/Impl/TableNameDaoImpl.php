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

class TableNameDaoImpl implements GenericDaoInterface {

  public function add($object) {
    $dados = array($object->getNumber(), $object->getTime(), $object->getXPosInDpi(), $object->getYPosInDpi(), $object->getMapPageNumber(), $object->getClient(), $object->getShow());
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".table_name (number, time, x_pos_in_dpi, y_pos_in_dpi, map_page_number, client, table_name.show) values(?, ?, ?, ?, ?, ?, ?);");
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    if(!($return[0] == '00000' && $return[1] == '')){
      error_log(print_r($return, TRUE));
    }
    return $con->lastInsertId("id");
  }

  public function addFromJson($object) {
    $dados = array($object->getNumber(), $object->getTime(), $object->getXPosInDpi(), $object->getYPosInDpi(), $object->getMapPageNumber(), $object->getClient(), $object->getClientTemp());
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".table_name (number, time, x_pos_in_dpi, y_pos_in_dpi, map_page_number, client, client_temp) values(?, ?, ?, ?, ?, ?, ?);");
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    if(!($return[0] == '00000' && $return[1] == '')){
      error_log(print_r($return, TRUE));
    }
    return $con->lastInsertId("id");
  }

  public function listAll() {
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".table_name where table_name.show = 1;");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function listAllWS() {
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT id, map_page_number as mapPageNumber, number, time as tableTime, x_pos_in_dpi as xPosInDpi, y_pos_in_dpi as yPosDpi, client FROM " . Constants::SCHEMA_MAIN . ".table_name;");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function listAllDashboard() {
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT a.number, b.open_time, b.close_time, b.paid_time, b.id as bill
                            FROM " . Constants::SCHEMA_MAIN . ".table_name a
                            left outer join " . Constants::SCHEMA_MAIN . ".bill b on (a.number = b.table_number)
                            where a.show = 1 order by number;");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function listOne($id) {
    $dados = array($id);
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".table_name WHERE id = ?;");
    $stmt->execute($dados);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function listOneByNumber($number) {
    $dados = array($number);
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".table_name WHERE number = ?;");
    $stmt->execute($dados);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function listOneByClient($client) {
    $dados = array($client);
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".table_name WHERE client = ?;");
    $stmt->execute($dados);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function remove($id) {
    $dados = array($id);
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".table_name WHERE id = ?;");
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    return $return[0] == '00000' && $return[1] == '';
  }

  public function update($object) {
    $dados = array($object->getNumber(), $object->getClient(), $object->getShow(), $object->getId());
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".table_name SET number = ?, client = ?, table_name.show = ? WHERE id = ?;");
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    if(!($return[0] == '00000' && $return[1] == '')){
      error_log(print_r($return, TRUE));
    }
    return $return[0] == '00000' && $return[1] == '';
  }

  public function updateClient($table, $id) {
    $dados = array($table, $id);
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".table_name SET client = ? WHERE id = ?;");
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    if(!($return[0] == '00000' && $return[1] == '')){
      error_log(print_r($return, TRUE));
    }
    return $return[0] == '00000' && $return[1] == '';
  }

  public function updateFromJson($object) {
    $client = $object->getClient();

    if(!is_null($client)) {
      $dados = array($object->getNumber(), $object->getXPosInDpi(), $object->getYPosInDpi(), $object->getMapPageNumber(), $object->getTime(), $object->getFunctionary(), $object->getClient(), $object->getClientTemp(), $object->getShow(), $object->getId());
      $arguments = ".table_name SET number = ?, x_pos_in_dpi = ?, y_pos_in_dpi = ?, map_page_number = ?, time = ?, functionary = ?, client = ?, client_temp = ?, table_name.show = ? WHERE id = ?;";
    } else {
      $dados = array($object->getNumber(), $object->getXPosInDpi(), $object->getYPosInDpi(), $object->getMapPageNumber(), $object->getTime(), $object->getFunctionary() , $object->getClientTemp(), $object->getShow(), $object->getId());
      $arguments = ".table_name SET number = ?, x_pos_in_dpi = ?, y_pos_in_dpi = ?, map_page_number = ?, time = ?, functionary = ?, client_temp = ?, table_name.show = ? WHERE id = ?;";
    }

    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . $arguments);
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    if(!($return[0] == '00000' && $return[1] == '')){
      error_log(print_r($return, TRUE));
    }
    return $return[0] == '00000' && $return[1] == '';
  }

  public function updateShow($object) {
    $dados = array($object->getShow(), $object->getId());
    $con = ConnectionFactory::getConnection();
    $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".table_name SET table_name.show = ? WHERE id = ?;");
    $stmt->execute($dados);
    $return = $stmt->errorInfo();
    if(!($return[0] == '00000' && $return[1] == '')){
      error_log(print_r($return, TRUE));
    }
    return $return[0] == '00000' && $return[1] == '';
  }
}

?>
