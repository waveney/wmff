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

$getid = strip_tags($_POST['id']);
$title = strip_tags($_POST['title']);
$content = $_POST['content'];
$image = strip_tags($_POST['imagecopy']);
$caption = strip_tags($_POST['caption']);
$author = strip_tags($_POST['author']);
$display = strip_tags($_POST['display']);
$articledate = strip_tags($_POST['articledate']);

$abrcontent = nl2br($content);
$lowerauthor = strtolower($author);
$ascontent = addslashes($abrcontent);

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmff SET articledate='$articledate' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmff SET display='$display' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmff SET title='$title' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmff SET content='$ascontent' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmff SET image='$image' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmff SET caption='$caption' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmff SET author='$lowerauthor' WHERE id='$getid'");

mysql_close($con);
?>
