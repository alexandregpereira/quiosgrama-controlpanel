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

class ProductDaoImpl implements GenericDaoInterface {

    public function add($object) {
        $dados = array($object->getCode(), $object->getName(), $object->getDescription(), $object->getPrice(), $object->getProductType(), $object->getPopularity());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".product (code, name, description, price, product_type, popularity) values(?, ?, ?, ?, ?, ?);");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        if(!($return[0] == '00000' && $return[1] == '')){
          error_log(print_r($return, TRUE));
        }
        return $con->lastInsertId("id");
    }

    public function listAll() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".product order by name;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listAllWithTax(){
      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("SELECT product.*, tax.*, icms.description icms_description, icms.orig, icms.cst icms_cst, icms.p_icms, icms.csosn,
                            pis.description pis_description, pis.cst pis_cst, pis.v_bc pis_v_bc, pis.p_pis, pis.q_bc_prod pis_q_bc_prod, pis.v_aliq_prod pis_v_aliq_prod,
                            cofins.description cofins_description, cofins.cst cofins_cst, cofins.v_bc cofins_v_bc, cofins.p_cofins, cofins.q_bc_prod cofins_q_bc_prod, cofins.v_aliq_prod cofins_v_aliq_prod
                            FROM " . Constants::SCHEMA_MAIN . ".product
                            inner join " . Constants::SCHEMA_MAIN . ".tax on tax.id = tax
                            inner join " . Constants::SCHEMA_MAIN . ".icms on icms.id = icms
                            inner join " . Constants::SCHEMA_MAIN . ".pis on pis.id = pis
                            inner join " . Constants::SCHEMA_MAIN . ".cofins on cofins.id = cofins;");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listAllWS() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT code, id, name, price FROM " . Constants::SCHEMA_MAIN . ".product;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOne($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".product WHERE id = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneByName($name) {
        $dados = array($name);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".product WHERE name = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneByCode($code) {
        $dados = array($code);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".product WHERE code = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".product WHERE id = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        return $return[0] == '00000' && $return[1] == '';
    }

    public function update($object) {
        $dados = array($object->getCode(), $object->getName(), $object->getDescription(), $object->getPrice(), $object->getProductType(), $object->getPopularity(), $object->getId());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".product SET code = ?, name = ?, description = ?, price = ?, product_type = ?, popularity = ? WHERE id = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        if(!($return[0] == '00000' && $return[1] == '')){
          error_log(print_r($return, TRUE));
        }
        return $return[0] == '00000' && $return[1] == '';
    }

}

?>
