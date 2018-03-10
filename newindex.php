<?php
  include_once("int/fest.php");
  
  doheadpart('8, 9, 10 June 2018');

  include("files/facebook.php");
  include("int/TradeLib.php");
  include("int/DispLib.php");
  echo '<script src="/js/HomePage.js"></script>';
  echo "</head><body>\n";
  echo '<a href="/" ><h1>Wimborne Minster Folk Festival | 8, 9, 10 June 2018</h1></a>';
  include_once("files/navigation.php");
  echo "<div class=content>";
  $host= "https://" . $_SERVER['HTTP_HOST'];
?>

<center><h2 class="maintitle">Welcome to Wimborne Minster Folk Festival 2018</h2></center>

<div class=centhead>In the second weekend of June, we will be bringing the town alive with an exciting mix of music &amp; dance. <br>
You can wander through Wimborne and enjoy any of the free shows, or buy tickets to see one of our headline acts.</div>

<?php 
  ShowArticles();

  include_once("int/NewsLib.php");
  $items = Get_All_News(0,5);
  if ($items) {
    echo "<h2 class=maintitle><a href=$host/news>Latest News</a></h2>\n";
    echo "Stay up to date with all the latest happenings from folk festival HQ, in the lead up to the festival and during the festival weekend by " .
	"keeping an eye on our <a href=/news><strong>Latest News</strong></a> page of the website.<p>\n";

    foreach ($items as $n) News_Item($n,500);
  }

  echo '<h2 class="subtitle">Sponsors & Supporters</h2>';
  echo "Wimborne Minster Folk Festival would not be possible without the amazing help and generosity of the following companies and organisations:<p>";

  $Spons = Get_Sponsors();
  echo "<div hidden>";
  foreach ($Spons as $s) {
    echo "<li class=SponsorsIds id=" .$s['id'] . "><div class=sponcontainer>";
    if ($s['Website']) echo weblinksimple($s['Website']);
    if ($s['Image']) echo "<img src='" . $s['Image'] . "' width=150>";
    if (!$s['Image'] || $s['IandT']) echo "<h2 class=sponText>" . $s['SName'] . "</h2>";
    if ($s['Website']) echo "</a>";
    echo "</div>";
  }
  echo "</div>\n";
  echo "<center><table style='table-layout: fixed;'><tr id=SponsorRow></table></center><p>";

  dotail();
?>
