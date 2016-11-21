<?php

ob_start();
session_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Login;
use App\Utility\Utilities;

$utilities = new Utilities();
$login = new Login();

if (!$login->isUserLogged()) {
    // Define template
    include($systemPath . Constants::TEMPLATE_LOGIN);
} else {
    header('Location:/dashboard');
}
?>
