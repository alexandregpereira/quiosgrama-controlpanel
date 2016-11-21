<?php

/**
 * @author Jean Souza
 */
session_start();
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Login;
use App\Db\Dao\Impl\UserDaoImpl;
use App\Utility\Utilities;

$utilities = new Utilities();
$login = new Login();

if($login->isUserLogged()) {
	$userDao = new UserDaoImpl();

	$userDao->resetPassword($utilities->getParameter("id"));

	unset($login);
	header("Location:/listagem-de-usuarios");
} else {
	unset($login);
	header("Location:/login");
}
?>
