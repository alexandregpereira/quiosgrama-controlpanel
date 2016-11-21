<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class Complement {

    function __construct($complementData = null) {
      if($complementData != null){
        $this->_id = $complementData->id;
        $this->_description = $complementData->description;
        $this->_price = $complementData->price;
        $this->_drawable = $complementData->drawable;
      }
    }

    private $_id;
    private $_description;
    private $_price;
    private $_drawable;
    private $_typeArray;

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getDescription() {
        return $this->_description;
    }

    public function setDescription($description) {
        $this->_description = $description;
    }

    public function getPrice() {
        return $this->_price;
    }

    public function setPrice($price) {
        $this->_price = $price;
    }

    public function getDrawable() {
        return $this->_drawable;
    }

    public function setDrawable($drawable) {
        $this->_drawable = $drawable;
    }

    public function getTypeArray() {
        return $this->_typeArray;
    }

    public function setTypeArray($typeArray) {
        $this->_typeArray = $typeArray;
    }
}

?>
