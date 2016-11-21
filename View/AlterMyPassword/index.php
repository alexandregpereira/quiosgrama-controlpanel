<?php

ob_start();
session_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Login;
use App\Model\User;
use App\Utility\Utilities;

$utilities = new Utilities();

$_CURRENT_USER = unserialize(stripslashes($_SESSION['QUIOSGRAMA']['USER']));
$login = new Login();

if ($login->isUserLogged()) {
	$page_title = "Alterar Minha Senha";
	$body_page_title = "Alterar Minha Senha";
	$inc_pg_body = "View.php";
	include(getenv('QUIOSGRAMA_SYSTEM_PATH') . Constants::TEMPLATE_MAIN);
} else {
	header('Location:/login');
}

unset($login);
?>
