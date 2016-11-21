<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class Product {

    function __construct() {

    }

    private $_id;
    private $_code;
    private $_name;
    private $_description;
    private $_price;
    private $_popularity;
    private $_productType;

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getCode() {
        return $this->_code;
    }

    public function setCode($code) {
        $this->_code = $code;
    }

    public function getName() {
        return $this->_name;
    }

    public function setName($name) {
        $this->_name = $name;
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

    public function getProductType() {
        return $this->_productType;
    }

    public function setProductType($productType) {
        $this->_productType = $productType;
    }

    public function getPopularity() {
        return $this->_popularity;
    }

    public function setPopularity($popularity) {
        $this->_popularity = $popularity;
    }
}

?>
