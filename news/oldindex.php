<?php 
$con = mysql_connect();
$findid = strip_tags($_GET['id']);
$findauthor = strip_tags($_GET['author']);
$findview = strip_tags($_GET['view']);

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmff WHERE id='$findid'");

while($row = mysql_fetch_array($result))
  {
  $getdisplay = $row['display'];
  }

if($getdisplay === 'false')
  {
  $findid = "";
  }

if(!empty($findid))
{
include("news/article.php");
die('');
}

if(!empty($findauthor))
{
include("news/author.php");
die('');
}

if($findview === 'archive')
{
include("news/archive.php");
die('');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Wimborne Minster Folk Festival | News</title>
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

<a href="/" rel="bookmark"><h1>Wimborne Minster Folk Festival | News</h1></a>
<div class="navigation"><?php include("files/navigation.php"); ?></div>
<div class="content">

<h2 class="maintitle">News</h2>
<p>Keep up to date with the latest news articles and press releases from the organisers of Wimborne Minster Folk Festival. Remember to follow our updates on <a href="http://facebook.com/WimborneFolk" rel="tag" target="_blank"><strong>Facebook</strong></a>, <a href="http://twitter.com/WimborneFolk" rel="tag" target="_blank"><strong>Twitter</strong></a> & <a href="http://instagram.com/WimborneFolk" rel="tag" target="_blank"><strong>Instagram</strong></a> for the latest info in your feeds!</p>

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

$result = mysql_query("SELECT * FROM wmff WHERE display='true' ORDER BY articledate DESC LIMIT 0,10");

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

  $getcontent = substr($getcontent,0,500);
  $getcontent = substr($getcontent, 0, strrpos($getcontent, '.'));

      if(!empty($getimage))
      {
      $getimage = "<a href=\"?id=$getid\" rel=\"bookmark\"><img src=\"/images/$getimage\" alt=\"Wimborne Minster Folk Festival\" class=\"newsimglrg\" /></a>";
      }
      if(empty($getimage))
      {
      $getimage = "";
      }


      echo "<div class=\"news\">$getimage
      <h2 class=\"subtitle\"><a href=\"?id=$getid\" rel=\"bookmark\">$gettitle</a></h2>
      <p class=\"newsdate\">$getdate by <a href=\"?author=$getauthor\" rel=\"bookmark\">$getauthor</a></p>
      <p>$getcontent.</p>
      <p><a class=\"button\" style=\"color:#FFFFFF;\" href=\"?id=$getid\" rel=\"bookmark\">Read More</a></p></div>";
  }

mysql_close($con);
?>

<p>Continue reading news articles in our <a href="?view=archive" rel="bookmark">Archive</a>.</p>

</div>
<?php include("files/footer.php"); ?>
</body>
</html>
