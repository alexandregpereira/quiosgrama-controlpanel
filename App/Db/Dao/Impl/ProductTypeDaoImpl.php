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

class ProductTypeDaoImpl implements GenericDaoInterface {

    public function add($object) {
        $dados = array($object->getName(), $object->getPriority(), $object->getTabImage(), $object->getButtonImage(), $object->getIconImage(), $object->getColor(), $object->getDestination());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("INSERT INTO " . Constants::SCHEMA_MAIN . ".product_type (name, priority, tab_image, button_image, icon_image, color, destination) values(?, ?, ?, ?, ?, ?, ?);");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        if(!($return[0] == '00000' && $return[1] == '')){
          error_log(print_r($return, TRUE));
        }
        return $con->lastInsertId("id");
    }

    public function listAll() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT product_type.*, destination.name destination_name, icon_name, printer_ip FROM " . Constants::SCHEMA_MAIN . ".product_type
                                inner join  " . Constants::SCHEMA_MAIN . ".destination on (product_type.destination = destination.id) order by name;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listAllWS() {
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT button_image as buttonImage, color_id as colorId, id, image_info as imageInfo, name, priority, tab_image as tabImage FROM " . Constants::SCHEMA_MAIN . ".product_type;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOne($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT product_type.*, destination.name destination_name, icon_name FROM " . Constants::SCHEMA_MAIN . ".product_type
                                inner join  " . Constants::SCHEMA_MAIN . ".destination on (product_type.destination = destination.id) WHERE product_type.id = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneWS($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT button_image as buttonImage, color_id as colorId, id, image_info as imageInfo, name, priority, tab_image as tabImage FROM " . Constants::SCHEMA_MAIN . ".product_type WHERE id = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function listOneByName($name) {
        $dados = array($name);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("SELECT * FROM " . Constants::SCHEMA_MAIN . ".product_type WHERE name = ?;");
        $stmt->execute($dados);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function remove($id) {
        $dados = array($id);
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("delete from " . Constants::SCHEMA_MAIN . ".product_type WHERE id = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        return $return[0] == '00000' && $return[1] == '';
    }

    public function update($object) {
        $dados = array($object->getName(), $object->getPriority(), $object->getTabImage(), $object->getButtonImage(), $object->getIconImage(), $object->getColor(), $object->getDestination(), $object->getId());
        $con = ConnectionFactory::getConnection();
        $stmt = $con->prepare("UPDATE " . Constants::SCHEMA_MAIN . ".product_type SET name = ?, priority = ?, tab_image = ?, button_image = ?, icon_image = ?, color = ?, destination = ? WHERE id = ?;");
        $stmt->execute($dados);
        $return = $stmt->errorInfo();
        if(!($return[0] == '00000' && $return[1] == '')){
          error_log(print_r($return, TRUE));
        }
        return $return[0] == '00000' && $return[1] == '';
    }

}

?>
