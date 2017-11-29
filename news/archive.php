<!DOCTYPE html>
<html lang="en">
<head>
<title>Wimborne Minster Folk Festival | News Archive</title>
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

<a href="/" rel="bookmark"><h1>Wimborne Minster Folk Festival | News Archive</h1></a>
<div class="navigation"><?php include("files/navigation.php"); ?></div>
<div class="content">

<h2 class="maintitle">News Archive</h2>
<p>Archived news articles from Wimborne Minster Folk Festival.</p>

<?php
$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
$findview = strip_tags($_GET['view']);

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmff WHERE display='true' ORDER BY articledate DESC LIMIT 10,9999");

while($row = mysql_fetch_array($result))
  {
  $getid = $row['id'];
  $getdate = $row['articledate'];
  $gettitle = $row['title'];
  $getcontent = $row['content'];
  $getimage = $row['image'];
  $getauthor = $row['author'];
  $getauthor = ucwords($getauthor);

  date_default_timezone_set('GMT');

  $getdate = date('j F Y', strtotime($getdate));

  $getcontent = substr($getcontent,0,250);
  $getcontent = substr($getcontent, 0, strrpos($getcontent, '.'));

      if(!empty($getimage))
      {
      $getimage = "<a href=\"?id=$getid\" rel=\"bookmark\"><img src=\"/images/$getimage\" alt=\"Wimborne Minster Folk Festival\" class=\"newsimg\" style=\"min-width:150px; max-width:270px;\" /></a>";
      }
      if(empty($getimage))
      {
      $getimage = "";
      }

   echo "
      <div class=\"newsarc\">$getimage
      <h2 class=\"mintitle\"><a href=\"?id=$getid\" rel=\"bookmark\">$gettitle</a></h2>
      <p class=\"newsdate\">$getdate by <a href=\"?author=$getauthor\" rel=\"bookmark\">$getauthor</a></p>
      <p>$getcontent.</p>
      <p><a class=\"button\" style=\"color:#FFFFFF;\" href=\"?id=$getid\" rel=\"bookmark\">Read More</a></p></div>";

  }

mysql_close($con);
?>

</div>
<?php include("files/footer.php"); ?>
</body>
</html>
