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

class ModuleDaoImpl implements GenericDaoInterface {

    public function add($object) {
        $dados = array($object->getDescription());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".module (description) values(?);");
        $stmt->execute($dados);
        return $con->lastInsertId("id");
    }

    public function listAll() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".module order by description;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function showModuleMenu($module) {
        $_CURRENT_USER = unserialize($_SESSION['QUIOSGRAMA']['USER']);
        $con = ConnectionFactory::getConnection();

        $dados = array($_CURRENT_USER->getId(), $module);

        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".user_permission a
            inner join " . Constants::SCHEMA_MAIN . ".module b on (a.module = b.id)
            inner join " . Constants::SCHEMA_MAIN . ".screen c on (b.id = c.module)
            inner join " . Constants::SCHEMA_MAIN . ".user d on (a.user = d.id)
            where a.user = ? and a.module = ? and
            ((d.administrator = 1 and c.need_administrator_permission = 1) or (d.administrator = 1 and c.need_administrator_permission = 0) or (d.administrator = 0 and c.need_administrator_permission = 0));");

        $stmt->execute($dados);
        $return = $stmt->fetchAll(PDO::FETCH_OBJ);

        if(isset($return[0])) {
            return true;
        } else {
            $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".user_permission a
                inner join " . Constants::SCHEMA_MAIN . ".screen b on (a.screen = b.id)
                inner join " . Constants::SCHEMA_MAIN . ".module c on (b.module = c.id)
                inner join " . Constants::SCHEMA_MAIN . ".user d on (a.user = d.id)
                where a.user = ? and c.id = ? and
                ((d.administrator = 1 and b.need_administrator_permission = 1) or (d.administrator = 1 and b.need_administrator_permission = 0) or (d.administrator = 0 and b.need_administrator_permission = 0));");
            $stmt->execute($dados);
            $return = $stmt->fetchAll(PDO::FETCH_OBJ);

            return isset($return[0]);
        }
    }

    public function listPermittedModules() {
        $_CURRENT_USER = unserialize($_SESSION['QUIOSGRAMA']['USER']);
        $con = ConnectionFactory::getConnection();
        $dados = array($_CURRENT_USER->getId());
        $stmt = $con->prepare("SELECT distinct b.* FROM " . Constants::SCHEMA_MAIN . ".user_permission a
            inner join " . Constants::SCHEMA_MAIN . ".module b on (a.module = b.id)
            inner join " . Constants::SCHEMA_MAIN . ".screen c on (b.id = c.module)
            inner join " . Constants::SCHEMA_MAIN . ".user d on (a.user = d.id)
            where a.user = ? and
            ((d.administrator = 1 and c.need_administrator_permission = 1) or (d.administrator = 1 and c.need_administrator_permission = 0) or (d.administrator = 0 and c.need_administrator_permission = 0));");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOne($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".module WHERE id = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneByDescription($description) {
        $dados = array($description);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".module WHERE description = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".module WHERE id = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        return $return[0] == '00000' && $return[1] == '';
    }

    public function update($object) {
        $dados = array($object->getDescription(), $object->getId());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".module SET description = ? WHERE id = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        return $return[0] == '00000' && $return[1] == '';
    }

}

?>
