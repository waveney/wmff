<?php
  include_once("int/fest.php");
  A_Check('Committee','OldAdmin');

$title = strip_tags($_POST['title']);
if(empty ($title))
{
include ("files/top.php");
echo "<h2 class=\"maintitle\">Something is Missing...</h2><p>It appears that we are missing a little information. Please go back and fill out all of the required (*) form fields.</p>";
include ("files/bottom.php");
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

$result = mysql_query("SELECT * FROM wmffevent");
$num_rows = mysql_num_rows($result);

while($row = mysql_fetch_array($result))
  {
  $nextnumber = $num_rows+1;
  }


$title = strip_tags($_POST['title']);
$cat = strip_tags($_POST['cat']);
$app = strip_tags($_POST['app']);
$UserData = Get_User($_SESSION{'WMFFid'});
$getuser = $UserData['Login'];
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

$abrcontent = nl2br($content);

$astitle = addslashes($title);
$ascontent = addslashes($abrcontent);
$image = addslashes($image);

if(empty($nextnumber))
{
$nextnumber = "1";
}

mysql_query("INSERT INTO wmffevent (id, cat, app, title, description, img, link1, link2, facebook, twitter, instagram, youtube, headline, display, user)
VALUES ('$nextnumber', '$cat', '$app', '$astitle', '$ascontent', '$image', '$link1', '$link2', '$facebook', '$twitter', '$instagram', '$youtube', '$headline', '$display', '$getuser')");

mysql_close($con);

?>
