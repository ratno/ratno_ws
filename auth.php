<?php
include_once 'spyc.php';
include_once 'func.php';

$yml = Spyc::YAMLLoad("ws.yml");

$login = '<form method="post">';
$login .='Password <input type="password" name="password" /><input type="submit" value="kirim" />';
$login .='</form>';

$menu = "<h1>Webservices for " . $yml['ws']['unit'] . "</h1>";
$menu .= "<a href='client.php'>Daftar Testing Services</a><br />";
$menu .= "<a href='data_dict.php'>Data Dictionary</a><br />";
$menu .= "<a href='auth.php?logout=yes'>Logout</a><br />";
?>
<html>
  <head>
    <title>Authentication</title>
  </head>
  <body>
    <?php
    if($_GET['logout']=="yes"){
      session_destroy();
      unset($_SESSION);
    }
    
    if ($_SESSION['auth_pass'] == "ratno_ws_auth_pass_#!@") {
      echo $menu;
    } else {
      if (count($_POST) > 0 && isset($_POST['password'])) {
        if ($_POST['password'] == $yml['ws']['password']) {
          $_SESSION['auth_pass'] = "ratno_ws_auth_pass_#!@";
          echo $menu;
        } else {
          echo "Password salah!!<br />";
          echo $login;
        }
      } else {
        echo $login;
      }
    }
    ?>
  </body>
</html>