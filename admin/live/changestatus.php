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
$getstatus = strip_tags($_GET['status']);

$getstatus = strtolower($getstatus);

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfflive SET status='$getstatus' WHERE id='$getid'");

mysql_close($con);
?>
