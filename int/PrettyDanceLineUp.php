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
<p>It's going to be another fun filled weekend of colourful dance displays through the streets of Wimborne from the
dance sides below, some of whom have travelled from the USA and Europe to be with us!</p>

<p>Click on the name or picture for more information and their individual programme (when available)</p>

<p>There is also a <a href=/int/LineUpDance.php>Tabulated list</a> that can be sorted by type and day and other things.</p>

<div id="flex">

<?php
  global $db,$Coming_Type,$YEAR;
  $SideQ = $db->query("SELECT s.*, y.* FROM Sides AS s, SideYear AS y " .
           "WHERE s.SideId=y.SideId AND y.year=$YEAR AND y.Coming=" . $Coming_Type['Y'] . " ORDER BY s.Importance DESC, s.SName");

  while($side = $SideQ->fetch_assoc()) {

   echo "<div class=mini>";
   echo "<a href=/int/ShowDance.php?sidenum=" . $side['SideId'] . ">";
   if ($side['Photo']) echo "<img class=miniimg src='" . $side['Photo'] ."'>";
   echo "<h2 class=minittl>" . $side['SName'] . "</h2></a>";
//   echo "(" . $side['Type'] . ")<p>";
   echo "<div class=minitxt>" . $side['Description'] . "</div>";
   echo "</div>";

  }

?>

</div>


</div>
<?php include("files/footer.php"); ?>
</body>
</html>
