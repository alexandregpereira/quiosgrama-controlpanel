<?php

/**
 * @author Jean Souza
 */

namespace App\WebSocket\Sockets;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Db\Dao\Impl\BillDaoImpl;
use App\Db\Dao\Impl\ProductRequestDaoImpl;
use App\Db\Dao\Impl\TableNameDaoImpl;
use App\Db\Dao\Impl\RequestDaoImpl;

class Dashboard {

  private $tables;
  private $requests;
  private $products;
  private $totals;
  private $bills = array();

  private function verifyTables() {
    $dao = new TableNameDaoImpl();

    $tablesTmpAll = $dao->listAllDashboard();
    $tablesTmp = array();
    $lastTable = null;

		foreach($tablesTmpAll as $table) {
			if(!is_null($lastTable) && $lastTable->number == $table->number) {
				$lastOpenDate = date("Y-m-d H:i:s", strtotime($lastTable->open_time));
				$openDate = date("Y-m-d H:i:s", strtotime($table->open_time));

				if($openDate > $lastOpenDate) {
					$lastTable = $table;
				}
			} elseif(!is_null($lastTable)) {
				array_push($tablesTmp, $lastTable);
				$lastTable = $table;
			} else {
				$lastTable = $table;
			}
		}

		if(!is_null($lastTable)) array_push($tablesTmp, $lastTable);

    if(serialize($this->tables) != serialize($tablesTmp)) {
      $this->tables = $tablesTmp;

      foreach($this->tables as $table) {
        array_push($this->bills, $table->bill);
      }

      return true;
    } else {
      return false;
    }
  }

  private function verifyRequests() {
    $dao = new RequestDaoImpl();
    $requestsTmp = array();

    foreach($this->bills as $bill) {
      if(!is_null($bill)) array_push($requestsTmp, $dao->listAllByBill($bill));
    }

    if(serialize($this->requests) != serialize($requestsTmp)) {
      $this->requests = $requestsTmp;
      return true;
    } else {
      return false;
    }
  }

  private function verifyProducts() {
    $dao = new ProductRequestDaoImpl();
    $productsTmp = array();

    foreach($this->bills as $bill) {
      if(!is_null($bill)) array_push($productsTmp, $dao->listAllByBill($bill));
    }

    if(serialize($this->products) != serialize($productsTmp)) {
      $this->products = $productsTmp;
      return true;
    } else {
      return false;
    }
  }

  private function updateTotals() {
    $daoProductRequest = new ProductRequestDaoImpl();

    $this->totals = array();

    foreach($this->bills as $bill) {
      if(!is_null($bill)) array_push($this->totals, $daoProductRequest->listTotalByBill($bill));
    }
  }

  public function load() {
    $methods = array();
    $values = array();

    $send = false;

    if($this->verifyTables()) {
      array_push($methods, "updateTables");
      array_push($values, $this->tables);

      $send = true;
    }

    if($this->verifyRequests()) {
      array_push($methods, "updateRequests");
      array_push($values, $this->requests);

      $send = true;
    }

    if($this->verifyProducts()) {
      array_push($methods, "updateProducts");
      array_push($values, $this->products);

      $this->updateTotals();

      array_push($methods, "updateTotals");
      array_push($values, $this->totals);

      $send = true;
    }

    $messageArray = array(
      "methods" => $methods,
      "values" => $values,
      "key" => "Dashboard"
    );

    if($send) return $messageArray;
    else return array();
  }

  public function closeTable($value) {
    $dao = new BillDaoImpl();

    $dao->closeTable($value->{'bill'}, $value->{'closed'});
  }

  public function setGetaway($value) {
    $dao = new BillDaoImpl();

    $dao->setGetaway($value->{'bill'}, $value->{'closed'});
  }

  public function invalidateItem($value) {
    $dao = new ProductRequestDaoImpl();

    $dao->invalidateItem($value);
  }

  public function validateItem($value) {
    $dao = new ProductRequestDaoImpl();

    $dao->validateItem($value);
  }
}
