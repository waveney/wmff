<?php
  include_once("int/fest.php");
  A_Check('Committee','OldAdmin');

$title = strip_tags($_POST['title']);
$content = strip_tags($_POST['content']);
$image = strip_tags($_POST['image']);

if(empty ($title) | empty ($content))
{
include ("/home/jmnetwor/public_htmlfiles/errors/top.php");
echo "<h2 class=\"maintitle\">Something is Missing...</h2><p>It appears that we are missing a little information. Please go back and fill out all of the required form fields.</p>";
include ("/home/jmnetwor/public_htmlfiles/errors/bottom.php");
die('');
}

?>
<?php

$con = mysql_connect();
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmff");
$num_rows = mysql_num_rows($result);

while($row = mysql_fetch_array($result))
  {
  $nextnumber = $num_rows+1;
  }


$title = strip_tags($_POST['title']);
$UserData = Get_User($_SESSION{'WMFFid'});
$getuser = $UserData['Login'];
$content = $_POST['content'];
$image = strip_tags($_POST['imagecopy']);
$caption = strip_tags($_POST['caption']);
$display = strip_tags($_POST['display']);

$abrcontent = nl2br($content);

$astitle = addslashes($title);
$ascontent = addslashes($abrcontent);
$ascaption = addslashes($caption);

$thedatetoday = gmdate("Y-m-d H-i-s");

if(empty($nextnumber))
{
$nextnumber = "1";
}

mysql_query("INSERT INTO wmff (articledate, id, display, title, content, image, caption, author)
VALUES ('$thedatetoday', '$nextnumber', '$display', '$astitle', '$ascontent', '$image', '$ascaption', '$getuser')");

mysql_close($con);

?>
