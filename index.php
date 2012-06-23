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
  $param_input = array();
  foreach($service['input'] as $input_key=>$input_opt){
    $input[$input_key] = "xsd:".$input_opt['type'];
    $param_input[] = "[".$input_key."] => ".$input_opt['type']." : ".$input_opt['info'];
  }
  
  $method = $classname.".{$service_name}";
  $output = array('return' => 'xsd:string');
  $soapaction = "{$namespace}#".$method;
  $style = 'rpc';
  $use = 'encoded'; 
  
  $desc = "<br />";
  $desc .= "<blockquote>";
  $desc .= $service['desc'] . "<br />";
  $desc .= "From : " . $yml['ws']['unit'] . "<br />";
  $desc .= "To : DataCenter ITB <br />";
  $desc .= "Parameter : ";
  $desc .= "<blockquote>";
  $desc .= implode("<br />",$param_input);
  $desc .= "</blockquote>";
  $desc .= "Output : JSON string";
  $desc .= "<blockquote>";
  $desc .= "[err_no] => integer : Kode error ( 0 jika tidak ada error) <br />";
  $desc .= "[err_teks] => string : Teks error ( kosong jika nilai field [err_no]=0 ) <br />";
  if($service['type']==singledata) {
    $desc .= "[data] => sebuah data => ".$service['fields'][0]."<br />";
  } elseif($service['type']==singlerow) {
    $desc .= "[data] => sebuah array data <br />";
    $desc .= nbsp(4)."array ("."<br />";
    $i=0;
    foreach ($service['fields'] as $field){
      $desc .= nbsp(8)."[".$i++."] => [data ".$field."]<br />";
    }
    $desc .= nbsp(4).")"."<br />";
  } else {
    $desc .= "[data] => sekumpulan array data <br />";
    $desc .= nbsp(4)."array ("."<br />";
    $desc .= nbsp(8)."[idx] => array ("."<br />";
    $i=0;
    foreach ($service['fields'] as $field){
      $desc .= nbsp(12)."[".$i++."] => [data ".$field."]<br />";
    }
    $desc .= nbsp(8).")"."<br />";
    $desc .= nbsp(4).")"."<br />";
  }
  $desc .= "[fields] => informasi fields data yang di-return-kan ";
  if($service['type']==singledata) {
    $desc .= "=> ".$service['fields'][0]."<br />";
  } else {
    $desc .= "<br />";
    $desc .= nbsp(4)."array ("."<br />";
    $i=0;
    foreach ($service['fields'] as $field){
      $desc .= nbsp(8)."[".$i++."] => ".$field."<br />";
    }
    $desc .= nbsp(4).")"."<br />";
  }
  $desc .= "</blockquote>";
  $desc .= "</blockquote>";
  

  $ws_serv->register($method, $input, $output, $namespace, $soapaction, $style, $use, $desc);
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : "";
$ws_serv->service($HTTP_RAW_POST_DATA);
