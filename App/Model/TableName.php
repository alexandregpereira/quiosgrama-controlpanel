<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class TableName {

    function __construct($tableData = null) {
      if($tableData != null){
        $this->_id = $tableData->id;
        $this->_number = $tableData->number;
        $this->_xPosInDpi = $tableData->x_pos_in_dpi;
        $this->_yPosInDpi = $tableData->y_pos_in_dpi;
        $this->_mapPageNumber = $tableData->map_page_number;
        $this->_time = $tableData->time;
        $this->_functionary = $tableData->functionary;
        $this->_client = $tableData->client;
        $this->_clientTemp = $tableData->client_temp;
        $this->_show = $tableData->show;
      }
    }

    private $_id;
    private $_number;
    private $_xPosInDpi;
    private $_yPosInDpi;
    private $_mapPageNumber;
    private $_time;
    private $_functionary;
    private $_client;
    private $_clientTemp;
    private $_show;

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getNumber() {
        return $this->_number;
    }

    public function setNumber($number) {
        $this->_number = $number;
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

    public function getClient() {
        return $this->_client;
    }

    public function setClient($client) {
        $this->_client = $client;
    }

    public function getClientTemp() {
        return $this->_clientTemp;
    }

    public function setClientTemp($clientTemp) {
        $this->_clientTemp = $clientTemp;
    }

    public function getShow() {
        return $this->_show;
    }

    public function setShow($show) {
        $this->_show = $show;
    }
}

?>
