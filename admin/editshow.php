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

$showid = strip_tags($_POST['showid']);
$date = strip_tags($_POST['editdate']);
$location = $_POST['editlocation'];
$price = strip_tags($_POST['editprice']);
$otdprice = strip_tags($_POST['editotdprice']);
$otdlink = strip_tags($_POST['editotdlink']);

if(empty($otdlink))
{
	$otdlink = "no";
}

$location = addslashes($location);

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffshow SET date='$date' WHERE id='$showid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffshow SET location='$location' WHERE id='$showid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffshow SET price='$price' WHERE id='$showid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffshow SET otdprice='$otdprice' WHERE id='$showid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffshow SET otdlink='$otdlink' WHERE id='$showid'");
mysql_close($con);
?>
