<?php

/**
* @author Jean Souza
*/

namespace App\Db\Dao\Impl;

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\ProductRequest;
use App\Db\Dao\GenericDaoInterface;
use App\Db\Connection\ConnectionFactory;
use App\Model\User;
use \PDO;

class ProductRequestDaoImpl implements GenericDaoInterface {

    public function add($object) {
      $dados = array($object->getRequest(), $object->getProduct(), $object->getComplement(), $object->getQuantity(), $object->getValid(), $object->getTransferRoute(), $object->getProductRequestTime(), $object->getStatus());
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".product_request (request, product, complement, quantity, valid, transfer_route, product_request_time, status) values(?, ?, ?, ?, ?, ?, ?, ?);");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $con->lastInsertId("id");
    }

    public function listAll() {
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".product_request;");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listAllToday() {
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT prodReq.id id_product_request, prodReq.*, prodReq.valid valid_product_request, product.code, complement.description, request.id id_request, request.*, request.valid valid_request, bill.id id_bill, bill.*
                              FROM " . Constants::SCHEMA_MAIN . ".product_request prodReq
                              JOIN " . Constants::SCHEMA_MAIN . ".product ON product.id = product
                              JOIN " . Constants::SCHEMA_MAIN . ".complement ON complement.id = complement
                              JOIN " . Constants::SCHEMA_MAIN . ".request ON request.id = request
                              JOIN " . Constants::SCHEMA_MAIN . ".bill ON bill.id = request.bill
                              where (paid_time IS NULL and DATE(`bill_time`) = CURDATE() and DATE(`time`) = CURDATE()) or close_time IS NULL or (close_time IS NOT NULL and paid_time IS NULL);");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listAllTodayZombie($idZombie) {
      $dados = array(idZombie);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT prodReq.id id_product_request, prodReq.*, prodReq.valid valid_product_request, product.code, complement.description, request.id id_request, request.*, request.valid valid_request, bill.id id_bill, bill.*
                              FROM " . Constants::SCHEMA_MAIN . ".product_request prodReq
                              JOIN " . Constants::SCHEMA_MAIN . ".product ON product.id = product
                              JOIN " . Constants::SCHEMA_MAIN . ".complement ON complement.id = complement
                              JOIN " . Constants::SCHEMA_MAIN . ".request ON request.id = request
                              JOIN " . Constants::SCHEMA_MAIN . ".bill ON bill.id = request.bill
                              where zombie = ? and ((paid_time IS NULL and DATE(`bill_time`) = CURDATE() and DATE(`time`) = CURDATE()) or close_time IS NULL or (close_time IS NOT NULL and paid_time IS NULL));");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listAllByRequest($request) {
      $dados = array($request);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("select a.quantity as quantity, b.name as product
                              from " . Constants::SCHEMA_MAIN . ".product_request a
                              inner join " . Constants::SCHEMA_MAIN . ".product b on (a.product = b.id)
                              where a.request = ?;");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listAllByBill($bill) {
      $dados = array($bill, $bill);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("select a.id as product_request, a.quantity as quantity, b.name as product, b.price as price, c.description as complement, (
                              	select b.table_number from " . Constants::SCHEMA_MAIN . ".request a
                              	inner join " . Constants::SCHEMA_MAIN . ".bill b on (a.bill = b.id)
                              	where b.id = ? limit 1
                              ) as table_number, a.valid
                              from " . Constants::SCHEMA_MAIN . ".product_request a
                              inner join " . Constants::SCHEMA_MAIN . ".product b on (a.product = b.id)
                              left outer join " . Constants::SCHEMA_MAIN . ".complement c on (a.complement = c.id)
                              where a.request in (
                              	select id
                              	from " . Constants::SCHEMA_MAIN . ".request
                              	where bill = ?
                              );");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listTotalByBill($bill) {
      $dados = array($bill);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("select sum((
                              	CASE a.valid
                              		WHEN 1 THEN (a.quantity * b.price)
                              		ELSE 0
                              	END
                              )) as total, c.bill, d.table_number
                              from " . Constants::SCHEMA_MAIN . ".product_request a
                              inner join " . Constants::SCHEMA_MAIN . ".product b on (a.product = b.id)
                              inner join " . Constants::SCHEMA_MAIN . ".request c on (c.id = a.request)
                              inner join " . Constants::SCHEMA_MAIN . ".bill d on (d.id = c.bill)
                              where a.request in (
                                select id
                                from " . Constants::SCHEMA_MAIN . ".request
                                where bill = ?
                              );");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOne($id) {
      $dados = array($id);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".product_request WHERE id = ?;");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneComposer($object) {
      $dados = array($object->getRequest(), $object->getProduct(), $object->getComplement());
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".product_request WHERE request = ? and product = ? and complement = ?;");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listByBill($tableNumber) {
      $dados = array($id);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT product_request.id, request, product, quantity, price FROM " . Constants::SCHEMA_MAIN . ".product_request inner join request ON request.id = request inner join bill ON bill.id = request.bill inner join product ON product.id = product where bill.table_number = ? and ISNULL(bill.close_time);");
      $stmt->execute($dados);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listReportByFunctionary($date) {
      $dados = array($date);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT waiter.name, SUM(price*quantity) as total FROM " . Constants::SCHEMA_MAIN . ".product_request prodreq
                            LEFT outer JOIN " . Constants::SCHEMA_MAIN . ".product prod on prod.id = product
                            JOIN " . Constants::SCHEMA_MAIN . ".request req on req.id = request
                            LEFT outer JOIN " . Constants::SCHEMA_MAIN . ".functionary waiter on waiter.id = functionary
                            where DATE(product_request_time) = ? and prodreq.valid = 1
                            GROUP BY functionary;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listReportByInvalidItens($date) {
      $dados = array($date);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT product, name, SUM(quantity) as quantity FROM " . Constants::SCHEMA_MAIN . ".product_request prodreq
                            LEFT outer JOIN " . Constants::SCHEMA_MAIN . ".product prod on prod.id = product
                            where DATE(product_request_time) = ? and prodreq.valid = 0
                            GROUP BY product;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listReportByValidItens($date) {
      $dados = array($date);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT product, name, SUM(quantity) as quantity, SUM(price*quantity) as total FROM " . Constants::SCHEMA_MAIN . ".product_request prodreq
                            LEFT outer JOIN " . Constants::SCHEMA_MAIN . ".product prod on prod.id = product
                            where DATE(product_request_time) = ? and prodreq.valid = 1
                            GROUP BY product;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listPrint() {
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT product_request.* FROM " . Constants::SCHEMA_MAIN . ".product_request
                            LEFT JOIN " . Constants::SCHEMA_MAIN . ".request ON request.id = request
                            LEFT JOIN " . Constants::SCHEMA_MAIN . ".bill ON bill.id = request.bill
                            WHERE status = " . ProductRequest::NOT_VISUALIZED_STATUS . "
                            and product_request.valid = 1 and paid_time IS NULL and transfer_route IS NULL;");
      $stmt->execute();
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($id) {
      $dados = array($id);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".product_request WHERE id = ?;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      return $return[0] == '00000' && $return[1] == '';
    }

    public function invalidateItem($id) {
      $dados = array($id);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("update " . Constants::SCHEMA_MAIN . ".product_request set valid = 0 WHERE id = ?;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      return $return[0] == '00000' && $return[1] == '';
    }

    public function validateItem($id) {
      $dados = array($id);
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("update " . Constants::SCHEMA_MAIN . ".product_request set valid = 1 WHERE id = ?;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      return $return[0] == '00000' && $return[1] == '';
    }

    public function update($object){
      $dados = array($object->getRequest(), $object->getProduct(), $object->getComplement(), $object->getQuantity(), $object->getValid(), $object->getTransferRoute(), $object->getProductRequestTime(), $object->getStatus(), $object->getId());
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".product_request SET request = ?, product = ?, complement = ?, quantity = ?, valid = ?, transfer_route = ?, product_request_time = ?, status = ? WHERE id = ?;");
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $return[0] == '00000' && $return[1] == '';
    }

    public function updatePrint($printList){
      $whereClause = "";
      foreach ($printList as $prodReq) {
        $whereClause .= "id = " . $prodReq->id . " or ";
      }
      $whereClause = substr($whereClause, 0, (strlen($whereClause) - 3));

      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".product_request SET printing = 1 , print_time = CURRENT_TIMESTAMP WHERE " . $whereClause . ";");
      $stmt->execute();
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $return[0] == '00000' && $return[1] == '';
    }
  }

  ?>
