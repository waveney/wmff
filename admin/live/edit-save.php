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

$contactname = strip_tags($_POST['contactname']);
$address = strip_tags($_POST['address']);
$email = strip_tags($_POST['email']);
$phone = strip_tags($_POST['phone']);

$performername = strip_tags($_POST['performername']);
$performernumber = strip_tags($_POST['performernumber']);
$performerage = strip_tags($_POST['performerage']);
$performerinst = strip_tags($_POST['performerinst']);
$performergenre = strip_tags($_POST['performergenre']);
$performerdemo = strip_tags($_POST['performerdemo']);

$squarerecords = strip_tags($_POST['squarerecords']);
$whitehart = strip_tags($_POST['whitehart']);
$risingsun = strip_tags($_POST['risingsun']);
$qe = strip_tags($_POST['qe']);


$address = nl2br($address);

$getcontactname = ucwords($getcontactname);
$getperformername = ucwords($getperformername);
$getperformerinst = ucwords($getperformerinst);
$getperformergenre = ucwords($getperformergenre);


mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfflive SET contactname='$contactname' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfflive SET address='$address' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfflive SET email='$email' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfflive SET phone='$phone' WHERE id='$getid'");




mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfflive SET performername='$performername' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfflive SET performernumber='$performernumber' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfflive SET performerage='$performerage' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfflive SET performerinst='$performerinst' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfflive SET performergenre='$performergenre' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfflive SET performerdemo='$performerdemo' WHERE id='$getid'");



mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfflive SET squarerecords='$squarerecords' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfflive SET whitehart='$whitehart' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfflive SET risingsun='$risingsun' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmfflive SET qe='$qe' WHERE id='$getid'");


mysql_close($con);
?>








