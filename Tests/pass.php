<?php


namespace App\Db\Dao\Impl;

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;

$salt = base64_encode(Constants::KEY_CRYPT);
$returnAux = crypt("123456", '$1$' . $salt . '$');
$return = hash('sha512', $returnAux);

echo $return;

?>
