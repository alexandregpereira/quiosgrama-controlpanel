<?php

/**
 * @author Jean Souza
 */

namespace App\Model;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

class Email {

    private $_recipients;
    private $_subject;
    private $_message;
    private $_authentication;

    public function getRecipients() {
        return $this->_recipients;
    }

    public function getSubject() {
        return $this->_subject;
    }

    public function getMessage() {
        return $this->_message;
    }

    public function getAuthentication() {
        return $this->_authentication;
    }

    public function setRecipients($recipients) {
        $this->_recipients = $recipients;
    }

    public function setSubject($subject) {
        $this->_subject = $subject;
    }

    public function setMessage($message) {
        $this->_message = $message;
    }

    public function setAuthentication($authentication) {
        $this->_authentication = $authentication;
    }

    public function sendEmail() {
        $mail = new PHPMailer();
        $mail->Debug = false;
        $mail->SMTPKeepAlive = true;
        $mail->IsSMTP();
        $mail->Host = Constants::MAIL_HOST;
        $mail->Port = Constants::MAIL_PORT;
        if($this->getAuthentication()) {
            $mail->SMTPAuth = true;
            $mail->Username = Constants::MAIL_USER;
            $mail->Password = Constants::MAIL_PASS;
        }
        $mail->SMTPSecure = Constants::MAIL_SECURE;
        $mail->From = Constants::MAIL_EMAIL_SEN;
        $mail->FromName = Constants::MAIL_NAME_SEN;
        foreach ($this->getRecipients() as $email) {
            $emailAux = explode('|', $email);
            $mail->AddAddress($emailAux[0], $emailAux[1]);
        }
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $this->getSubject();
        $mail->Body = $this->getMessage();
        $mail->AltBody = $this->getMessage();
        $enviado = $mail->Send();
        $mail->ClearAllRecipients();
        $mail->ClearAttachments();
        if ($enviado) {
            echo "";
        } else {
            echo $mail->ErrorInfo;
        }
    }

}
