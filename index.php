<?php
/**
 * index.php ini adalah halaman yang akan menampilkan web service 
 * yang memanfaatkan nusoap dan ratno_ws (mekanisme pembuatan webservice paling simple)
 * 
 * @author Ratno Putro Sulistiyono | ratno@comlabs.itb.ac.id | ratno@knoqdown.com 
 */

include_once 'spyc.php';
include_once 'nusoap/nusoap.php';
include_once 'func.php';

$yml = Spyc::YAMLLoad("ws.yml");
$namespace = "urn:" . $yml['ws']['nama'];
$classname = $yml['ws']['class'];

include_once "dynamic_ws.php";

$ws_serv = new soap_server();
$ws_serv->soap_defencoding = 'UTF-8';
$ws_serv->configureWSDL($yml['ws']['nama'], $namespace);
$ws_serv->wsdl->ports = array($yml['ws']['nama'] . 'Port' => array(
        "binding" => $yml['ws']['nama'] . "Binding",
        "location" => $yml['ws']['ns'],
        "bindingType" => "http://schemas.xmlsoap.org/wsdl/soap/"
        ));

foreach ($yml['services'] as $service_name => $service) {
  $input = array();
  foreach($service['input'] as $input_key=>$input_type){
    $input[$input_key] = "xsd:$input_type";
  }

  $method = $classname.".{$service_name}";
  $output = array('return' => 'xsd:string');
  $soapaction = "{$namespace}#".$method;
  $style = 'rpc';
  $use = 'encoded';
  $desc_func = $service['desc_func'];

  $ws_serv->register($method, $input, $output, $namespace, $soapaction, $style, $use, $desc_func($service['desc'], $yml['ws']['unit']));
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : "";
$ws_serv->service($HTTP_RAW_POST_DATA);
