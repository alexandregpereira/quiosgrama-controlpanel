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

class FunctionaryDaoImpl implements GenericDaoInterface {

    public function add($object) {
        $dados = array($object->getName(), $object->getDevice(), $object->getAdminFlag());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".functionary (name, device, admin_flag) values(?, ?, ?);");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        if(!($return[0] == '00000' && $return[1] == '')){
          error_log("FunctionaryDaoImpl add: " . print_r($return, TRUE));
        }
        return $con->lastInsertId("id");
    }

    public function listAll() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".functionary where device IS NOT NULL order by name;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listAllWS() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT a.id, b.imei, a.name FROM " . Constants::SCHEMA_MAIN . ".functionary a
        inner join " . Constants::SCHEMA_MAIN . ".device b on (a.device = b.id);");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listAdminByImei($imei) {
        $dados = array($imei);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT a.id, b.imei, a.name FROM " . Constants::SCHEMA_MAIN . ".functionary a
        inner join " . Constants::SCHEMA_MAIN . ".device b on (a.device = b.id) WHERE imei = ? and admin_flag = 1;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listNoAdminByImei($imei) {
        $dados = array($imei);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT a.id, b.imei, a.name FROM " . Constants::SCHEMA_MAIN . ".functionary a
        inner join " . Constants::SCHEMA_MAIN . ".device b on (a.device = b.id) WHERE imei = ? and admin_flag != 1;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOne($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".functionary WHERE id = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneByName($name) {
        $dados = array($name);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".functionary WHERE name = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneByDevice($device) {
        $dados = array($device);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".functionary WHERE device = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".functionary WHERE id = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        return $return[0] == '00000' && $return[1] == '';
    }

    public function update($object) {
        $dados = array($object->getName(), $object->getDevice(), $object->getAdminFlag(), $object->getId());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".functionary SET name = ?, device = ?, admin_flag = ? WHERE id = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        return $return[0] == '00000' && $return[1] == '';
    }

    public function updateDevice($object) {
        $dados = array($object->getDevice(), $object->getId());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".functionary SET device = ? WHERE id = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        return $return[0] == '00000' && $return[1] == '';
    }

    public function updateDisableAllDevices($device) {
        $dados = array($device);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".functionary SET device = null WHERE device = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        return $return[0] == '00000' && $return[1] == '';
    }
}

?>
