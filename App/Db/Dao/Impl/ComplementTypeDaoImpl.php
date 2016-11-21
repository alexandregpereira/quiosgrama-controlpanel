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
use \PDO;

class ComplementTypeDaoImpl implements GenericDaoInterface {

    public function add($object) {}

    public function addComplementType($productType, $complement) {
        $dados = array($productType, $complement);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".complement_type (product_type, complement) values(?, ?);");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listAll() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".complement_type;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listAllByComplement($complement) {
        $dados = array($complement);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".complement_type where complement = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listAllByComplementObject($complement) {
        $dados = array($complement->getId());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".complement_type where complement = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOne($description) {}

    public function listOneComplementType($productType, $complement) {
        $dados = array($productType, $complement);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".complement_type WHERE product_type = ? and complement = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($description) {}

    public function removeComplementType($productType, $complement) {
        $dados = array($productType, $complement);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".complement_type WHERE product_type = ? and complement = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        return $return[0] == '00000' && $return[1] == '';
    }

    public function removeComplementTypeByComplement($complement) {
        $dados = array($complement);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".complement_type WHERE complement = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        return $return[0] == '00000' && $return[1] == '';
    }

    public function update($object) {}

}

?>
