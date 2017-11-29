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

$techid = strip_tags($_POST['techid']);
$filename = strip_tags($_POST['editfilename']);

$filename = addslashes($filename);

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftech SET filename='$filename' WHERE id='$techid'");

mysql_close($con);
?>
