<?php
/**
 * dynamic ws ini adalah webservice yang digenerate secara otomatis dari service yang disiapkan di ws.yml
 * mekanismenya dia akan membuat file generated_ws.php on the fly setiap kali terjadi perubahan di ws.yml
 * jadi tidak perlu melakukan perubahan pada file-file selain ws.yml
 * pastikan file berikut ini rewritable:
 *  - generated_ws.php
 *  - md5.txt 
 * 
 * @author Ratno Putro Sulistiyono | ratno@comlabs.itb.ac.id | ratno@knoqdown.com
 */

include_once 'ratno_ws.php';

$md5 = file_get_contents("md5.txt");
if (trim($md5) != md5_file("ws.yml")) {
  $yml = Spyc::YAMLLoad("ws.yml");
  $dynamic_ws = '<?php class ' . $yml['ws']['class'] . ' extends ratno_ws {';
  foreach ($yml['services'] as $service_name => $service) {
    $dynamic_ws .= 'public function ' . $service_name . '(){return $this->process(__METHOD__, func_get_args());}';
  }
  $dynamic_ws .= '}';
  $fp = fopen("generated_ws.php", "w+");
  fwrite($fp, $dynamic_ws);
  fclose($fp);
  
  $fp = fopen("md5.txt", "w+");
  fwrite($fp, md5_file("ws.yml"));
  fclose($fp);
} 

include_once "generated_ws.php";