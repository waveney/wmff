<!DOCTYPE html>
<html lang="en">
<head>
<title>Wimborne Minster Folk Festival | Dance Line-up</title>
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

<a href="/" rel="bookmark"><h1>Wimborne Minster Folk Festival | Dance Line-up</h1></a>
<div class="navigation"><?php include("files/navigation.php"); ?></div>
<div class="content">

<h2 class="maintitle">Dance Line-up</h2>
<p>It's going to be another fun filled weekend of colourful dance displays through the streets of Wimborne from the dance sides below, some of whom have travelled from Europe to be with us!</p>

<h2 class="subtitle">Morris Sides 2016</h2>
<p></p>

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

$eventresult = mysql_query("SELECT * FROM wmffevent WHERE cat='dance' AND app='false' AND display='true' ORDER BY title ASC");

while($row = mysql_fetch_array($eventresult))
  {
  $id = $row['id'];
  $title = $row['title'];
  $description = $row['description'];
  $img = $row['img'];

  $title = str_replace('\\','',$title); 
  $description = str_replace('\\','',$description);
  $img = str_replace('\\','',$img);

  $description = substr($description,0,150);
  $description = substr($description, 0, strrpos($description, '.'));

   echo "<div class=\"mini\">
<a href=\"/?event=$id\" rel=\"bookmark\">
<img class=\"miniimg\" src=\"/images/$img\" alt=\"$title at Wimborne Minster Folk Festival 2016\" />
<h2 class=\"minittl\">$title</h2>
</a>
<p class=\"minitxt\">$description.</p>
</div>";

  }

mysql_close($con);
?>

</div>

<h2 class="subtitle">Appalachian Teams 2016</h2>
<p>Taking to the stage in the Cornmarket, Model Town and the Royal British Legion, find out about all of the Appalachian dance teams who will be appearing at the festival in June 2016. The full dance schedule for both Appalachian and Morris will be available prior to the festival.</p>

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

$eventresult = mysql_query("SELECT * FROM wmffevent WHERE cat='dance' AND app='true' AND display='true' ORDER BY title ASC");

while($row = mysql_fetch_array($eventresult))
  {
  $id = $row['id'];
  $title = $row['title'];
  $description = $row['description'];
  $img = $row['img'];

  $title = str_replace('\\','',$title); 
  $description = str_replace('\\','',$description);
  $img = str_replace('\\','',$img);

  $description = substr($description,0,150);
  $description = substr($description, 0, strrpos($description, '.'));

   echo "<div class=\"mini\">
<a href=\"/?event=$id\" rel=\"bookmark\">
<img class=\"miniimg\" src=\"/images/$img\" alt=\"$title at Wimborne Minster Folk Festival 2016\" />
<h2 class=\"minittl\">$title</h2>
</a>
<p class=\"minitxt\">$description.</p>
</div>";

  }

mysql_close($con);
?>

</div>


</div>
<?php include("files/footer.php"); ?>
</body>
</html>
