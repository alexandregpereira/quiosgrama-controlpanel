<?php

/**
 * @author Alexandre Pereira
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class Destination {

    function __construct() {

    }

    private $_id;
    private $_name;
    private $_iconName;

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

    public function getIconName() {
        return $this->_iconName;
    }

    public function setIconName($iconName) {
        $this->_iconName = $iconName;
    }
}

?>
