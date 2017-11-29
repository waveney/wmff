<!DOCTYPE html>
<html lang="en">
<head>
<title>Wimborne Minster Folk Festival | Comedy</title>
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

<a href="/" rel="bookmark"><h1>Wimborne Minster Folk Festival | Comedy</h1></a>
<div class="navigation"><?php include("files/navigation.php"); ?></div>
<div class="content">

<img src="/images/new.png" style="max-height:22px; margin:12px 5px 0 0; float:left;" /><h2 class="maintitle">Comedy</h2>
<p>New for 2017, we're introducing a night of Comedy to the festival! Come along and laugh out loud on Friday 9 June to Welsh comic Noel James, Polly Morris and... could it be you on stage? Find out more below...</p>

<p>Tickets are just <strong>&#163;5.00</strong> and are also included in our Friday & Weekend Passes, in case you wanted to see more of the festival! Tickets and passes are on sale now <a href="/tickets" rel="bookmark"><strong>Online</strong></a>, at Wimborne Tourist Information Centre (01202 886116) and from the Allendale Centre, Wimborne.</p>

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

$eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='147' AND display='true'");

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

$eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='8' AND display='true'");

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

$eventresult = mysql_query("SELECT * FROM wmffevent WHERE cat='comedy' AND id!='147' AND display='true'");

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

<h2 class="subtitle">Join the Line-up</h2>
<p>We're looking for local people who think their funny enough to join our comedy line-up! Our new comedy competition <strong><i>Laugh Out Loud</i></strong> will be taking place in Wimborne between the end of April and beginning of May. More details about the competition and how to apply are available on our <a href="/lol" rel="bookmark"><strong>Laugh Out Loud Competition</strong></a> page.</p>

</div>
<?php include("files/footer.php"); ?>
</body>
</html>
