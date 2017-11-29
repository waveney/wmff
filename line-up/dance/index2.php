<!DOCTYPE html>
<html lang="en">
<head>
<title>Wimborne Minster Folk Festival | Dance Line-up</title>
<?php include("files/header.php"); ?>
<?php include_once("int/fest.php"); ?>
<?php include_once("int/DanceLib.php"); ?>
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
<?php include("files/navigation.php"); ?>
<div class="content">

<h2 class="maintitle">Dance Line-up</h2>
<b><a href=/int/ShowDanceProg.php?Cond=1&Pub=1&Y=2017>Complete Dance Programme for 2017</a>, <a href=/line-up/dance/index.php?Y=2017>Dance Line Up 2017</a></b><p>

<p>In 2018, it's going to be another fun filled weekend of colourful dance displays through the streets of Wimborne.<p>

Dance teams already confirmed for 2018 include:<p>


<?php
    include_once ("int/fest.php");
    include_once ("int/ProgLib.php");
//    echo "<b><form method=Post action=/int/VenueUse.php class=staffform>";
//    echo "<input type=submit name=a value='Show Dance at' id=staffformid>" . 
//		fm_select(Get_Venues(0,' WHERE Dance=1 '),0,'v',0," onchange=this.form.submit()") . "</form></b><p>\n";
?>

<p>Click on the name of a team, or their photograph to find out more about them and where they are dancing.</p>



<?php
  global $db,$Coming_Type,$YEAR;
  $SideQ = $db->query("SELECT s.*, y.* FROM Sides AS s, SideYear AS y " .
           "WHERE s.SideId=y.SideId AND y.year=$YEAR AND y.Coming=" . $Coming_Type['Y'] . " AND s.IsASide=1 ORDER BY s.Importance DESC, s.Name");
  
  while($side = $SideQ->fetch_assoc()) {

   echo "<div class=floatleft>";
   echo "<div class=mini>";
   echo "<a href=/int/ShowDance.php?sidenum=" . $side['SideId'] . ">";
   if ($side['Photo']) echo "<img class=miniimg src='" . $side['Photo'] ."'>";
   echo "<h2 class=minittl>" . $side['Name'] . "</h2></a>";
   echo "<div class=minitxt>" . $side['Description'] . "</div>";
   echo "</div>";
   echo "</div>";

  }

?>



</div>
<?php include("files/footer.php"); ?>
</body>
</html>
