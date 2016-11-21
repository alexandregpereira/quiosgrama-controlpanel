<?php

namespace App\Constants;

/**
* @author Jean Souza
*
* This file is included without the path (include_once(getenv('QUIOSGRAMA_SYSTEM_PATH') . 'Constants/Constants.php';), his path is set in the 'include_path' variable, in the .htaccess file.
*
*/

class Constants {

	const LICENCE_VALIDATOR_URL = 'http://localhost:9090/Quioservice/sync/validateLicence'; //External validation
	const LOCAL_LICENCE_VALIDATOR_URL = 'http://localhost:9090/Quioservice/sync/validateLicence'; //Local validation, test porpose

	const DIR_IMAGE_PRODUCT_TYPE_BUTTON = "/Resources/Img/productType/buttonImage";
	const DIR_IMAGE_PRODUCT_TYPE_ICON = "/Resources/Img/productType/iconImage";
	const DIR_IMAGE_PRODUCT_TYPE_TAB = "/Resources/Img/productType/tabImage";
	const DIR_IMAGE_COMPLEMENT = "/Resources/Img/complement";
	const DIR_IMAGE_POI = "/Resources/Img/poi";

	// PATHS - end

	// ENCRYPT KEY - begin

	const KEY_CRYPT = "keyEncryptQuiosgrama";

	// ENCRYPT KEY - end

	// DATABASE ACCESS DATA - begin

	const QUIOSGRAMA_DB_HOST = "127.0.0.1";
	const QUIOSGRAMA_DB_PORT = "3306";
	const QUIOSGRAMA_DB_USER = "root";
	const QUIOSGRAMA_DB_PASS = "quiosgramaMySql";

	const SCHEMA_MAIN = 'quiosgrama';

	// DATABASE ACCESS DATA - end

	// EMAIL ACCESS DATA - begin

	const MAIL_HOST = "";
	const MAIL_PORT = "";
	const MAIL_USER = "";
	const MAIL_PASS = "";
	const MAIL_SECURE = "";
	const MAIL_EMAIL_SEN = "";
	const MAIL_NAME_SEN = "Quiosgrama";
	const MAIL_EMAIL_REC = "";
	const MAIL_NAME_REC = "";

	const DEFAULT_PASSWORD = '123456';

	// EMAIL ACCESS DATA - end

	// SOCKET DATA
	const PORT_SOCKET = "9876";

	const TEMPLATE_MAIN = '/View/Template/Main.tpl';
	const TEMPLATE_LOGIN = '/View/Template/Login.tpl';
	const TEMPLATE_COMMON_EMAIL_TEXT = '/View/Template/Emails/textoComum.html';
	const TEMPORARY_DIR = '/Resources/Temp/';
	const PCLZIP_TEMPORARY_DIR = '/Resources/Temp/';
	const FPDF_FONTPATH = '/Resources/fpdf';

}
