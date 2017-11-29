<?php
$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
$jmnuser = $_COOKIE['jmnuser'];
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
  
mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM userdata WHERE username='$jmnuser' AND staff='all' OR username='$jmnuser' AND staff='wmff'");

while($row = mysql_fetch_array($result))
  {
  $getstaff = $row['staff'];
  }

if(empty($getstaff))
  {
  include("/home/jmnetwor/public_htmlfiles/errors/top.php");
  echo "<h2 class=\"maintitle\">Restricted Access</h2><p>You are not authorised to view this page!</p>";
  include("/home/jmnetwor/public_htmlfiles/errors/bottom.php");
  die('');
  }
?>
