<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class Module {

    function __construct() {

    }

    private $_id;
    private $_description;

    public function getId() {
        return $this->_id;
    }

    public function getDescription() {
        return $this->_description;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function setDescription($description) {
        $this->_description = $description;
    }

}

?>
