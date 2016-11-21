<?php

/**
 * @author Jean Souza
 */
session_start();
ob_start();

$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use App\Constants\Constants;
use App\Model\Product;
use App\Db\Dao\Impl\ProductDaoImpl;
use App\Model\Login;
use App\Utility\Utilities;
use App\WS\SendPushSocket;
use App\WS\Core;

$utilities = new Utilities();
$login = new Login();

if ($login->isUserLogged()) {

    $action = $utilities->getParameter('action');

    switch ($action) {
        case 'add':

        $dao = new ProductDaoImpl();
        $product = $dao->listOneByName($utilities->getParameter("name"));

        if (count($product) == 0) {
            $object = new Product();
            $object->setCode($utilities->getParameter("code"));
            $object->setName($utilities->getParameter("name"));
            $object->setPrice(str_replace(',', '.', $utilities->getParameter("price")));
            $object->setProductType($utilities->getParameter("productType"));
            $object->setPopularity($utilities->getParameter("popularity"));

            $description = $utilities->getParameter("description");
            if($description != ""){
              $object->setDescription($description);
            }

            $ret = $dao->add($object);

            if($ret > 0){
              $obj = $dao->listOne($ret);
              $list = array($obj[0]);
              $core = new Core();
              $array = $core->buildProductJsonArray($list);
              $pushSocket = new SendPushSocket();
              $pushSocket->sendPush($array, 6);

              header("Location:/listagem-de-produtos");
            } else {
              $utilities->alertJs("Ocorreu um erro no processo, tente novamente.");
            }
        } else {
            $utilities->alertJs("J&aacute; existe um produto com esse nome!");
        }

        break;

        case 'upd':

        $dao = new ProductDaoImpl();
        $product = $dao->listOneByName($utilities->getParameter("name"));

        if ((count($product) == 0) || (count($product) == 1 && $product[0]->id == $utilities->getParameter("id"))) {
            $object = new Product();
            $object->setId($utilities->getParameter("id"));
            $object->setCode($utilities->getParameter("code"));
            $object->setName($utilities->getParameter("name"));
            $object->setPrice(str_replace(',', '.', $utilities->getParameter("price")));
            $object->setProductType($utilities->getParameter("productType"));
            $object->setPopularity($utilities->getParameter("popularity"));

            $description = $utilities->getParameter("description");
            if($description != ""){
              $object->setDescription($description);
            }

            $ret = $dao->update($object);

            if($ret){
              $obj = $dao->listOne($object->getId());
              $list = array($obj[0]);
              $core = new Core();
              $array = $core->buildProductJsonArray($list);
              $pushSocket = new SendPushSocket();
              $pushSocket->sendPush($array, 6);

              header("Location:/listagem-de-produtos");
            } else {
              $utilities->alertJs("Ocorreu um erro no processo, tente novamente.");
            }
        } else {
            $utilities->alertJs("J&aacute; existe um produto com esse nome!");
        }

        break;

        case 'del':
        if (trim($utilities->getParameter("id")) != "") {
            $dao = new ProductDaoImpl();
            $ret = $dao->remove($utilities->getParameter("id"));

            if($ret){
              $message['status'] = "Object Deleted";
              $array = array($message);
              $pushSocket = new SendPushSocket();
              $pushSocket->sendPush($array, -1);

              header("Location:/listagem-de-produtos");
            } else {
              $utilities->alertJs("N&atilde;o &eacute; poss&iacute;vel excluir esse item, ele J&aacute esta sendo usado.");
            }
        }
        break;

        default:
        header("Location:/listagem-de-produtos");
        break;
    }
} else {
    header('Location:/login');
}

unset($login);
?>
