<!DOCTYPE html>
<html lang="en">
<head>
<title>Wimborne Minster Folk Festival | Sessions</title>
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

<a href="/" rel="bookmark"><h1>Wimborne Minster Folk Festival | Sessions</h1></a>
<div class="navigation"><?php include("files/navigation.php"); ?></div>
<div class="content">

<h2 class="maintitle">Sessions</h2>

<p>Take part in some of the various types of sessions that are happening around the town.</p>

<!-- <p>We have a huge selection of music for you to choose from over the festival weekend, from national names to local legends. There are five music stages around the town for you to discover something new. Enjoy!</p> //-->


<div id="flex">

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

$eventresult = mysql_query("SELECT * FROM wmffevent WHERE cat='session' AND display='true' ORDER BY title ASC");

while($row = mysql_fetch_array($eventresult))
  {
  $id = $row['id'];
  $title = $row['title'];
  $cat = $row['cat'];
  $description = $row['description'];
  $img = $row['img'];

  $title = str_replace('\\','',$title); 
  $description = str_replace('\\','',$description);
  $img = str_replace('\\','',$img);

  $description = substr($description,0,150);
  $description = substr($description, 0, strrpos($description, '.'));

   echo "<div class=\"article\">
<a href=\"/?event=$id\" rel=\"bookmark\">
<h2 class=\"articlettl\">$title</h2>
<img class=\"articleimg\" src=\"/images/$img\" alt=\"$title at Wimborne Minster Folk Festival 2017\" />
</a>
<p class=\"articletxt\">$description.</p>
</div>";

  }

mysql_close($con);
?>

</div>

<h2 class="subtitle">Stay Updated</h2>
<p>Keep up to date with our latest music announcements by joining us on <a href="http://facebook.com/WimborneFolk" rel="tag" target="-blank"><strong>Facebook</strong></a>, <a href="http://twitter.com/WimborneFolk" rel="tag" target="_blank"><strong>Twitter</strong></a> and <a href="http://instagram.com/WimborneFolk" rel="tag" target="_blank"><strong>Instagram</strong></a>!</p>

</div>
<?php include("files/footer.php"); ?>
</body>
</html>
