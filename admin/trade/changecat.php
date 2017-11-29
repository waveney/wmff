<?php
  include_once("int/fest.php");
  A_Check('Committee','OldAdmin');

$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

$getid = strip_tags($_GET['id']);
$getcat = strip_tags($_GET['changecat']);

$getcat = strtolower($getcat);

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET cat='$getcat' WHERE id='$getid'");

mysql_close($con);
?>
