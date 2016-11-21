<?php

ob_start();
session_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Login;
use App\Utility\Utilities;

$login = new Login();
$utilities = new Utilities();

if ($login->isUserLogged()) {
	$page_title = "Erro 404";
	$body_page_title = "Erro 404";
	$inc_pg_body = "View404.php";
	include(getenv('QUIOSGRAMA_SYSTEM_PATH') . Constants::TEMPLATE_MAIN);
} else {
	header('Location:/login');
}

unset($login);
?>
