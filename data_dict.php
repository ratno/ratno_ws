<?php
include_once 'spyc.php';
include_once 'func.php';

$yml = Spyc::YAMLLoad("ws.yml");
?>
<html>
  <head>
    <title>Data Dictionary for <?php echo $yml['ws']['unit'] ?></title>
  </head>
  <body>
    <h1>Data Dictionary for <?php echo $yml['ws']['unit'] ?><h1>
    <?php
    foreach ($yml['services'] as $service_name => $service) {
      echo "<h2>$service_name:</h2>";
      echo "<h3>Description: </h3>";
      echo "<p>".$service['desc']."</p>";
      echo "<h3>wsdl: </h3>";
      echo "<p>".$yml['ws']['ns']."?wsdl</p>";
      echo "<h3>Parameter Input Service:</h3>";
      echo "<table border=1 cellpadding=5 cellspacing=0>";
      echo "<tr>";
      echo "<th>Parameter Input</th>";
      echo "<th>Tipe</th>";
      echo "<th>Info</th>";
      echo "<tr>";
      foreach($service['input'] as $input_key=>$input_opt){
        echo "<tr>";
        echo "<td>".$input_key."</td>";
        echo "<td>".$input_opt['type']."</td>";
        echo "<td>".$input_opt['info']."</td>";
        echo "<tr>";
      }
      echo "</table>";
      
      echo "<h3>Output Fields:</h3>";
      echo "<ul>";
      foreach($service['fields'] as $field){
        echo "<li>$field</li>";
      }
      echo "</ul>";
      echo "<h3>Client Test:</h3>";
      echo "<a href='client.php?m=$service_name'>$service_name</a>";
      echo "<hr />";
    }
    ?>
  </body>
</html>