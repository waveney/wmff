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

mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmfftech");
$num_rows = mysql_num_rows($result);

while($row = mysql_fetch_array($result))
  {
  $nextnumber = $num_rows+1;
  }


$eventid = strip_tags($_POST['eventid']);
$filename = strip_tags($_POST['filename']);

$filename = addslashes($filename);

if(empty($nextnumber))
{
$nextnumber = "1";
}

mysql_query("INSERT INTO wmfftech (id, eventid, filename)
VALUES ('$nextnumber', '$eventid', '$filename')");

mysql_close($con);

?>
