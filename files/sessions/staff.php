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
  echo "<div class=\"navigation\">";
  include("/admin/navigation.php");
  echo "</div>";
  }

?>
