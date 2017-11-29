<?php
  include_once("int/fest.php");
  A_Check('Committee','OldAdmin');

$date = strip_tags($_POST['date']);
$location = strip_tags($_POST['location']);
$price = strip_tags($_POST['price']);

if(empty ($date))
{
include ("files/top.php");
echo "<h2 class=\"maintitle\">Something is Missing...</h2><p>It appears that we are missing a little information. Please go back and fill out all of the form fields.</p>";
include ("files/bottom.php");
die('');
}

?>
<?php
$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmffshow");
$num_rows = mysql_num_rows($result);

while($row = mysql_fetch_array($result))
  {
  $nextnumber = $num_rows+1;
  }


$eventid = strip_tags($_POST['eventid']);
$date = strip_tags($_POST['date']);
$location = $_POST['location'];
$price = strip_tags($_POST['price']);
$otdprice = strip_tags($_POST['otdprice']);
$otdlink = strip_tags($_POST['otdlink']);

if(empty($otdlink))
{
	$otdlink = "no";
}

$location = addslashes($location);

if(empty($nextnumber))
{
$nextnumber = "1";
}

mysql_query("INSERT INTO wmffshow (id, date, location, price, otdprice, otdlink, eventid)
VALUES ('$nextnumber', '$date', '$location ', '$price', '$otdprice', '$otdlink', '$eventid')");

mysql_close($con);

?>
