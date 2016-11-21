<?php

/**
 * @author Jean Souza
 */

namespace App\Db\Dao;

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

interface GenericDaoInterface {

    public function listAll();

    public function listOne($id);

    public function add($entity);

    public function remove($id);

    public function update($entity);
}

?>
