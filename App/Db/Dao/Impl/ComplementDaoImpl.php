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

class ComplementDaoImpl implements GenericDaoInterface {

    public function add($object) {
        $dados = array($object->getDescription(), $object->getPrice(), $object->getDrawable());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".complement (description, price, drawable) values(?, ?, ?);");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        if(!($return[0] == '00000' && $return[1] == '')){
          error_log(print_r($return, TRUE));
        }
        return $con->lastInsertId("id");
    }

    public function listAll() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".complement;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOne($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".complement WHERE id = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneByDescription($description) {
        $dados = array($description);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".complement WHERE description = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".complement WHERE id = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        return $return[0] == '00000' && $return[1] == '';
    }

    public function update($object) {
        $dados = array($object->getDescription(), $object->getPrice(), $object->getDrawable(), $object->getId());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".complement SET description = ?, price = ?, drawable = ? WHERE id = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        return $return[0] == '00000' && $return[1] == '';
    }

}

?>
