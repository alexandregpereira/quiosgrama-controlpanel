<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class Poi {

    function __construct() {

    }

    private $_id;
    private $_name;
    private $_xPosInDpi;
    private $_yPosInDpi;
    private $_image;
    private $_mapPageNumber;
    private $_time;
    private $_functionary;

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

    public function getXPosInDpi() {
        return $this->_xPosInDpi;
    }

    public function setXPosInDpi($xPosInDpi) {
        $this->_xPosInDpi = $xPosInDpi;
    }

    public function getYPosInDpi() {
        return $this->_yPosInDpi;
    }

    public function setYPosInDpi($yPosInDpi) {
        $this->_yPosInDpi = $yPosInDpi;
    }

    public function getImage() {
        return $this->_image;
    }

    public function setImage($image) {
        $this->_image = $image;
    }

    public function getMapPageNumber() {
        return $this->_mapPageNumber;
    }

    public function setMapPageNumber($mapPageNumber) {
        $this->_mapPageNumber = $mapPageNumber;
    }

    public function getTime() {
        return $this->_time;
    }

    public function setTime($time) {
        $this->_time = $time;
    }

    public function getFunctionary() {
        return $this->_functionary;
    }

    public function setFunctionary($functionary) {
        $this->_functionary = $functionary;
    }

}

?>
