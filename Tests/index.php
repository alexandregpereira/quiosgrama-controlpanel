<?php
echo getenv('QUIOSGRAMA_SYSTEM_PATH');

/*include_once(getenv('QUIOSGRAMA_SYSTEM_PATH') . 'Constants/Constants.php');
include_once(INCLUDE_QUIOSGRAMA . 'Db/Dao/Impl/ClientDaoImpl.php');
include_once(INCLUDE_QUIOSGRAMA . 'Db/Dao/Impl/FunctionaryDaoImpl.php');
include_once(INCLUDE_QUIOSGRAMA . 'Db/Dao/Impl/DeviceDaoImpl.php');

$jean = json_decode('{"clientList":[{"cpf":"41562865846","id":"3","name":"Alexandre Gomes Pereira","phone":"1198465914","present":"0","presentFlag":"false","temp":"0","tempFlag":"false"}],"complementList":[{"description":" ","id":"1","price":"0.0"},{"description":"copo","drawable":"ic_complement_ice","id":"2","price":"0.0","typeSet":[{"buttonImage":"selector_btn_soda","colorId":"#0968f7","id":"3","imageInfo":"ic_info_soda","name":"Refrescantes","priority":"3","tabImage":"tab_soda"},{"buttonImage":"selector_btn_beer","colorId":"#e8fc00","id":"1","imageInfo":"ic_info_beer","name":"Cerveja","priority":"1","tabImage":"tab_beer"},{"buttonImage":"selector_btn_drink","colorId":"#18dd96","id":"2","imageInfo":"ic_info_drink","name":"Coqueteis","priority":"2","tabImage":"tab_drink"}]},{"description":"copo c/ gelo","drawable":"ic_complement_ice","id":"3","price":"0.0"},{"description":"copo c/ gelo; ","id":"4","price":"0.0"},{"description":"copo c/ gelo; copo; ","id":"5","price":"0.0"},{"description":"copo c/ gelo; laranja; ","id":"6","price":"0.0"},{"description":"copo c/ gelo; limÃ£o; ","id":"7","price":"0.0"},{"description":"laranja","drawable":"ic_complement_ice","id":"8","price":"0.0"},{"description":"limÃ£o","drawable":"ic_complement_ice","id":"9","price":"0.0"},{"description":"s aÃ§ucar","drawable":"ic_complement_ice","id":"10","price":"0.0"},{"description":"s gelo","drawable":"ic_complement_ice","id":"11","price":"0.0"},{"description":"s gelo; ","id":"12","price":"0.0"},{"description":"s gelo; copo c/ gelo; limÃ£o; ","id":"13","price":"0.0"},{"description":"s gelo; limÃ£o; copo c/ gelo; ","id":"14","price":"0.0"},{"description":"s alface; ","id":"24","price":"0.0"}],"functionaryList":[{"device":{"id":"3","imei":"359281050923526","registrationId":"APA91bH2HrjAmdAskvfTz8-wNlQvZ5F4C_TYpADNLYsjLZ685ELqqDyZphL4VgNRyftSgEpcBKLy_-PGH37xMIJG82EymXkm_C7cyg6I4qYSjuT0wNAc818L35nbOec_V5AAQLQB19TI"},"id":"1","imei":"359281050923526","name":"Alexandre"},{"device":{"id":"2","imei":"356489055788223","registrationId":"APA91bE8mnuA4yhJi6zNobNYPlfnc_36qQfit8Vxw8WjDej7_DM9ZoZMD0yIhroKIJe8JII3AGTh1xpUIi5wliiZA3kvrlWBb22Ih_mW4F8Wy4BpCBLE91-g5BrMI2_ZwCLQYs15d1_y"},"id":"2","imei":"356489055788223","name":"Jean"},{"device":{"id":"1","imei":"355470061060832","registrationId":"APA91bFxGgl92_XqIuqVi5S-IVFf91t5QVxo6FQXFxk-cUMPAG4FefCXZgbeBL8nCNdMBSyaHNL0hJjkyZQzCiJjVlQkkHicp3vZGSOxSlPfTA-nN0oZ2rMqYHvvF0xqTlddq72kvYxg"},"id":"3","imei":"355470061060832","name":"Vinicius"}],"poiList":[{"idPoi":"1","image":"ic_poi1","mapPageNumber":"2","name":"Shopping","poiTime":"Aug 3, 2015 8:15:37 PM","waiterAlterPoi":{"device":{"id":"3","imei":"359281050923526","registrationId":"APA91bH2HrjAmdAskvfTz8-wNlQvZ5F4C_TYpADNLYsjLZ685ELqqDyZphL4VgNRyftSgEpcBKLy_-PGH37xMIJG82EymXkm_C7cyg6I4qYSjuT0wNAc818L35nbOec_V5AAQLQB19TI"},"id":"1","imei":"359281050923526","name":"Alexandre"},"xPosDpi":"250","yPosDpi":"179"},{"idPoi":"2","image":"ic_poi1","mapPageNumber":"1","name":"Barraca do seu ZÃ©","poiTime":"Aug 3, 2015 8:20:13 PM","waiterAlterPoi":{"device":{"id":"3","imei":"359281050923526","registrationId":"APA91bH2HrjAmdAskvfTz8-wNlQvZ5F4C_TYpADNLYsjLZ685ELqqDyZphL4VgNRyftSgEpcBKLy_-PGH37xMIJG82EymXkm_C7cyg6I4qYSjuT0wNAc818L35nbOec_V5AAQLQB19TI"},"id":"1","imei":"359281050923526","name":"Alexandre"},"xPosDpi":"6","yPosDpi":"198"},{"idPoi":"3","image":"ic_poi1","mapPageNumber":"1","name":"Milho","poiTime":"Aug 3, 2015 8:19:36 PM","waiterAlterPoi":{"device":{"id":"3","imei":"359281050923526","registrationId":"APA91bH2HrjAmdAskvfTz8-wNlQvZ5F4C_TYpADNLYsjLZ685ELqqDyZphL4VgNRyftSgEpcBKLy_-PGH37xMIJG82EymXkm_C7cyg6I4qYSjuT0wNAc818L35nbOec_V5AAQLQB19TI"},"id":"1","imei":"359281050923526","name":"Alexandre"},"xPosDpi":"278","yPosDpi":"172"},{"idPoi":"4","image":"ic_poi1","mapPageNumber":"1","name":"Raspadinha","poiTime":"Aug 3, 2015 8:19:15 PM","waiterAlterPoi":{"device":{"id":"3","imei":"359281050923526","registrationId":"APA91bH2HrjAmdAskvfTz8-wNlQvZ5F4C_TYpADNLYsjLZ685ELqqDyZphL4VgNRyftSgEpcBKLy_-PGH37xMIJG82EymXkm_C7cyg6I4qYSjuT0wNAc818L35nbOec_V5AAQLQB19TI"},"id":"1","imei":"359281050923526","name":"Alexandre"},"xPosDpi":"131","yPosDpi":"121"},{"idPoi":"5","image":"ic_poi1","mapPageNumber":"2","name":"Rei dos Pasteis","poiTime":"Aug 3, 2015 8:19:24 PM","waiterAlterPoi":{"device":{"id":"3","imei":"359281050923526","registrationId":"APA91bH2HrjAmdAskvfTz8-wNlQvZ5F4C_TYpADNLYsjLZ685ELqqDyZphL4VgNRyftSgEpcBKLy_-PGH37xMIJG82EymXkm_C7cyg6I4qYSjuT0wNAc818L35nbOec_V5AAQLQB19TI"},"id":"1","imei":"359281050923526","name":"Alexandre"},"xPosDpi":"280","yPosDpi":"334"},{"idPoi":"6","image":"ic_poi1","mapPageNumber":"0","name":"Teste","poiTime":"Aug 10, 2015 02:27:13 PM","xPosDpi":"0","yPosDpi":"0"}],"productList":[{"code":"1","id":"1","name":"Brahma","price":"4.0","quantity":"0","type":{"buttonImage":"selector_btn_beer","colorId":"#e8fc00","id":"1","imageInfo":"ic_info_beer","name":"Cerveja","priority":"1","tabImage":"tab_beer"}},{"code":"2","id":"2","name":"Refrigerante Lata","price":"4.0","quantity":"0","type":{"buttonImage":"selector_btn_soda","colorId":"#0968f7","id":"3","imageInfo":"ic_info_soda","name":"Refrescantes","priority":"3","tabImage":"tab_soda"}},{"code":"3","id":"3","name":"agua Mineral","price":"2.0","quantity":"0","type":{"buttonImage":"selector_btn_soda","colorId":"#0968f7","id":"3","imageInfo":"ic_info_soda","name":"Refrescantes","priority":"3","tabImage":"tab_soda"}},{"code":"4","id":"4","name":"agua Mineral c/ gas","price":"3.0","quantity":"0","type":{"buttonImage":"selector_btn_soda","colorId":"#0968f7","id":"3","imageInfo":"ic_info_soda","name":"Refrescantes","priority":"3","tabImage":"tab_soda"}},{"code":"5","id":"5","name":"Coco gelado","price":"5.0","quantity":"0","type":{"buttonImage":"selector_btn_soda","colorId":"#0968f7","id":"3","imageInfo":"ic_info_soda","name":"Refrescantes","priority":"3","tabImage":"tab_soda"}},{"code":"6","id":"6","name":"Suco del valle","price":"4.0","quantity":"0","type":{"buttonImage":"selector_btn_soda","colorId":"#0968f7","id":"3","imageInfo":"ic_info_soda","name":"Refrescantes","priority":"3","tabImage":"tab_soda"}},{"code":"7","id":"7","name":"Gatorade","price":"6.0","quantity":"0","type":{"buttonImage":"selector_btn_soda","colorId":"#0968f7","id":"3","imageInfo":"ic_info_soda","name":"Refrescantes","priority":"3","tabImage":"tab_soda"}},{"code":"8","id":"8","name":"Cerveja sem alcool","price":"4.0","quantity":"0","type":{"buttonImage":"selector_btn_beer","colorId":"#e8fc00","id":"1","imageInfo":"ic_info_beer","name":"Cerveja","priority":"1","tabImage":"tab_beer"}},{"code":"9","id":"9","name":"Capeta","price":"12.0","quantity":"0","type":{"buttonImage":"selector_btn_drink","colorId":"#18dd96","id":"2","imageInfo":"ic_info_drink","name":"Coqueteis","priority":"2","tabImage":"tab_drink"}},{"code":"10","id":"10","name":"Espanhola","price":"10.0","quantity":"0","type":{"buttonImage":"selector_btn_drink","colorId":"#18dd96","id":"2","imageInfo":"ic_info_drink","name":"Coqueteis","priority":"2","tabImage":"tab_drink"}},{"code":"11","id":"11","name":"Cuba","price":"10.0","quantity":"0","type":{"buttonImage":"selector_btn_drink","colorId":"#18dd96","id":"2","imageInfo":"ic_info_drink","name":"Coqueteis","priority":"2","tabImage":"tab_drink"}},{"code":"12","id":"12","name":"Coco louco","price":"12.0","quantity":"0","type":{"buttonImage":"selector_btn_drink","colorId":"#18dd96","id":"2","imageInfo":"ic_info_drink","name":"Coqueteis","priority":"2","tabImage":"tab_drink"}},{"code":"13","id":"13","name":"Camarao empanado","price":"35.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"14","id":"14","name":"Camarao a paulista","price":"50.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"15","id":"15","name":"Lula empanada","price":"40.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"16","id":"16","name":"Lula alho e oleo","price":"45.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"17","id":"17","name":"Isca de abadejo","price":"50.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"18","id":"18","name":"Isca de cacao","price":"35.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"19","id":"19","name":"Peixe Porquinho","price":"35.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"20","id":"20","name":"Peixe Manjuba","price":"35.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"21","id":"21","name":"Isca de peixe da epoca","price":"35.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"22","id":"22","name":"Bolinho de bacalhau (15 unid)","price":"25.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"23","id":"23","name":"Mini kibe (20 unid)","price":"20.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"24","id":"24","name":"Batata frita","price":"20.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"25","id":"25","name":"Mandioca","price":"20.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"26","id":"26","name":"Frango a passarinho","price":"30.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"27","id":"27","name":"Kibe c/ provolone (20 unid)","price":"20.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"28","id":"28","name":"Provolone Frito","price":"20.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"29","id":"29","name":"Torresmo","price":"25.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"30","id":"30","name":"Bolinho de arraia","price":"30.0","quantity":"0","type":{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"}},{"code":"31","id":"31","name":"Itaipava","price":"4.0","quantity":"0","type":{"buttonImage":"selector_btn_beer","colorId":"#e8fc00","id":"1","imageInfo":"ic_info_beer","name":"Cerveja","priority":"1","tabImage":"tab_beer"}},{"code":"32","id":"32","name":"Skol","price":"4.0","quantity":"0","type":{"buttonImage":"selector_btn_beer","colorId":"#e8fc00","id":"1","imageInfo":"ic_info_beer","name":"Cerveja","priority":"1","tabImage":"tab_beer"}},{"code":"33","id":"33","name":"H2O","price":"4.0","quantity":"0","type":{"buttonImage":"selector_btn_soda","colorId":"#0968f7","id":"3","imageInfo":"ic_info_soda","name":"Refrescantes","priority":"3","tabImage":"tab_soda"}},{"code":"89","description":"lalala","id":"35","name":"teste jean","price":"52.32","quantity":"0","type":{"buttonImage":"selector_btn_beer","colorId":"#e8fc00","id":"1","imageInfo":"ic_info_beer","name":"Cerveja","priority":"1","tabImage":"tab_beer"}},{"code":"34","description":".","id":"36","name":"X Salada","price":"5.0","quantity":"0","type":{"buttonImage":"selector_btn_snack","colorId":"#e28b09","id":"5","imageInfo":"ic_info_snack","name":"Lanches","priority":"6","tabImage":"tab_snack"}},{"code":"35","description":"a","id":"37","name":"Croissant","price":"4.0","quantity":"0","type":{"buttonImage":"selector_btn_salty","colorId":"#f75009","id":"6","imageInfo":"ic_info_salty","name":"Salgados","priority":"5","tabImage":"tab_salty"}}],"productTypeList":[{"buttonImage":"selector_btn_beer","colorId":"#e8fc00","id":"1","imageInfo":"ic_info_beer","name":"Cerveja","priority":"1","tabImage":"tab_beer"},{"buttonImage":"selector_btn_drink","colorId":"#18dd96","id":"2","imageInfo":"ic_info_drink","name":"Coqueteis","priority":"2","tabImage":"tab_drink"},{"buttonImage":"selector_btn_soda","colorId":"#0968f7","id":"3","imageInfo":"ic_info_soda","name":"Refrescantes","priority":"3","tabImage":"tab_soda"},{"buttonImage":"selector_btn_portion","colorId":"#af3a08","id":"4","imageInfo":"ic_info_portion","name":"Porcoes","priority":"4","tabImage":"tab_portions"},{"buttonImage":"selector_btn_snack","colorId":"#e28b09","id":"5","imageInfo":"ic_info_snack","name":"Lanches","priority":"6","tabImage":"tab_snack"},{"buttonImage":"selector_btn_salty","colorId":"#f75009","id":"6","imageInfo":"ic_info_salty","name":"Salgados","priority":"5","tabImage":"tab_salty"}],"tableList":[{"id":"28","mapPageNumber":"0","number":"50","tableTime":"Aug 11, 2015 2:07:22 PM","waiterAlterTable":{"device":{"id":"3","imei":"359281050923526","registrationId":"APA91bH2HrjAmdAskvfTz8-wNlQvZ5F4C_TYpADNLYsjLZ685ELqqDyZphL4VgNRyftSgEpcBKLy_-PGH37xMIJG82EymXkm_C7cyg6I4qYSjuT0wNAc818L35nbOec_V5AAQLQB19TI"},"id":"1","imei":"359281050923526","name":"Alexandre"},"xPosInDpi":"25","yPosDpi":"0"},{"id":"33","mapPageNumber":"0","number":"51","tableTime":"Aug 11, 2015 3:38:49 PM","waiterAlterTable":{"device":{"id":"3","imei":"359281050923526","registrationId":"APA91bH2HrjAmdAskvfTz8-wNlQvZ5F4C_TYpADNLYsjLZ685ELqqDyZphL4VgNRyftSgEpcBKLy_-PGH37xMIJG82EymXkm_C7cyg6I4qYSjuT0wNAc818L35nbOec_V5AAQLQB19TI"},"id":"1","imei":"359281050923526","name":"Alexandre"},"xPosInDpi":"25","yPosDpi":"0"}]}');

//echo "<pre>";
//print_r($jean->clientList);
//$jean;
//echo "</pre>";

//$dao = new ClientDaoImpl();
//$dao = new FunctionaryDaoImpl();
$dao = new DeviceDaoImpl();

//echo json_encode($dao->listAllWS());

function teste() {
  echo "deu certo";
}

$var = "teste";

//$var();

//echo serialize(array("lala")) == serialize(array("lala"));

/*echo "<pre>";
print_r(json_decode('	  [
         [
            {
               "functionary":"Caixa",
               "time":"Oct 19, 2015 5:00:15 PM",
               "request":"6c72b6b8-2f4b-437e-946d-2616ccee503d",
               "table_number":"1"
            },
            {
               "functionary":"Jean",
               "time":"Oct 19, 2015 4:38:23 PM",
               "request":"aea42f6c-cdb1-43d8-9c74-632923d78e52",
               "table_number":"1"
            },
            {
               "functionary":"Caixa",
               "time":"Oct 19, 2015 4:34:42 PM",
               "request":"af907cc6-5f86-4b79-bb8f-b883e1012dc6",
               "table_number":"1"
            }
         ],
         [
            {
               "functionary":"Caixa",
               "time":"Oct 20, 2015 11:57:44 PM",
               "request":"2858df08-d351-441a-a768-e4a47a710d9d",
               "table_number":"2"
            }
         ],
         [
            {
               "functionary":"Jean",
               "time":"Oct 19, 2015 5:09:33 PM",
               "request":"02ddff79-ddba-45bd-a525-30c2200b0907",
               "table_number":"3"
            },
            {
               "functionary":"Caixa",
               "time":"Oct 19, 2015 7:19:36 PM",
               "request":"5f217cc5-b6ae-4580-878f-ef94e9545b61",
               "table_number":"3"
            },
            {
               "functionary":"Caixa",
               "time":"Oct 19, 2015 7:18:49 PM",
               "request":"71d97e8c-dc1c-4140-9aef-be089bdad9c2",
               "table_number":"3"
            },
            {
               "functionary":"Caixa",
               "time":"Oct 19, 2015 7:19:55 PM",
               "request":"e30e91ca-38b4-461f-94c9-0d271f7ca5d5",
               "table_number":"3"
            }
         ],
         [
            {
               "functionary":"Jean",
               "time":"Oct 19, 2015 5:03:12 PM",
               "request":"c436bb85-245d-4364-b908-c8c47c8af68f",
               "table_number":"8"
            }
         ],
         [
            {
               "functionary":"Jean",
               "time":"Oct 19, 2015 5:05:58 PM",
               "request":"8ac330aa-5ba1-4a6a-bf09-8e9705292683",
               "table_number":"15"
            }
         ]
      ]'));

echo "</pre>";

$var = "{
  \"method\": \"closeTable\",
  \"value\": {
    \"number\": 2,
    \"closed\": false
  }
}";

$json = json_decode($var);
$method = $json->{'method'};
$value = $json->{'value'};

echo $value->{'closed'};
*/
?>
