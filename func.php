<?php
/**
 * Kumpulan fungsi untuk toolbox aja
 * 
 * @author Ratno Putro Sulistiyono | ratno@comlabs.itb.ac.id | ratno@knoqdown.com
 */


function d($data, $die = false) {
  echo "<pre>";
  print_r($data);
  echo "</pre>";
  if ($die)
    die(":: die at " . date("d/m/Y H:i:s") . " ::");
}

function nbsp($num){
  $out = "";
  for($i=0;$i<$num;$i++) $out .= "&nbsp;";
  return $out;
}

function auth(){
  if($_SESSION['auth_pass'] == "ratno_ws_auth_pass_#!@") {
    // go ahead
  } else {
    header("location: auth.php");
  }
}

session_start();