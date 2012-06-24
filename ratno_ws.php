<?php
/**
 * ratno_ws ini bertujuan untuk mempermudah 
 * implementasi pembuatan webservices BLU USDI ITB
 * 
 * class ini harus diextend oleh subclass webservice 
 * yang nantinya digunakan untuk menyimpan method/function webservicenya
 * 
 * @author Ratno Putro Sulistiyono | ratno@comlabs.itb.ac.id | ratno@knoqdown.com 
 */

include_once 'func.php';
include_once 'spyc.php';
include_once 'adodb5/adodb.inc.php';

class ratno_ws {

  protected $conn;
  protected $config;
  protected $ws;
  protected $services;
  protected $return;

  public function __construct() {
    $yml = Spyc::YAMLLoad("ws.yml");
    $this->config = $yml['config'];
    $this->ws = $yml['ws'];
    $this->services = $yml['services'];

    $this->return['err_no'] = 0;
    $this->return['err_teks'] = "";
  }

  protected function connect() {
    try {
      $this->conn = &ADONewConnection($this->config['driver']);
      if (in_array($this->config['driver'], array("oracle", "oci8"))) {
        $this->conn->connectSID = $this->config['oraclesid'];
        $this->conn->Connect($this->config['server'], $this->config['username'], $this->config['password'], $this->config['database']);
      } else {
        $this->conn->PConnect($this->config['server'], $this->config['username'], $this->config['password'], $this->config['database']);
      }
    } catch (Exception $e) {
      $this->return['err_no'] = -2;
      $this->return['err_teks'] = $e->getMessage();
      $this->return['data'] = array();
    }
  }

  protected function auth($password,$method) {
    if (key_exists("password", $this->services[$method])) {
      if($password == $this->services[$method]['password']){
        return true;
      } else {
        $this->return['err_no'] = -1;
        $this->return['err_teks'] = 'Anda tidak berhak mengakses webservice ini!';
        return false;
      }
    } else {
      if($password == $this->ws['password']){
        return true;
      } else {
        $this->return['err_no'] = -1;
        $this->return['err_teks'] = 'Anda tidak berhak mengakses webservice ini!';
        return false;
      }
    }
  }

  protected function ws_data() {
    if ($this->ws['zip_data']) {
      return base64_encode(gzcompress(json_encode($this->return), 9));
    } else {
      return json_encode($this->return);
    }
  }

  protected function process($method, $data) {
    $this->connect();
    $method = str_replace($this->ws['class'] . "::", "", $method);
    // ambil input
    $input = array();
    $i = 0;
    foreach ($this->services[$method]['input'] as $input_name => $input_opt) {
      $input[$input_name] = $data[$i++];
    }

    if (key_exists("is_compressed", $input))
      $this->ws['zip_data'] = $input['is_compressed'];
    
    unset($input['is_compressed']);
    
    // if any failure should happened when connect to database
    if ($this->return['err_no'] == -2)
      return $this->ws_data();

    if (!$this->auth($input['password'],$method))
      return $this->ws_data();
    
    unset($input['password']);

    if (key_exists("pre_sql", $this->services[$method])) {
      foreach ($this->services[$method]['pre_sql'] as $pre_sql) {
        $this->conn->Execute($pre_sql);
      }
    }
    
    $search = array();
    $replace = array();
    foreach ($input as $key=>$val) {
      $search[] = "[$key]";
      $replace[] = is_numeric($val)?$val:$this->conn->Quote($val);
    }
    
    $sql = str_replace($search, $replace, $this->services[$method]['sql']);
    
    if(key_exists("debug_sql", $input) && $input['debug_sql']){
      return $sql;
    }
    
    $this->conn->SetFetchMode(ADODB_FETCH_NUM);
    $rs = $this->conn->Execute($sql);

    if (!$rs) {
      $this->return['err_no'] = -2;
      $this->return['err_teks'] = $this->conn->ErrorMsg();
      $this->return['data'] = -1;
      return $this->ws_data();
    } else {
      if ($this->services[$method]['type'] == 'singledata') {
        $this->return['data'] = $rs->fields[0];
        $this->return['fields'] = $this->services[$method]['fields'][0];
      } elseif ($this->services[$method]['type'] == 'singlerow') {
        $this->return['data'] = $rs->fields;
        $this->return['fields'] = $this->services[$method]['fields'];
      } else {
        $ret = array();
        while (!$rs->EOF) {
          $ret[] = $rs->fields;
          $rs->MoveNext();
        }
        $this->return['data'] = $ret;
        $this->return['fields'] = $this->services[$method]['fields'];
      }
      
    }

    $this->conn->Close();
    return $this->ws_data();
  }

}