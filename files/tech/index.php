<!DOCTYPE html>
<html lang="en">
<head>
<title>Tech Specs 2016</title>
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

<a href="/" rel="bookmark"><h1>Tech Specs 2016</h1></a>
<div class="navigation"><?php include("files/navigation.php"); ?></div>
<div class="content">

<?php
$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
$get = $_GET['location'];

$get = str_replace('%20',' ',$get);

if (empty($get))
  {
  echo "<h2 class=\"maintitle\">Tech Specs 2016</h2><p>Please select the area you are working at during the festival.</p>
  
  <div id=\"flex\">
  
  <div class=\"article\">
<a href=\"?location=The Allendale Centre Minster Hall\" rel=\"bookmark\">
<h2 class=\"articlettl\">Allendale Minster Hall</h2>
<img class=\"articleimg\" src=\"/gallery/2015/Wimborne-Folk-2015-023.jpg\" alt=\"Wimborne Minster Folk Festival\" />
</a>
<p class=\"articletxt\">Click to view Line-up and Tech Specs.</p>
</div>
  
  <div class=\"article\">
<a href=\"?location=The Allendale Centre Quarterjack\" rel=\"bookmark\">
<h2 class=\"articlettl\">Allendale Quarterjack</h2>
<img class=\"articleimg\" src=\"/gallery/2015/Wimborne-Folk-2015-149.jpg\" alt=\"Wimborne Minster Folk Festival\" />
</a>
<p class=\"articletxt\">Click to view Line-up and Tech Specs.</p>
</div>
  
  <div class=\"article\">
<a href=\"?location=Garden Stage at The Olive Branch\" rel=\"bookmark\">
<h2 class=\"articlettl\">Garden Stage at The Olive Branch</h2>
<img class=\"articleimg\" src=\"/gallery/2015/Wimborne-Folk-2015-076.jpg\" alt=\"Wimborne Minster Folk Festival\" />
</a>
<p class=\"articletxt\">Click to view Line-up and Tech Specs.</p>
</div>
  
  <div class=\"article\">
<a href=\"?location=Stage in The Square\" rel=\"bookmark\">
<h2 class=\"articlettl\">Stage in The Square</h2>
<img class=\"articleimg\" src=\"/gallery/2015/Wimborne-Folk-2015-034.jpg\" alt=\"Wimborne Minster Folk Festival\" />
</a>
<p class=\"articletxt\">Click to view Line-up and Tech Specs.</p>
</div>
  
  <div class=\"article\">
<a href=\"?location=Willow Walk Youth Music Stage\" rel=\"bookmark\">
<h2 class=\"articlettl\">Willow Walk Youth Music Stage</h2>
<img class=\"articleimg\" src=\"/gallery/2015/Wimborne-Folk-2015-015.jpg\" alt=\"Wimborne Minster Folk Festival\" />
</a>
<p class=\"articletxt\">Click to view Line-up and Tech Specs.</p>
</div>
  
  <div class=\"article\">
<a href=\"?location=Wimborne Minster\" rel=\"bookmark\">
<h2 class=\"articlettl\">Wimborne Minster</h2>
<img class=\"articleimg\" src=\"/gallery/2015/Wimborne-Folk-2015-071.jpg\" alt=\"Wimborne Minster Folk Festival\" />
</a>
<p class=\"articletxt\">Click to view Line-up and Tech Specs.</p>
</div>

</div>";
  }

if (!empty($get))
  {
  echo "<h2 class=\"maintitle\">$get</h2><p>The <strong>Line-up</strong> and <strong>Tech Specs</strong> for acts appearing at $get over the festival weekend.</p>
  <p><a href=\"files/tech\" rel=\"bookmark\"><strong>Return to Menu</strong></a>.</p><p><table style=\"width:inherit;\" cellspacing=\"5\" cellpadding=\"5\">";
  }

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

if(!empty($get))
  {

  mysql_select_db("wmff", $con);

  $showresult = mysql_query("SELECT * FROM wmffshow WHERE location = '$get' ORDER BY date ASC");

  while($row = mysql_fetch_array($showresult))
    {
    $id = $row['eventid'];
    $date = $row['date'];
    
     $techresult = mysql_query("SELECT * FROM wmfftech WHERE eventid='$id'");
        while($row = mysql_fetch_array($techresult))
        {
        $techid = $row['eventid'];
        $filename = $row['filename'];
        if (!empty($filename))
          {
          $filename = "<a href=\"files/tech/$filename\" rel=\"bookmark\" target=\"_blank\"><strong>Download Tech Spec</strong></a>";
          }
        }

    $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$id'");

      while($row = mysql_fetch_array($eventresult))
      {
      $id = $row['id'];
      $title = $row['title'];
      $cat = $row['cat'];
      $description = $row['description'];
      $img = $row['img'];

      $title = str_replace('\\','',$title); 
      
      $show2result = mysql_query("SELECT * FROM wmffshow WHERE eventid='$id' AND location = '$get' AND date='$date'");
        while($row = mysql_fetch_array($show2result))
        {
        $date = $row['date'];
date_default_timezone_set('GMT');

        $date = date('D d M g:ia', strtotime($date));
        
            echo "<tr>
            <td>$date</td>
    <td><a href=\"/?event=$id\" rel=\"bookmark\"><strong>$title</strong></a></td><td>
<td>";

if ($techid === $id)
  {
  echo "$filename";
  }
  echo "</td>
</tr>";
        }
      }

    }

  echo "</table></p></div>";
  include("files/footer.php");
  echo "</body></html>";
  die('');
  }

mysql_close($con);
?>

</div>
<?php include("files/footer.php"); ?>
</body>
</html>
