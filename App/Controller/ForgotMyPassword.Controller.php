<?php

/**
* @author Jean Souza
*/
session_start();
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Db\Dao\Impl\UserDaoImpl;
use App\Model\Login;
use App\Model\Email;
use App\Utility\Utilities;

$utilities = new Utilities();
$login = new Login();

$dao = new UserDaoImpl();
$user = $dao->listOneByUser($utilities->getParameter("user"));

if(count($user) == 1) {
  $newPassword = $login->generatePassword();
  $return = $dao->updatePasswordByUser($newPassword, $user[0]->user);

  if($return) {
    // Email begin

    $subject = "Quiosgrama - Nova Senha";
    $message = "Uma nova senha foi gerada, segue abaixo:<br/><br/>Senha:&nbsp;<strong>" . $newPassword . "</strong>";

    $emailTemplate = file_get_contents(Constants::INCLUDE_QUIOSGRAMA . 'View/Template/Emails/commonText.html');

    $finalMessage = str_replace("{!message}", $message, $emailTemplate);
    $finalMessage = str_replace("{!subject}", $subject, $finalMessage);
    $finalMessage = str_replace("{!quiosgramaUrl}", "http://" . $_SERVER['HTTP_HOST'], $finalMessage);
    $finalMessage = str_replace("{!year}", date('Y'), $finalMessage);

    $emailObj = new Email();

    $emailObj->setRecipients(array($user[0]->email . "|" . $user[0]->name));
    $emailObj->setSubject($subject);
    $emailObj->setMessage($finalMessage);
    $emailObj->setAuthentication(true);

    $emailObj->sendEmail();

    // Email end

    header("Location:/login/senha-enviada");
  } else {
    header("Location:/login/usuario-invalido");
  }
} else {
  header("Location:/login/usuario-invalido");
}

unset($login);
?>
