<?php
  include_once("int/fest.php");

  set_ShowYear();  
  dohead('7,8,9 June 2019', '/js/WmffAds.js', "/js/HomePage.js");

  global $MASTER_DATA;
  include("int/TradeLib.php");
  include("int/NewsLib.php");
  include("int/DispLib.php");
  $host= "https://" . $_SERVER['HTTP_HOST'];
  
  Show_Articles_For("Top");
  if (!Feature('UseArticles')) { // Old code to be removed as soon as change over complete
?>
<center><h2 class="maintitle">Welcome to Wimborne Minster Folk Festival</h2></center>
<div class=centhead>The 2019 Festival will be from Friday 7th to Sunday 9th of June 2019.<br>
<b style='color:Red'>NOTE DATES CHANGED</b><br>
Everything you can see on this site is from the fabulous 2018 festival. </div> 
<div id="flex">

<div class=article>
<a href=https://wimbornefolk.co.uk/int/ShowDance.php?sidenum=371>
<h2 class=articlettl>Tashi Lhunpo Tibetan Monks</h2>
<img class=articleimg src=/images/2018Pics/MOnks2.jpg alt='Wimborne Minster Folk Festival'></a>
<p class=articletxt>The Monks are already here in Wimborne making their Mandala in the Priest House Museum.  They have a stall in the Priest House garden with
lots of lovely things...
</p>
</div>

<?php 
global $db,$SHOWYEAR,$Coming_Type;
$ans = $db->query("SELECT count(*) AS Total FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$SHOWYEAR AND y.Coming=" . $Coming_Type['Y']);
$sc = 0;
if ($ans) {
  $r = $ans->fetch_assoc();
  $sc=$r['Total'];
}
$ans = $db->query("SELECT s.Photo,s.SideId FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.Coming=" . 
		$Coming_Type['Y'] . " ORDER BY RAND() LIMIT 1");
if ($ans) {
  $p = $ans->fetch_assoc();
  $Photo = $p['Photo'];
} else {
  $Photo = "/images/Hobos-Morris-2016.jpg";
}
  $ans = $db->query("SELECT s.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.Coming=" . 
			$Coming_Type['Y'] . " AND s.Importance!=0 ORDER BY RAND() LIMIT 2");
  if (!$ans) {
    $ans = $db->query("SELECT s.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.Coming=" . 
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
      echo "<h2 class=articlettl>" . $stuff['SN'] . "</h2>";
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

<div class=article>
<a href=https://wimbornefolk.co.uk/int/EventShow.php?e=965.php>
<h2 class=articlettl>Comedy Night</h2>
<img class=articleimg src=http://media.ents24network.com/image/000/247/590/096d19bc204cb59115ff8765d67bd68aa54007f7.jpg alt='Wimborne Minster Folk Festival'></a>
<p class=articletxt>'A laugh a Minute' Comedy Night Friday 8th QE School 8pm - 10:30pm Phil Jerrod, The Polly Morris Band plus the six finalists of 
The Comedy Competition.
</div>

<div class=article>
<h2 class=articlettl>Jingle on Radio Wimborne and Forest FM</h2>
<video width=400 height=260 controls src="files/Wimborne_MFF_Slide_Show.mp4"></video>
</div>

<div class=article>
<h2 class=articlettl>Next year's dates</h2><b style='color:Red'>NOTE THESE HAVE CHANGED</b>.
<p class=articletxt>The 2019 Wimborne Minster Folk Festival will be from 7th to 9th of June 2019.
</div>

<div class=article>
<?php
  echo "<a href=$host/int/VenueShow.php?v=44>";
?>
<h2 class=articlettl>Microbreweries</h2>
<img class=articleimg src=/images/icons/beerfest.jpeg></a>
<p class=articletxt>New for 2018 - the Microbreweries.  Just off the high st, with 8 local breweries, food, dance and music.
</div>

<?php
  $ans = $db->query("SELECT count(*) AS Total FROM Sides s, ActYear y WHERE s.SideId=y.SideId AND y.Year=$SHOWYEAR AND y.YearState>0 ");
  $sc = 0;
  if ($ans) {
    $r = $ans->fetch_assoc();
    $sc=$r['Total'];
  }
  $ans = $db->query("SELECT s.Photo,s.SideId FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.YearState>0 " . 
			" ORDER BY RAND() LIMIT 1");
  if ($ans) {
    $p = $ans->fetch_assoc();
    $Photo = $p['Photo'];
  } else {
    $Photo = "/images/Hobos-Morris-2016.jpg";
  }
  $ans = $db->query("SELECT s.* FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.YearState>0 " . 
			" AND s.Importance!=0 ORDER BY RAND() LIMIT 2");
  if (!$ans) {
    $ans = $db->query("SELECT s.* FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.YearState>0 " . 
			" ORDER BY RAND() LIMIT 2");
  }
  if ($ans) {
    $stuff = $ans->fetch_assoc();
    if ($stuff['SideId'] == $p['SideId']) $stuff = $ans->fetch_assoc();
    if ($stuff['Photo'] || $stuff['Description']) {
      echo "<div class=article>";
      echo "<a href=$host/int/ShowMusic.php?sidenum=" . $stuff['SideId'] . " >";
      echo "<h2 class=articlettl>" . $stuff['SN'] . "</h2>";
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
  echo "</div>\n";

} // End of old content that will be going

  echo "<br clear=all>";
  echo '<h2 class="subtitle">Sponsors & Supporters</h2>';
  echo "Wimborne Minster Folk Festival would not be possible without the amazing help and generosity of the following companies and organisations:<p>";
  
  $Spons = Get_Sponsors();
  echo "<div hidden>";
  foreach ($Spons as $s) {
    echo "<li class=SponsorsIds id=" .$s['id'] . "><div class=sponcontainer>";
    if ($s['Website']) echo weblinksimple($s['Website']);
    if ($s['Image']) echo "<img src='" . $s['Image'] . "' width=150>";
    if (!$s['Image'] || $s['IandT']) echo "<h2 class=sponText>" . $s['SN'] . "</h2>";
    if ($s['Website']) echo "</a>";
    echo "</div>";
  }
  echo "</div>\n";
  echo "<center><table style='table-layout: fixed;'><tr id=SponsorRow></table></center><p>";

  dotail();
?>
