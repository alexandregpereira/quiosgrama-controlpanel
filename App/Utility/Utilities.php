<?php

namespace App\Utility;

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

/**
* This file contains usefull methods
*
* @author Jean Souza <contato@jeansouza.me>
*
*/

use \utilphp\util;

class Utilities extends util {

  private $_allowedBreadcrumb = array(
    "401.php:Erro 401:",
    "402.php:Erro 402:",
    "403.php:Erro 403:",
    "404.php:Erro 404:",
    "405.php:Erro 405:",
    "500.php:Erro 500:",
    "AlterMyPassword:Alterar Minha Senha:",
    "Client:Cliente:/listagem-de-clientes",
    "Complement:Complemento:/listagem-de-complementos",
    "details.php:Detalhes:",
    "Device:Dispositivo:/listagem-de-dispositivos",
    "Functionary:Funcion&aacute;rio:/listagem-de-funcionarios",
    "Dashboard:Dashboard:/dashboard",
    "Module:M&oacute;dulo:/listagem-de-modulos",
    "Poi:Poi:/listagem-de-pois",
    "Product:Produto:/listagem-de-produtos",
    "ProductType:Tipo de Produto:/listagem-de-tipos-de-produto",
    "register.php:Cadastro:",
    "Screen:Tela:/listagem-de-telas",
    "TableName:Mesa:/listagem-de-mesas",
    "User:Usu&aacute;rio:/listagem-de-usuarios",
    "UserPermission:Permiss&otilde;es de Usu&aacute;rios:/listagem-de-permissoes"
  );

  private $_notAllowedBreadcrumb = array(
    "View",
    "Resources",
    "Controller",
    "Model",
    "Db",
    "Dao",
    "Impl",
    "Errors",
    "Connection",
    "index.php"
  );

  public function getParameter($parameter) {
    if(isset($_POST[$parameter])) {
      return $_POST[$parameter];
    } elseif(isset($_GET[$parameter])) {
      return $_GET[$parameter];
    } else {
      return "";
    }
  }

  public function convertDateBR2US($oldDate) {
    $date = explode('/', $oldDate);
    return isset($date[2]) ? $date[2] . '-' . $date[1] . '-' . $date[0] : "";
  }

  public function convertDateUS2BR($oldDate) {
    $date = explode('-', $oldDate);
    return isset($date[2]) ? $date[2] . '/' . $date[1] . '/' . $date[0] : "";
  }

  public function convertDateTimeUS2BR($oldDate, $separator) {
    $dateTime = explode($separator, $oldDate);
    $date = explode('-', $dateTime[0]);
    return isset($date[2]) ? $date[2] . '/' . $date[1] . '/' . $date[0] . $separator . $dateTime[1] : "";
  }

  public function getDate() {
    return date('Y-m-d');
  }

  public function getTime() {
    return date('H:i:s');
  }

  public function removeSpecialCharacters($string) {
    return ereg_replace("[^a-zA-Z0-9_]", "", strtr($string, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
  }

  public function formatCNPJ($cnpj) {
    return preg_replace('@^(\d{2,3})(\d{3})(\d{3})(\d{4})(\d{2})$@', '$1.$2.$3/$4-$5', $cnpj);
  }

  public function formatCPF($cpf) {
    return preg_replace('@^(\d{3})(\d{3})(\d{3})(\d{2})$@', '$1.$2.$3-$4', $cpf);
  }

  public function formatDDDPhone($phone) {
    return preg_replace('@^(\d{2})(\d{4})(\d{4})$@', '($1) $2-$3', $phone);
  }

  public function formatPhone($phone) {
    return preg_replace('@^(\d{4})(\d{4})$@', '$1-$2', $phone);
  }

  public function formatCellphone($cellphone) {
    return preg_replace('@^(\d{5})(\d{4})$@', '$1-$2', $cellphone);
  }

  public function formatCEP($cep) {
    return preg_replace('@^(\d{5})(\d{3})$@', '$1-$2', $cep);
  }

  public function allowBreadcrumb($directory) {
    $allowed = true;

    foreach($this->_notAllowedBreadcrumb as $item) {
      if($item == $directory) {
        $allowed = false;
        break;
      }
    }

    if(strpos($directory, "?") !== false) {
      $allowed = false;
    }

    return $allowed;
  }

  public function replaceBreadcrumb($item) {
    $position = -1;

    for($i = 0; $i < count($this->_allowedBreadcrumb); $i++) {
      $valueArray = explode(":", $this->_allowedBreadcrumb[$i]);
      if($valueArray[0] == $item) {
        $position = $i;
        break;
      }
    }

    if($position != -1) {
      $breadcrumbArrayTemp = explode(":", $this->_allowedBreadcrumb[$position]);

      return isset($breadcrumbArrayTemp[1]) ? $breadcrumbArrayTemp[1] : "#";
    } else {
      return "#";
    }
  }

  public function getBreadcrumbLink($item) {
    $position = -1;

    for($i = 0; $i < count($this->_allowedBreadcrumb); $i++) {
      $valueArray = explode(":", $this->_allowedBreadcrumb[$i]);
      if($valueArray[0] == $item) {
        $position = $i;
        break;
      }
    }

    if($position != -1) {
      $breadcrumbArrayTemp = explode(":", $this->_allowedBreadcrumb[$position]);

      return isset($breadcrumbArrayTemp[2]) ? $breadcrumbArrayTemp[2] : "#";
    } else {
      return "#";
    }
  }

  public function replaceSpecialCharactersWithHTMLEntities($string) {
    $specialCharacters = array('á', 'à', 'ã', 'â', 'é', 'è', 'ê', 'í', 'ì', 'î', 'ó', 'ò', 'õ', 'ô', 'ú', 'ù', 'û', 'ç', 'Á', 'À', 'Ã', 'Â', 'É', 'È', 'Ê', 'Í', 'Ì', 'Î', 'Ó', 'Ò', 'Õ', 'Ô', 'Ú', 'Ù', 'Û', 'Ç', '´', '`', '~', '^');

    $HTMLEntities = array('&aacute;', '&agrave;', '&atilde;', '&acirc;', '&eacute;', '&egrave;', '&ecirc;', '&iacute;', '&igrave;', '&icirc;', '&oacute;', '&ograve;', '&otilde;', '&ocirc;', '&uacute;', '&ugrave;', '&ucirc;', '&ccedil;', '&Aacute;', '&Agrave;', '&Atilde;', '&Acirc;', '&Eacute;', '&Egrave;', '&Ecirc;', '&Iacute;', '&Igrave;', '&Icirc;', '&Oacute;', '&Ograve;', '&Otilde;', '&Ocirc;', '&Uacute;', '&Ugrave;', '&Ucirc;', '&Ccedil;', '&acute;', '&grave;', '&tilde;', '&circ;');

    for($i = 0; $i < count($specialCharacters); $i++) {
      $string = str_replace($specialCharacters[$i], $HTMLEntities[$i], $string);
    }

    return $string;
  }

  public function replaceHTMLEntitiesWithSpecialCharacters($string) {
    $specialCharacters = array('á', 'à', 'ã', 'â', 'é', 'è', 'ê', 'í', 'ì', 'î', 'ó', 'ò', 'õ', 'ô', 'ú', 'ù', 'û', 'ç', 'Á', 'À', 'Ã', 'Â', 'É', 'È', 'Ê', 'Í', 'Ì', 'Î', 'Ó', 'Ò', 'Õ', 'Ô', 'Ú', 'Ù', 'Û', 'Ç', '´', '`', '~', '^');

    $HTMLEntities = array('&aacute;', '&agrave;', '&atilde;', '&acirc;', '&eacute;', '&egrave;', '&ecirc;', '&iacute;', '&igrave;', '&icirc;', '&oacute;', '&ograve;', '&otilde;', '&ocirc;', '&uacute;', '&ugrave;', '&ucirc;', '&ccedil;', '&Aacute;', '&Agrave;', '&Atilde;', '&Acirc;', '&Eacute;', '&Egrave;', '&Ecirc;', '&Iacute;', '&Igrave;', '&Icirc;', '&Oacute;', '&Ograve;', '&Otilde;', '&Ocirc;', '&Uacute;', '&Ugrave;', '&Ucirc;', '&Ccedil;', '&acute;', '&grave;', '&tilde;', '&circ;');

    for($i = 0; $i < count($HTMLEntities); $i++) {
      $string = str_replace($HTMLEntities[$i], $specialCharacters[$i], $string);
    }

    return $string;
  }

  public function replaceSpecialCharactersWithCommon($string) {
    $specialCharacters = array('á', 'à', 'ã', 'â', 'é', 'è', 'ê', 'í', 'ì', 'î', 'ó', 'ò', 'õ', 'ô', 'ú', 'ù', 'û', 'ç', 'Á', 'À', 'Ã', 'Â', 'É', 'È', 'Ê', 'Í', 'Ì', 'Î', 'Ó', 'Ò', 'Õ', 'Ô', 'Ú', 'Ù', 'Û', 'Ç', '´', '`', '~', '^');

    $commonCharacters = array('a', 'a', 'a', 'a', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'c', 'A', 'A', 'A', 'A', 'E', 'E', 'E', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'C', '', '', '', '');

    for ($i = 0; $i < count($specialCharacters); $i++) {
      $string = str_replace($specialCharacters[$i], $commonCharacters[$i], $string);
    }

    return $string;
  }

  public function getRandomString() {
    $basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    $return = "";

    for ($count = 0; 40 > $count; $count++) {
      $return.= $basic[rand(0, strlen($basic) - 1)];
    }

    return $return;
  }

  public function antiSqlinjection($string) {
    $string = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"), "", $string);
    $string = strip_tags($string);
    $string = addslashes($string);
    return $string;
  }

  public function alertJs($msg) {
    echo "<!DOCTYPE html><html><head><meta charset=\"UTF-8\"><link href=\"/Resources/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\" /><link href=\"/Resources/dist/css/vex.css\" rel=\"stylesheet\" type=\"text/css\" /><link href=\"/Resources/dist/css/vex-theme-os.css\" rel=\"stylesheet\" type=\"text/css\" /><script src=\"/Resources/plugins/jQuery/jQuery-2.1.3.min.js\" type=\"text/javascript\"></script><script src=\"/Resources/dist/js/vex.combined.min.js\" type=\"text/javascript\"></script><script>vex.defaultOptions.className = 'vex-theme-os';</script></head><body onclick=\"window.history.go(-1);\"><script>"
    . "vex.dialog.alert('" . $msg . "');"
    . "</script></body>"
    . "</html>";
  }

  public function returnAbbreviatedMonthByNumber($num) {
    $arrayNum = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
    $arrayAbbrev = array('Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez');

    $pos = array_search($num, $arrayNum);

    return $arrayAbbrev[$pos];
  }

  public function returnMonthByNumber($num) {
    $arrayNum = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
    $arrayAbbrev = array('Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

    $pos = array_search($num, $arrayNum);

    return $arrayAbbrev[$pos];
  }

  public function printArray($array = array()) {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
  }

}

?>
