<?php
  include_once("int/fest.php");
  
  doheadpart('8, 9, 10 June 2018');

  include("files/facebook.php");
  include("int/TradeLib.php");
  echo '<script src="/js/HomePage.js"></script>';
  echo "</head><body>\n";
  echo '<a href="/" ><h1>Wimborne Minster Folk Festival | 8, 9, 10 June 2018</h1></a>';
  include_once("files/navigation.php");
  echo "<div class=content>";
?>

<center><h2 class="maintitle">Welcome to Wimborne Minster Folk Festival 2018</h2></center>

<!--
<p>Go on, get social with us on <a href="http://facebook.com/WimborneFolk" rel="tag"><strong>Facebook</strong></a>, <a href="http://twitter.com/WimborneFolk" rel="tag"><strong>Twitter</strong></a> & <a href="http://instagram.com/WimborneFolk" rel="tag"><strong>Instagram</strong></a> with <strong>#WimborneFolk</strong>.</p>

<div class="rslides_container" style="margin:0 0 25px 0;">
<ul class="rslides" id="slider1">
  <li><a href="/info/trade" ><img src="/images/Highstreet-Traders-2016.jpg"></a></li>
  <li><img src="/images/Justin-Wikkaman-2016.jpg"></li>
  <li><img src="/images/Happy-Morris-Dancer-2016.jpg"></li>
  <li><img src="/images/Ringwood-Pipe-Band-2016.jpg"></li>
</ul>
</div>
-->
<div class=centhead>In the second weekend of June, we will be bringing the town alive with an exciting mix of music &amp; dance. 
You can wander through Wimborne and enjoy any of the free shows, or buy tickets to see one of our headline acts.</div>
<div id="flex">

<?php 
global $db,$THISYEAR,$Coming_Type;
$ans = $db->query("SELECT count(*) AS Total FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$THISYEAR AND y.Coming=" . $Coming_Type['Y']);
$sc = 0;
if ($ans) {
  $r = $ans->fetch_assoc();
  $sc=$r['Total'];
}

$ans = $db->query("SELECT s.Photo,s.SideId FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$THISYEAR AND s.Photo!='' AND y.Coming=" . 
		$Coming_Type['Y'] . " ORDER BY RAND() LIMIT 1");
if ($ans) {
  $p = $ans->fetch_assoc();
  $Photo = $p['Photo'];
} else {
  $Photo = "/images/Hobos-Morris-2016.jpg";
}
  $ans = $db->query("SELECT s.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$THISYEAR AND s.Photo!='' AND y.Coming=" . 
			$Coming_Type['Y'] . " AND s.Importance!=0 ORDER BY RAND() LIMIT 2");
  if (!$ans) {
    $ans = $db->query("SELECT s.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$THISYEAR AND s.Photo!='' AND y.Coming=" . 
			$Coming_Type['Y'] . " ORDER BY RAND() LIMIT 2");
  }
  if ($ans) {
    $stuff = $ans->fetch_assoc();
    if ($stuff['SideId'] == $p['SideId']) {
      $newstuff = $ans->fetch_assoc();
      if ($newstuff) $stuff = $newstuff;
    }
    if ($stuff['Photo'] || $stuff['Description']) {
      echo "<div class=article>";
      echo "<a href=/int/ShowDance.php?sidenum=" . $stuff['SideId'] . " >";
      echo "<h2 class=articlettl>" . $stuff['Name'] . "</h2>";
      if ($stuff['Photo']) echo "<img class=articleimg src=" . $stuff['Photo'] . " alt='Wimborne Minster Folk Festival' />";
      echo "</a>";
      if ($stuff['Description']) echo "<p class=articletxt>" . $stuff['Description'] . "\n";
      echo "</div>";
    }
  }

  echo "<div class=article>";
  echo "<a href=/line-up/dance >";
  echo "<h2 class=articlettl>Dancing in 2018</h2>";
  echo "<img class=articleimg src=$Photo alt='Wimborne Minster Folk Festival' /></a>";
  echo "<p class=articletxt>$sc Dance teams have already confirmed for 2018. Many of your favourite teams and some brand new faces.\n";
  echo "</div>";
?>

<div class="article">
<a href="/tickets" >
<h2 class="articlettl">Buy Tickets and Wristbands</h2>
<img class="articleimg" src="/images/Weekend-Wristband.jpg" alt="Wimborne Minster Folk Festival" /></a>
<p class="articletxt">Weekend and Day Passes are now on sale for the festival weekend.</p>
<p>Many event tickets are also available, more to come.</p>
</div>

<div class="article">
<a href="/info/trade" >
<h2 class="articlettl">Trade Stand Booking is Open</h2>
<img class="articleimg" src="/images/Trade-Stand-2016.jpg" alt="Wimborne Minster Folk Festival" /></a>
<p class="articletxt">Booking is open for traders of all types.<p>
</div>

<div class="article">
<a href="/info/camping" >
<h2 class="articlettl">Official Festival Campsite</h2>
<img class="articleimg" src="/images/Wimborne-Folk-Festival-Campsite.jpg" alt="Wimborne Minster Folk Festival" /></a>
<p class="articletxt">Plan your stay at the festival and book a pitch at Meadows Campsite, which is within a short few minute walk from the town centre.</p>
</div>

<?php 
  $ans = $db->query("SELECT count(*) AS Total FROM Sides s, ActYear y WHERE s.SideId=y.SideId AND y.Year=$THISYEAR AND y.YearState>0 ");
  $sc = 0;
  if ($ans) {
    $r = $ans->fetch_assoc();
    $sc=$r['Total'];
  }

  $ans = $db->query("SELECT s.Photo,s.SideId FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$THISYEAR AND s.Photo!='' AND y.YearState>0 " . 
			" ORDER BY RAND() LIMIT 1");
  if ($ans) {
    $p = $ans->fetch_assoc();
    $Photo = $p['Photo'];
  } else {
    $Photo = "/images/Hobos-Morris-2016.jpg";
  }

  $ans = $db->query("SELECT s.* FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$THISYEAR AND s.Photo!='' AND y.YearState>0 " . 
			" AND s.Importance!=0 ORDER BY RAND() LIMIT 2");
  if (!$ans) {
    $ans = $db->query("SELECT s.* FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$THISYEAR AND s.Photo!='' AND y.YearState>0 " . 
			" ORDER BY RAND() LIMIT 2");
  }
  if ($ans) {
    $stuff = $ans->fetch_assoc();
    if ($stuff['SideId'] == $p['SideId']) $stuff = $ans->fetch_assoc();
    if ($stuff['Photo'] || $stuff['Description']) {
      echo "<div class=article>";
      echo "<a href=/int/ShowMusic.php?sidenum=" . $stuff['SideId'] . " >";
      echo "<h2 class=articlettl>" . $stuff['Name'] . "</h2>";
      if ($stuff['Photo']) echo "<img class=articleimg src=" . $stuff['Photo'] . " alt='Wimborne Minster Folk Festival' />";
      echo "</a>";
      if ($stuff['Description']) echo "<p class=articletxt>" . $stuff['Description'] . "\n";
      echo "</div>";
    }
  }
  echo "<div class=article>";
  echo "<a href=/line-up/music >";
  echo "<h2 class=articlettl>Music in 2018</h2>";
  echo "<img class=articleimg src=$Photo alt='Wimborne Minster Folk Festival' /></a>";
  echo "<p class=articletxt>$sc Music acts have already confirmed for 2018.\n";
  echo "</div>";
?>

</div>

<?php
  include_once("int/NewsLib.php");
  $items = Get_All_News(0,5);
  if ($items) {
    echo "<h2 class=maintitle><a href=/news>Latest News</a></h2>\n";
    echo "Stay up to date with all the latest happenings from folk festival HQ, in the lead up to the festival and during the festival weekend by " .
	"keeping an eye on our <a href=/news><strong>Latest News</strong></a> page of the website.<p>\n";

    foreach ($items as $n) News_Item($n,500);
  }

  echo '<h2 class="subtitle">Sponsors & Supporters</h2>';
  echo "Wimborne Minster Folk Festival would not be possible without the amazing help and generosity of the following companies and organisations:<p>";

  include_once("int/TradeLib.php"); 
  
  $Spons = Get_Sponsors();
  echo "<div hidden>";
  foreach ($Spons as $s) {
    echo "<li class=SponsorsIds id=" .$s['id'] . ">";
    if ($s['Website']) echo weblinksimple($s['Website']);
    if ($s['Image']) echo "<img src='" . $s['Image'] . "' width=150>";
    if (!$s['Image']) echo "<h2 style='font-size:36px';>" . $s['Name'] . "</h2>";
    if ($s['Website']) echo "</a>";
  }
  echo "</div>\n";
  echo "<center><table style='table-layout: fixed;'><tr id=SponsorRow></table></center><p>";

  dotail();
?>
