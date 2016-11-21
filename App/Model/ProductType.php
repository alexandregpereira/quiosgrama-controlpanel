<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class ProductType {

    function __construct() {

    }

    private $_id;
    private $_name;
    private $_priority;
    private $_tabImage;
    private $_buttonImage;
    private $_iconImage;
    private $_color;
    private $_destination;

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getName() {
        return $this->_name;
    }

    public function setName($name) {
        $this->_name = $name;
    }

    public function getPriority() {
        return $this->_priority;
    }

    public function setPriority($priority) {
        $this->_priority = $priority;
    }

    public function getTabImage() {
        return $this->_tabImage;
    }

    public function setTabImage($tabImage) {
        $this->_tabImage = $tabImage;
    }

    public function getButtonImage() {
        return $this->_buttonImage;
    }

    public function setButtonImage($buttonImage) {
        $this->_buttonImage = $buttonImage;
    }

    public function getIconImage() {
        return $this->_iconImage;
    }

    public function setIconImage($iconImage) {
        $this->_iconImage = $iconImage;
    }

    public function getColor() {
        return $this->_color;
    }

    public function setColor($color) {
        $this->_color = $color;
    }

    public function getDestination(){
      return $this->_destination;
    }

    public function setDestination($destination){
      $this->_destination = $destination;
    }
}

?>
