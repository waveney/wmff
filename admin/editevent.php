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
$cat = strip_tags($_POST['cat']);
$app = strip_tags($_POST['app']);
$content = $_POST['content'];
$image = strip_tags($_POST['imagecopy']);
$link1 = strip_tags($_POST['link1']);
$link2 = strip_tags($_POST['link2']);
$facebook = strip_tags($_POST['facebook']);
$twitter = strip_tags($_POST['twitter']);
$instagram = strip_tags($_POST['instagram']);
$youtube = strip_tags($_POST['youtube']);
$headline = strip_tags($_POST['headline']);
$display = strip_tags($_POST['display']);
$author = strip_tags($_POST['author']);

$abrcontent = nl2br($content);
$lowerauthor = strtolower($author);
$ascontent = addslashes($abrcontent);

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffevent SET display='$display' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffevent SET title='$title' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffevent SET cat='$cat' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffevent SET app='$app' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffevent SET description='$ascontent' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffevent SET img='$image' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffevent SET link1='$link1' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffevent SET link2='$link2' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffevent SET facebook='$facebook' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffevent SET twitter='$twitter' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffevent SET instagram='$instagram' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffevent SET youtube='$youtube' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffevent SET headline='$headline' WHERE id='$getid'");

mysql_select_db("wmff", $con);
mysql_query("UPDATE wmffevent SET user='$lowerauthor' WHERE id='$getid'");

mysql_close($con);
?>
