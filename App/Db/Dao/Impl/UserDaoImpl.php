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
use App\Model\Login;
use \PDO;

class UserDaoImpl implements GenericDaoInterface {

    public function add($object) {
        $dados = array($object->getUser(), $object->getPassword(), $object->getName(), $object->getRegisterDate(), $object->getAdministrator(), $object->getEmail());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".user (user, password, name, register_date, administrator, email) values(?, ?, ?, ?, ?, ?);");
        $stmt->execute($dados);
        return $con->lastInsertId("id");
    }

    public function listAll() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".user;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOne($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".user WHERE id = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneByUser($user) {
        $dados = array($user);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".user WHERE user = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".user WHERE id = ?;");
        $stmt->execute($dados);
        $retorno = $stmt->errorInfo();
        return $retorno[0] == '00000' && $retorno[1] == '';
    }

    public function update($object) {
        $dados = array($object->getUser(), $object->getPassword(), $object->getName(), $object->getAdministrator(), $object->getEmail(), $object->getId());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".user SET user = ?, password = ?, name = ?, administrator = ?, email = ? WHERE id = ?;");
        $stmt->execute($dados);
        $retorno = $stmt->errorInfo();
        return $retorno[0] == '00000' && $retorno[1] == '';
    }

    public function resetPassword($id) {
        $loginModel = new Login();
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".user SET password = '" . $loginModel->encryptPassword(DEFAULT_PASSWORD) . "' WHERE id = ?;");
        $stmt->execute($dados);
        $retorno = $stmt->errorInfo();
        return $retorno[0] == '00000' && $retorno[1] == '';
    }

    public function updatePasswordByUser($password, $user) {
        $loginModel = new Login();
        $dados = array($loginModel->encryptPassword($password), $user);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".user SET password = ? WHERE user = ?;");
        $stmt->execute($dados);
        $retorno = $stmt->errorInfo();
        return $retorno[0] == '00000' && $retorno[1] == '';
    }

}

?>
