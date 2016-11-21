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

class PoiDaoImpl implements GenericDaoInterface {

    public function add($object) {
        $dados = array($object->getName(), $object->getXPosInDpi(), $object->getYPosInDpi(), $object->getImage(), $object->getMapPageNumber(), $object->getTime());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".poi (name, x_pos_in_dpi, y_pos_in_dpi, image, map_page_number, time) values(?, ?, ?, ?, ?, ?);");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        if(!($return[0] == '00000' && $return[1] == '')){
          error_log(print_r($return, TRUE));
        }
        return $con->lastInsertId("id");
    }

    public function listAll() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".poi;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listAllWS() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT id as idPoi, image, map_page_number as mapPageNumber, name, time as poiTime, x_pos_in_dpi as xPosDpi, y_pos_in_dpi as yPosDpi FROM " . Constants::SCHEMA_MAIN . ".poi;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOne($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".poi WHERE id = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneByName($name) {
        $dados = array($name);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".poi WHERE name = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".poi WHERE id = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        return $return[0] == '00000' && $return[1] == '';
    }

    public function update($object) {
        $dados = array($object->getName(), $object->getImage(), $object->getId());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".poi SET name = ?, image = ? WHERE id = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        if(!($return[0] == '00000' && $return[1] == '')){
          error_log(print_r($return, TRUE));
        }
        return $return[0] == '00000' && $return[1] == '';
    }

    public function updateFromJson($object) {
      $dados = array($object->getXPosInDpi(), $object->getYPosInDpi(), $object->getMapPageNumber(), $object->getTime(), $object->getFunctionary() , $object->getImage(), $object->getId());
      $arguments = ".poi SET x_pos_in_dpi = ?, y_pos_in_dpi = ?, map_page_number = ?, time = ?, functionary = ?, image = ? WHERE id = ?;";

      $con = ConnectionFactory::getConnection();
      $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . $arguments);
      $stmt->execute($dados);
      $return = $stmt->errorInfo();
      if(!($return[0] == '00000' && $return[1] == '')){
        error_log(print_r($return, TRUE));
      }
      return $return[0] == '00000' && $return[1] == '';
    }
}

?>
