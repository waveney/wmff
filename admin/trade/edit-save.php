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

$getid = $_GET['id'];

$business = strip_tags($_POST['business']);
$contactname = strip_tags($_POST['contactname']);
$invoiceaddress = strip_tags($_POST['invoiceaddress']);
$email = strip_tags($_POST['email']);
$phone = strip_tags($_POST['phone']);

$pitchfee = strip_tags($_POST['pitchfee']);
$location = strip_tags($_POST['location']);
$accepted = strip_tags($_POST['accepted']);
$products = strip_tags($_POST['products']);
$pitchsize = strip_tags($_POST['pitchsize']);
$health = strip_tags($_POST['health']);
$charity = strip_tags($_POST['charity']);

$bidlevy = strip_tags($_POST['bidlevy']);
$chamber = strip_tags($_POST['chamber']);
$festivaltrader = strip_tags($_POST['festivaltrader']);


$invoiceaddress = nl2br($invoiceaddress);

$business = ucwords($business);
$contactname = ucwords($contactname);
$health = ucwords($health);


mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET contactname='$contactname' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET business='$business' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET invoiceaddress='$invoiceaddress' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET email='$email' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET phone='$phone' WHERE id='$getid'");


mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET pitchfee='$pitchfee' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET location='$location' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET accepted='$accepted' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET pitchsize='$pitchsize' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET products='$products' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET health='$health' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET charity='$charity' WHERE id='$getid'");


mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET bidlevy='$bidlevy' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET chamber='$chamber' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfftrade SET festivaltrader='$festivaltrader' WHERE id='$getid'");


mysql_close($con);
?>








