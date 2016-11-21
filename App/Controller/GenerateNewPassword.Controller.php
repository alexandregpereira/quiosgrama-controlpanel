<?php

/**
 * @author Jean Souza
 */
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Login;
use App\Model\Email;
use App\Model\User;
use App\Db\Dao\Impl\UserDaoImpl;

$utilities = new Utilities();
$daoUser = new UserDaoImpl();

$user = $daoUser->listOneByEmail(trim($utilities->getParameter("email")));

if (count($user) > 0) {

    $login = new Login();

    $novaPassword = $login->gerarPassword();

    $userObj = new User();
    $userObj->setColaborador($user[0]->colaborador);
    $userObj->setId($user[0]->id);
    $userObj->setNivel($user[0]->nivel);
    $userObj->setPassword($login->encriptarPassword($novaPassword));
    $userObj->setStatus($user[0]->status);
    $userObj->setUser($user[0]->user);

    $daoUser->update($userObj);

    // Envio de emails

    $template = file_get_contents(Constants::TEMPLATE_EMAIL_TEXTO_COMUM);

    $email = new Email();
    $email->setAssunto("Quiosgrama - Nova Password");

    $auxTemplate = str_replace("{!assunto}", "Quiosgrama - Nova Password", $template);
    $auxTemplate = str_replace("{!mensagem}", "Sua nova password foi gerada com sucesso, posteriormente ela pode ser alterada.<br/><br/>Password: <strong>$novaPassword</strong>", $auxTemplate);

    $email->setMensagem($auxTemplate);
    $email->setAutenticacao(true);
    $email->setEmailsDestinatarios(array(trim($utilities->getParameter("email")) . '|'));
    $email->enviarEmail();

    // Envio de emails

    header("Location:/E-Office/workflow/View/Login?msg=passwordEnviada");
} else {
    header('Location:/E-Office/workflow/View/Login?msg=emailInexistente');
}
?>
