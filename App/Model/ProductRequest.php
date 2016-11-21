<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class ProductRequest {

    const NOT_VISUALIZED_STATUS = 0;

    function __construct() {

    }

    private $_id;
    private $_request;
    private $_product;
    private $_complement;
    private $_quantity;
    private $_valid;
    private $_transferRoute;
    private $_productRequestTime;
    private $_status;

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getRequest() {
        return $this->_request;
    }

    public function setRequest($request) {
        $this->_request = $request;
    }

    public function getProduct() {
        return $this->_product;
    }

    public function setProduct($product) {
        $this->_product = $product;
    }

    public function getComplement() {
        return $this->_complement;
    }

    public function setComplement($complement) {
        $this->_complement = $complement;
    }

    public function getQuantity() {
        return $this->_quantity;
    }

    public function setQuantity($quantity) {
        $this->_quantity = $quantity;
    }

    public function getValid() {
        return $this->_valid;
    }

    public function setValid($valid) {
        $this->_valid = $valid;
    }

    public function getTransferRoute() {
        return $this->_transferRoute;
    }

    public function setTransferRoute($transferRoute) {
        $this->_transferRoute = $transferRoute;
    }

    public function getProductRequestTime() {
        return $this->_productRequestTime;
    }

    public function setProductRequestTime($productRequestTime) {
        $this->_productRequestTime = $productRequestTime;
    }

    public function getStatus() {
        return $this->_status;
    }

    public function setStatus($status) {
        $this->_status = $status;
    }
}

?>
