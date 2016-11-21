<?php

session_start();
ob_start();

$_SESSION['QUIOSGRAMA'] = '';
unset($_SESSION['QUIOSGRAMA']);

setcookie("QUIOSGRAMA-USER", "", time() - 3600);

header('Location:/dashboard');
?>
