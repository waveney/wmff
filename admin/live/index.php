<?php
  include_once("int/fest.php");
  A_Check('Committee','OldAdmin');

$getchangestatus = $_GET['status'];
$confirm = "confirm";

if(!empty($getchangestatus))
{
include ("changestatus.php");
}

$getview = $_GET['update'];
$confirm = "confirm";

if($getview === $confirm)
{
include ("edit-save.php");
}

$getview = $_GET['view'];
$confirm = "confirm";

if($getview === $confirm)
{
include ("view.php");
die('');
}

$getview = $_GET['edit'];
$confirm = "confirm";

if($getview === $confirm)
{
include ("edit.php");
die('');
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>WimborneFolk.co.uk | Admin Home</title>
<?php include("files/header.php"); ?>
</head>
<body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=320647184654064&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

    <script>
      $(function() {
        $(".rslides").responsiveSlides();
      });
    </script>

<a href="/" rel="bookmark"><h1>WimborneFolk.co.uk | Admin Home</h1></a>
<div class="navigation"><?php include("files/navigation.php"); ?></div>
<div class="content">

<h2 class="maintitle">View Live & Loud Applications</h2>

<p>
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

$result = mysql_query("SELECT * FROM wmfflive");
$num_rows = mysql_num_rows($result);

echo "<a href=\"?view=confirm\"><strong>View All Applications ($num_rows)</strong></a>";

mysql_close($con);
?>
</p>

</div>
<?php include("files/footer.php"); ?>
</body>
</html>
