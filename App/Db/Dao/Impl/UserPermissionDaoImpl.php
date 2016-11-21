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
use App\Utility\Utilities;
use App\Model\User;
use \PDO;

class UserPermissionDaoImpl implements GenericDaoInterface {

    public function add($object) {

    }

    public function addModule($object) {
        $dados = array($object->getUser(), $object->getModule());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".user_permission (user, module, screen) values(?, ?, null);");
        $stmt->execute($dados);
        return $con->lastInsertId("id");
    }

    public function addScreen($object) {
        $dados = array($object->getUser(), $object->getScreen());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".user_permission (user, screen, module) values(?, ?, null);");
        $stmt->execute($dados);
        return $con->lastInsertId("id");
    }

    public function listAll() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".user_permission;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOne($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".user_permission where id = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneByUserAndModule($user, $module) {
        $dados = array($user, $module);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".user_permission where user = ? and module = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneByUserAndScreen($user, $screen) {
        $dados = array($user, $screen);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".user_permission where user = ? and screen = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".user_permission where id = ?;");
        $stmt->execute($dados);
        $retorno = $stmt->errorInfo();
        return $retorno[0] == '00000' && $retorno[1] == '';
    }

    public function update($object) {
    }

    public function updatePermissionModule($object) {
        $dados = array($object->getModule(), $object->getUser(), $object->getId());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("update " . Constants::SCHEMA_MAIN . ".user_permission set module = ?, user = ?, screen = null where id = ?;");
        $stmt->execute($dados);
        $retorno = $stmt->errorInfo();
        return $retorno[0] == '00000' && $retorno[1] == '';
    }

    public function updatePermissionScreen($object) {
        $dados = array($object->getScreen(), $object->getUser(), $object->getId());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("update " . Constants::SCHEMA_MAIN . ".user_permission set screen = ?, user = ?, module = null where id = ?;");
        $stmt->execute($dados);
        $retorno = $stmt->errorInfo();
        return $retorno[0] == '00000' && $retorno[1] == '';
    }

    public function listFields() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("show columns from " . Constants::SCHEMA_MAIN . ".user_permission where Field <> 'id';");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listRelations() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("select TABLE_NAME as relacionamento from INFORMATION_SCHEMA.KEY_COLUMN_USAGE where REFERENCED_TABLE_NAME = 'user_permission' and CONSTRAINT_SCHEMA = '" . Constants::SCHEMA_MAIN . "';");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function accessPermitted($url) {
        $_CURRENT_USER = unserialize($_SESSION['QUIOSGRAMA']['USER']);
        $dados = array($_CURRENT_USER->getId(), $url);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".user_permission a
            inner join " . Constants::SCHEMA_MAIN . ".screen b on (a.screen = b.id)
            inner join " . Constants::SCHEMA_MAIN . ".user c on (a.user = c.id)
            where
            ((c.administrator = 1 and b.need_administrator_permission = 1) or (c.administrator = 1 and b.need_administrator_permission = 0) or (c.administrator = 0 and b.need_administrator_permission = 0)) and
            a.user = ? and b.url = ?;");
        $stmt->execute($dados);
        $retorno = $stmt->fetchAll(PDO::FETCH_OBJ);

        if (isset($retorno[0])) {
            return true;
        } else {
            $dados = array($_CURRENT_USER->getId(), $url);
            $con = ConnectionFactory::getConnection();
            $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".user_permission a
                inner join " . Constants::SCHEMA_MAIN . ".module b on (a.module = b.id)
                inner join " . Constants::SCHEMA_MAIN . ".screen c on (b.id = c.module)
                inner join " . Constants::SCHEMA_MAIN . ".user d on (a.user = d.id)
                where
                ((d.administrator = 1 and c.need_administrator_permission = 1) or (d.administrator = 1 and c.need_administrator_permission = 0) or (d.administrator = 0 and c.need_administrator_permission = 0)) and
                a.user = ? and c.url = ?;");
            $stmt->execute($dados);
            $retorno = $stmt->fetchAll(PDO::FETCH_OBJ);

            return isset($retorno[0]);
        }
    }

}
