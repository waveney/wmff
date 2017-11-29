<?php
$page = $_SERVER['PHP_SELF'];
$sec = "30";
header("Refresh: $sec; url=$page");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("files/header.php"); ?>
</head>
<body>
<div class="content">

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

$result = mysql_query("SELECT * FROM wmff WHERE display='true' ORDER BY articledate DESC LIMIT 0,3");

while($row = mysql_fetch_array($result))
  {
  $getid = $row['id'];
  $getdate = $row['articledate'];
  $gettitle = $row['title'];
  $getcontent = $row['content'];
  $getimage = $row['image'];
  $getauthor = $row['author'];
  $getauthor = ucwords($getauthor);
  $getdisplay = $row['display'];

  date_default_timezone_set('GMT');

  $getdate = date('j F Y', strtotime($getdate));

  $getcontent = substr($getcontent,0,250);
  $getcontent = substr($getcontent, 0, strrpos($getcontent, '.'));

      if(!empty($getimage))
      {
      $getimage = "<a href=\"/news/?id=$getid\" rel=\"bookmark\" target=\"_blank\"><img src=\"/images/$getimage\" alt=\"Wimborne Minster Folk Festival\" class=\"newsimg\" /></a>";
      }
      if(empty($getimage))
      {
      $getimage = "";
      }


      echo "<div class=\"news\">$getimage
      <h2 class=\"subtitle\"><a href=\"/news/?id=$getid\" rel=\"bookmark\" target=\"_blank\">$gettitle</a></h2>
      <p class=\"newsdate\">$getdate by <a href=\"/news/?author=$getauthor\" rel=\"bookmark\" target=\"_blank\">$getauthor</a></p>
      <p>$getcontent. <a href=\"/news/?id=$getid\" rel=\"bookmark\" target=\"_blank\"><strong>Read more</strong>.</a></p></div>";
  }

mysql_close($con);
?>

</div>
</body>
</html>
