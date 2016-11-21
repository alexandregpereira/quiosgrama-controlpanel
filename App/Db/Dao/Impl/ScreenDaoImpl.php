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

class ScreenDaoImpl implements GenericDaoInterface {

    public function add($object) {
        $dados = array($object->getDescription(), $object->getUrl(), $object->getModule(), $object->getListOnTheScreen(), $object->getNeedAdministratorPermission());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".screen (description, url, module, list_on_the_screen, need_administrator_permission) values(?, ?, ?, ?, ?);");
        $stmt->execute($dados);
        return $con->lastInsertId("id");
    }

    public function listAll() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".screen;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listPermittedScreens() {
        $_CURRENT_USER = unserialize($_SESSION['QUIOSGRAMA']['USER']);
        $con = ConnectionFactory::getConnection();
        $dados = array($_CURRENT_USER->getId(), $_CURRENT_USER->getId());
        $stmt = $con->prepare("SELECT distinct b.* FROM " . Constants::SCHEMA_MAIN . ".user_permission a
            inner join " . Constants::SCHEMA_MAIN . ".screen b on (a.screen = b.id)
            inner join " . Constants::SCHEMA_MAIN . ".module c on (b.module = c.id)
            inner join " . Constants::SCHEMA_MAIN . ".user d on (a.user = d.id)
            where a.user = ? and
            ((d.administrator = 1 and b.need_administrator_permission = 1) or (d.administrator = 1 and b.need_administrator_permission = 0) or (d.administrator = 0 and b.need_administrator_permission = 0))
            union
            SELECT distinct c.* FROM " . Constants::SCHEMA_MAIN . ".user_permission a
            inner join " . Constants::SCHEMA_MAIN . ".module b on (b.id = a.module)
            inner join " . Constants::SCHEMA_MAIN . ".screen c on (b.id = c.module)
            inner join " . Constants::SCHEMA_MAIN . ".user d on (a.user = d.id)
            where a.user = ? and
            ((d.administrator = 1 and c.need_administrator_permission = 1) or (d.administrator = 1 and c.need_administrator_permission = 0) or (d.administrator = 0 and c.need_administrator_permission = 0));");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listByModuleListOnTheScreen($module, $administrator) {
        $dados = array($module);
        $con = ConnectionFactory::getConnection();

        $sql = "SELECT * FROM " . Constants::SCHEMA_MAIN . ".screen where module = ? and list_on_the_screen = 1";

        if($administrator == 0) {
            $sql .= " and need_administrator_permission = 0";
        }

        $sql .= " order by description;";

        $stmt = $con->prepare($sql);
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOne($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".screen WHERE id = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneByDescriptionOrUrl($description, $url) {
        $dados = array($description, $url);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".screen WHERE description = ? or url = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".screen WHERE id = ?;");
        $stmt->execute($dados);
        $retorno = $stmt->errorInfo();
        return $retorno[0] == '00000' && $retorno[1] == '';
    }

    public function update($object) {
        $dados = array($object->getDescription(), $object->getUrl(), $object->getModule(), $object->getListOnTheScreen(), $object->getNeedAdministratorPermission(), $object->getId());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".screen SET description = ?, url = ?, module = ?, list_on_the_screen = ?, need_administrator_permission = ? WHERE id = ?;");
        $stmt->execute($dados);
        $retorno = $stmt->errorInfo();
        return $retorno[0] == '00000' && $retorno[1] == '';
    }

}

?>
