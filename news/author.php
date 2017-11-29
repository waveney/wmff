<!DOCTYPE html>
<html lang="en">

<?php
$findauthor = strip_tags($_GET['author']);
$getauthor = strip_tags($_GET['author']);
$findauthor = ucwords($findauthor);
$james = 'James Marshall';
$judith = 'Judith Proctor';
$mike = 'Mike Carhart-Harris';

      if($findauthor === 'James')
      {
	  $findauthor = 'James Marshall';
      $authorimage = "<div class=\"biodiv\" style=\"min-width:150px; max-width:200px; margin-left:25px;\">
      <img src=\"/images/James-Marshall.jpg\" alt=\"Wimborne Minster Folk Festival\" class=\"bioimg\" />
      <p>James Marshall</p>
      </div>";
      }
      if($findauthor === 'Judith')
      {
	  $findauthor = 'Judith Proctor';
      $authorimage = "<div class=\"biodiv\" style=\"min-width:150px; max-width:200px;\">
      <img src=\"/images/Judith-Proctor.jpg\" alt=\"Wimborne Minster Folk Festival\" class=\"bioimg\" />
      <p>Judith Proctor</p>
      </div>";
      }      if($findauthor === 'Mike')
      {
	  $findauthor = 'Mike Carhart-Harris';
      $authorimage = "<div class=\"biodiv\" style=\"min-width:150px; max-width:200px;\">
      <img src=\"/images/Mike-Carhart-Harris.jpg\" alt=\"Wimborne Minster Folk Festival\" class=\"bioimg\" />
      <p>Mike Carhart-Harris</p>
      </div>";
      }
      if(empty($findauthor))
      {
      $authorimage = "";
      }

   echo "<head>
      <title>Wimborne Minster Folk Festival | Articles by $findauthor</title>";

    include("files/header.php");

      echo "</head>
      <body>
      <div id=\"fb-root\"></div>
      <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = \"//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=320647184654064&version=v2.0\";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));</script>
      <a href=\"/\" rel=\"bookmark\"><h1>Wimborne Minster Folk Festival | Articles by $findauthor</h1></a>
      <div class=\"navigation\">";

    include("files/navigation.php"); 

      echo "</div>
      <div class=\"content\">
      $authorimage
      <h2 class=\"subtitle\">Articles by $findauthor</h2>
      <p>All news articles created by $findauthor.</p>";

$con = mysql_connect();
mysql_select_db("wmff", $con);
$result = mysql_query("SELECT * FROM wmff WHERE author='$getauthor' AND display='true' ORDER BY articledate DESC");

while($row = mysql_fetch_array($result))
    {
    $getdate = $row['articledate'];
    $getid = $row['id'];
    $gettitle = $row['title'];
    $getcontent = $row['content'];
    $getimage = $row['image'];
    $getcaption = $row['caption'];
    $getauthor = $row['author'];
    $getauthor = ucwords($getauthor);
    $getdisplay = $row['display'];

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
    echo "</div>";

  include("files/footer.php");

echo "</body>";

?>

</html>
