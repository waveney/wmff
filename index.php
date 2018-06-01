<?php
  include_once("int/fest.php");
  
  doheadpart('8, 9, 10 June 2018');

  global $MASTER;
  $V = $MASTER['V'];
  include("files/facebook.php");
  include("int/TradeLib.php");
  echo '<script src="/js/HomePage.js"></script>';
  echo "</head><body>\n";
  echo "<div id=HeadRow>";
  echo "<a href=/InfoBuses.php><img src=/images/icons/leftspon.jpg id=leftspon hidden></a>";
  echo "<a href=/><img id=HeadBan src=/images/icons/WimborneFolkV3Banner-V6.png ></a>";
  echo "<a href=http://www.hall-woodhouse.co.uk/find-your-perfect-pub/oddfellows-arms target=_blank><img src=/images/icons/rightspon.jpg id=rightspon hidden></a>";
  echo "</div>\n";
  echo "<script src=/js/WmffAds.js?V=$V></script>";
  include_once("files/navigation.php");
  echo "<div class=content>";
  $host= "https://" . $_SERVER['HTTP_HOST'];
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
<div class=centhead>In the second weekend of June, we will be bringing the town alive with an exciting mix of music &amp; dance. <br>
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
      echo "<a href=$host/int/ShowDance.php?sidenum=" . $stuff['SideId'] . " >";
      echo "<h2 class=articlettl>" . $stuff['SName'] . "</h2>";
      if ($stuff['Photo']) echo "<img class=articleimg src=" . $stuff['Photo'] . " alt='Wimborne Minster Folk Festival' />";
      echo "</a>";
      if ($stuff['Description']) echo "<p class=articletxt>" . $stuff['Description'] . "\n";
      echo "</div>";
    }
  }

  echo "<div class=article>";
  echo "<a href=$host/LineUpDance.php>";
  echo "<h2 class=articlettl>Dancing in 2018</h2>";
  echo "<img class=articleimg src=$Photo alt='Wimborne Minster Folk Festival'></a>";
  echo "<p class=articletxt>$sc Dance teams have already confirmed for 2018. Many of your favourite teams and some brand new faces.\n";
  echo "</div>";

?>
<!--
<div class=article>
<a href=$host/BuskersBash.php>
<h2 class=articlettl>Buskers Bash 2018</h2>
<img class=articleimg src=/images/Stratus5.jpg alt='Wimborne Minster Folk Festival'></a>
<p class=articletxt>
On Saturday 14th April, Wimborne will come alive to the sound of traditional street entertainment from a
range of buskers. Organised for the second year by the committee of Wimborne Minster Folk Festival.
Calling all those of you who would like to take part in this year's Busker's Bash.<p>

Events start in the square at 10:30 with Polly Morris and Buskers around town from 11:30.<p>
</div>
-->

<div class=article>
<?php
  echo "<a href=$host/Tickets.php>";
?>
<h2 class=articlettl>Buy Tickets and Wristbands</h2>
<img class=articleimg src=/images/Weekend-Wristband.jpg alt='Wimborne Minster Folk Festival'></a>
<p class=articletxt>Weekend and Day Passes are now on sale for the festival weekend.<br>Many event tickets are also available.  Online
purchases close at midnight Thursday June 7th.</p>
</div>

<!--
<div class=article>
<a href=$host/info/trade>
<h2 class=articlettl>Trade Stand Booking is Open</h2>
<img class=articleimg src=/images/Trade-Stand-2016.jpg alt='Wimborne Minster Folk Festival'></a>
<p class=articletxt>Booking is open for traders of all types.<p>
</div>
-->

<div class=article>
<h2 class=articlettl>Jingle on Radio Wimborne and Forest FM</h2>
<video width=500 height=320 controls src="files/Wimborne_MFF_Slide_Show.mp4"></video>
</div>

<div class=article>
<h2 class=articlettl>Next years dates</h2>
<p class=articletxt>The 2019 Wimborne Minster Folk Festival will be from 14th to 16th of June 2019.
</div>

<div class=article>
<?php
  echo "<a href=$host/int/VenueShow.php?v=44>";
?>
<h2 class=articlettl>Microbreweries</h2>
<img class=articleimg src=/images/icons/beerfest.jpeg></a>
<p class=articletxt>New for 2018 - the Microbreweries.  Just off the high st, with 8 local breweries, food, dance and music.
</div>

<!--
<div class=article>
<a href=$host/LaughOutLoud.php>
<h2 class=articlettl>Laugh Out Loud 2018</h2>
<img class=articleimgsml src=/images/Lol2017/IMG_4441.jpg alt='Wimborne Minster Folk Festival'></a>
<p class=articletxt>Are you funny? Do you want a chance to prove it - on stage, in front of a warm, supportive
audience? If your answer is <b>YES!</b> then our <b>Laugh Out Loud</b> New Act Competition is just
right for you.<P>
</div>

<div class=article>
<a href=$host/LiveNLoud.php>
<h2 class=articlettl>Live and Loud 2018</h2>
<img class=articleimgsml src='/images/Lnl2018/3-The Darwins.jpg' alt='Wimborne Minster Folk Festival'></a>
<p class=articletxt>Live and Loud has been running for several years now with the intention of finding local talent from within the community to 
showcase their work during the festival.<P>

The final was on 24th February (Status, Bowen & Pounds and The Darwins).<p>
</div>
-->

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
      echo "<a href=$host/int/ShowMusic.php?sidenum=" . $stuff['SideId'] . " >";
      echo "<h2 class=articlettl>" . $stuff['SName'] . "</h2>";
      if ($stuff['Photo']) echo "<img class=articleimg src=" . $stuff['Photo'] . " alt='Wimborne Minster Folk Festival' />";
      echo "</a>";
      if ($stuff['Description']) echo "<p class=articletxt>" . $stuff['Description'] . "\n";
      echo "</div>";
    }
  }
  echo "<div class=article>";
  echo "<a href=$host/LineUpMusic.php >";
  echo "<h2 class=articlettl>Music in 2018</h2>";
  echo "<img class=articleimg src=$Photo alt='Wimborne Minster Folk Festival' /></a>";
  echo "<p class=articletxt>$sc Music acts have already confirmed for 2018.\n";
  echo "</div>";

  echo "<div class=article>
<a href=$host/InfoCamping.php >
<h2 class=articlettl>Official Festival Campsite</h2>
<img class=articleimg src=/images/Wimborne-Folk-Festival-Campsite.jpg alt='Wimborne Minster Folk Festival'></a>
<p class=articletxt>Plan your stay at the festival and book a pitch at Meadows Campsite, which is within a short few minute walk from the town centre.
Online booking will close at midnight on Thursday June 7th (after which you can still pay at the gate)</p>
</div>
</div>
";

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

  include_once("int/TradeLib.php"); 
  
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
