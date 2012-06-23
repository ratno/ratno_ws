<?php
/**
 * halaman ini untuk testing client, yang secara otomatis mengambil informasi dari ws.yml
 * @author Ratno Putro Sulistiyono | ratno@comlabs.itb.ac.id | ratno@knoqdown.com 
 */
include_once 'spyc.php';
include_once 'nusoap/nusoap.php';
include_once 'func.php';

$call_method = (key_exists("m", $_GET) && isset($_GET['m'])) ? $_GET['m'] : null;
$yml = Spyc::YAMLLoad("ws.yml");
if (is_null($call_method)) {
  echo "<h1>Services Available:</h1>";
  echo "<ul>";
  foreach ($yml['services'] as $service_name => $service) {
    echo "<li><a href='client.php?m=$service_name'>$service_name</a></li>";
  }
  echo "</ul>";
} else {
  try {
    $client = new soapclient($yml['ws']['ns'] . '?wsdl', true);

    foreach ($yml['services'] as $service_name => $service) {
      if (!is_null($call_method) && $service_name != $call_method)
        continue;
      $input = array();
      $input["password"] = $yml['ws']['password'];
      if (key_exists('test', $service)) {
        foreach ($service['test'] as $param => $param_value) {
          $input[$param] = $param_value;
        }
      }

      echo "<h1>" . $service['desc'] . " [" . $service_name . "]</h1>";
      $method = $yml['ws']['class'] . ".$service_name";
      $rs = $client->call($method, $input);
      echo '<b>test parameter:</b>';
      $input['password'] = '-sensored by ratno-';
      d($input);
      echo '<b>return result:</b>';
      d($rs);
      echo "<b>decode:</b><br />";
      d(json_decode($rs));
      echo "<hr />";
      echo "<h3><a href='client.php'>BACK</a>";
    }
  } catch (Exception $e) {
    d($e->getMessage());
  }
}
	

