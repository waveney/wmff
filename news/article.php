<!DOCTYPE html>
<html lang="en">

<?php
$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
$findid = strip_tags($_GET['id']);

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmff WHERE id='$findid' AND display='true'");

while($row = mysql_fetch_array($result))
  {
  $getdate = $row['articledate'];
  $gettitle = $row['title'];
  $getcontent = $row['content'];
  $getimage = $row['image'];
  $getcaption = $row['caption'];
  $getauthor = $row['author'];
  $getauthor = ucwords($getauthor);

  date_default_timezone_set('GMT');

  $getdate = date('j F Y', strtotime($getdate));

      if(!empty($getimage))
      {
      $postimage = "<div class=\"biodiv\">
<img src=\"/images/$getimage\" alt=\"Wimborne Minster Folk Festival\" class=\"bioimg\" />
<p>$getcaption</p>
</div>";
      }
      if(empty($getimage))
      {
      $postimage = "";
      }

   echo "<head>
      <title>$gettitle</title>";

      include("files/header.php");

      echo "<meta property=\"og:image\" content=\"/images/$getimage\">
      </head>
      <body>";

      include("files/facebook.php");

      echo "<a href=\"/\" rel=\"bookmark\"><h1>$gettitle</h1></a>
      <div class=\"navigation\">";

    include("files/navigation.php"); 

      echo "</div>
      <div class=\"content\">
      $postimage
      <h2 class=\"subtitle\">$gettitle</h2>
      <p class=\"newsdate\">$getdate by <a href=\"?author=$getauthor\" rel=\"bookmark\">$getauthor</a></p>
      <p>$getcontent</p>

<div class=\"socialbutton\">
<a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-lang=\"en\" data-hashtags=\"WimborneFolk\">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"https://platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script>
</div>
<div class=\"socialbutton\">
<div class=\"fb-share-button\" data-type=\"button_count\"></div>
</div>

      </div>
      ";

    include("files/footer.php");

  }

?>

</body>
</html>
