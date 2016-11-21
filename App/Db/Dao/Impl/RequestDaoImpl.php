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

class RequestDaoImpl implements GenericDaoInterface {

    public function add($object) {
      $dados = array($object->getId(), $object->getTime(), $object->getBill(), $object->getWaiter());
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".request (id, time, bill, functionary) values(?, ?, ?, ?);");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $return[0] == '00000' && $return[1] == '';
    }

    public function listAll() {
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".request;");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listAllToday() {
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".request where DATE(`time`) = CURDATE();");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listAllByBill($bill) {
      $dados = array($bill, $bill);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("select b.name as functionary, a.time as time, a.id as request, c.table_number,
                              (select group_concat(a.quantity, 'X ', b.name)
                              from " . Constants::SCHEMA_MAIN . ".product_request a
                              inner join " . Constants::SCHEMA_MAIN . ".product b on (a.product = b.id)
                              where a.request in (
                              	select id
                              	from " . Constants::SCHEMA_MAIN . ".request
                              	where bill = ?
                              )) as products
                              from " . Constants::SCHEMA_MAIN . ".request a
                              inner join " . Constants::SCHEMA_MAIN . ".functionary b on (a.functionary = b.id)
                              inner join " . Constants::SCHEMA_MAIN . ".bill c on (a.bill = c.id)
                              where a.bill = ?;");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listNewRequests($limit = null) {
      $sql = "select a.id as request, a.datetime as time, a.time as timestring, b.name as functionary, a.bill, a.new, c.table_number, count(d.id) as products
              from " . Constants::SCHEMA_MAIN . ".request a
              inner join " . Constants::SCHEMA_MAIN . ".functionary b on (a.functionary = b.id)
              inner join " . Constants::SCHEMA_MAIN . ".bill c on (a.bill = c.id)
              inner join " . Constants::SCHEMA_MAIN . ".product_request d on (d.request = a.id)
              where a.new = 1 group by a.id order by a.datetime asc";

      if($limit != null && is_numeric($limit)) $sql .= " limit " . $limit . ";";
      else $sql .= ";";

      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listCompleteRequest($id) {
      $dados = array($id);
      $sql = "select a.id as request, DATE_FORMAT(a.datetime, '%d/%m/%Y %H:%i:%s') as time,
              TIMESTAMPDIFF(DAY, a.datetime, NOW()) AS days,
              TIMESTAMPDIFF(HOUR, a.datetime, NOW()) - TIMESTAMPDIFF(DAY, a.datetime, NOW()) * 24 AS hours,
              TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) - TIMESTAMPDIFF(HOUR, a.datetime, NOW()) * 60 AS minutes,
              a.time as timestring, b.name as functionary, a.bill, a.new, c.table_number
              from " . Constants::SCHEMA_MAIN . ".request a
              inner join " . Constants::SCHEMA_MAIN . ".functionary b on (a.functionary = b.id)
              inner join " . Constants::SCHEMA_MAIN . ".bill c on (a.bill = c.id)
              where a.id = ?;";

      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare($sql);
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function markRequestAsViewed($id) {
      $dados = array($id);
      $sql = "update " . Constants::SCHEMA_MAIN . ".request set new = 0 where id = ?;";
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare($sql);
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function countNewRequests() {
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("select count(id) as count from " . Constants::SCHEMA_MAIN . ".request where new = 1;");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOne($id) {
      $dados = array($id);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".request WHERE id = ?;");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($id) {
      $dados = array($id);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".request WHERE id = ?;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      return $return[0] == '00000' && $return[1] == '';
    }

    public function update($object) {
      $dados = array($object->getId(), $object->getTime(), $object->getBill(), $object->getWaiter(), $object->getId());
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".request SET id = ?, time = ?, bill = ?, functionary = ? WHERE id = ?;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $return[0] == '00000' && $return[1] == '';
    }

  }

  ?>
