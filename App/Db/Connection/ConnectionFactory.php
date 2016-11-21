<?php

/**
 * @author Jean Souza
 */

namespace App\Db\Connection;

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use \PDO;

class ConnectionFactory {

    private static $_con;
    private function __construct() {}
    private function __clone() {}

    public static function getConnection() {
        if (!isset(self::$_con)) {
            try {
                self::$_con = new PDO("mysql:host=" . Constants::QUIOSGRAMA_DB_HOST . ";port=" . Constants::QUIOSGRAMA_DB_PORT . ";Dbname=" . Constants::SCHEMA_MAIN . ";charset=utf8", Constants::QUIOSGRAMA_DB_USER, Constants::QUIOSGRAMA_DB_PASS, array(PDO::ATTR_PERSISTENT => false));
                self::$_con->exec("set names utf8");
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
        return self::$_con;
    }

}

?>
