<?php
  include_once("int/fest.php");
  include_once("int/DateTime.php");
  dohead("Camping",[],'images/icons/CampingBanner.png');
  global $YEARDATA;

  include_once("int/MapLib.php");
?>

There will be no festival provided campsites this year.  There are some around that may have space.<p>

<?php

  dotail();
/*
<div class=rightdiv style='margin-bottom:50'>
<div class="biodiv">
<img src="http://wimbornefolk.co.uk/images/Wimborne-Folk-Festival-Campsite.jpg" alt="Wimborne Minster Folk Festival" class="bioimg" />
The Meadows Campsite during the folk festival weekend<p>
</div><br clear=all>
<div class=MapWrap style='height:300;'>
<div id=DirPane><div id=DirPaneTop></div><div id=Directions></div>
<p><div id=map></div></div>
<?php Init_Map(1,2,14,0) ?>
</div></div>

Our official campsite is run by Meadows Camping, a picturesque, local, secure, well managed temporary campsite for the folk festival weekend. 
The site is just a 10 minute walk from the town centre over Julian's bridge.<p>

<?php
if ($YEARDATA['CampingControl'] == 1) {

  if ($YEARDATA['CampingControl'] == 1 && Days2Festival() < 10) {
    echo "<b>Note</b>: Online ticket sales will close at 6am on " . FestDate(0,'F') . " , after that tickets and passes can be purchased from Festival Information in the Square. " .
         "Camping may be available at the campsite gate if there is space available.<p>";
  }
  

?>
Camping at Meadows Campsite this year is <strong>&pound;10</strong> for the first night and <strong>&pound;8</strong> for each additional night.  
Under 8's are free.<p>

<?php
  if ($YEARDATA['BookingFee']) echo "Please note that there is a booking fee of " . $YEARDATA['BookingFee'] . " when ordering tickets online. <p>";
?>

The site has toilets, showers, food and good 24 hour security. Entry to the campsite is by camping wristband only.<p>

Please be aware that access to/from the town on foot is over Julian's bridge, which has no footpath, so please take care when crossing at all times.  There should be
traffic lights on the bridge from 8pm Friday until Sunday evening.</p>

This year the campsite will be available from Thursday (5pm onwards).<p>

Note: for SatNav, the nearest postcode to the campsite is BH21 1EF (do not use postcode for the farm it over a mile away by road).<p>

<?php
  echo "<div ><table class=InfoTable>"; // Not using tablecont for this small table
  echo "<tr><th colspan=3>Camping Tickets";
  
  $Avails = [
             'Thursday, Friday, Saturday and Sunday nights'=>['TFSS',4],
             'Thursday, Friday and Saturday nights'=>['TFSx',3],
             'Thursday and Friday nights'=>['TFxx',2],             
             'Thursday night only'=>['Txxx',1],
             'Friday, Saturday and Sunday nights'=>['xFSS',3],
             'Friday and Saturday nights'=>['xFSx',2],
             'Friday night only'=>['xFxx',1],
             'Saturday and Sunday nights'=>['xxSS',2],
             'Saturday night only'=>['xxSx',1],
             'Sunday night only'=>['xxxS',1],
            ];
   $DName = ['Thursday','Friday','Saturday','Sunday'];
   foreach ($Avails as $txt=>$dat) {
     if (!$YEARDATA['CampingCode_' . $dat[0] ]) continue;
     echo "<tr><td>Camping for:";
     foreach (str_split($dat[0]) as $i=>$c) echo "<td>" . ($c == 'x'?"":$DName[$i]);
     echo "<td>" . Print_Pence($YEARDATA['CampingPrice' . $dat[1] . 'Day']*100) . "<td>";
     if (substr($YEARDATA['CampingCode_' . $dat[0] ],0,1) != '-') {
       echo "<a href='" . $YEARDATA['CampingCode_' . $dat[0] ] . "' target=_blank><b>Buy Now</b></a>";
     } else {
       echo "Closed";
     }
   }
  echo "</table></div><p>";
} elseif ($YEARDATA['CampingControl'] == 4) {
    echo "The camping can not be booked directly through the festival this year from here.  " .
          "Please visit <a href=http://merleyhallfarm.co.uk/camping target=_blank>MerleyHallFarm.co.uk</a><p>";

    echo "The site has toilets, showers, food and good 24 hour security. Entry to the campsite is by camping wristband only.<p>

Please be aware that access to/from the town on foot is over Julian's bridge, which has no footpath, so please take care when crossing at all times.  There should be
traffic lights on the bridge from 8pm Friday until Sunday evening.</p>

Note: for SatNav, the nearest postcode to the campsite is BH21 1EF (do not use postcode for the farm it over a mile away by road).<p>";


} elseif ($YEARDATA['CampingControl'] > 1) {
  echo "<b>Online booking has now closed</b>  Camping may be available at the campsite gate if there is space available.<p>

Camping at Meadows Campsite this year is from <strong>&pound;10</strong> per person per night. Under 8's are free.<p>

The site has toilets, showers, food and good 24 hour security. Entry to the campsite is by camping wristband only.<p>

Please be aware that access to/from the town on foot is over Julian's bridge, which has no footpath, so please take care when crossing at all times.  There should be
traffic lights on the bridge from 8pm Friday until Sunday evening.</p>

Note: for SatNav, the nearest postcode to the campsite is BH21 1EF (do not use postcode for the farm it over a mile away by road).<p>";

} else {
  echo "Booking for Camping is not yet open<p>";
}

  echo "For more information about the campsite, visit <a href=http://merleyhallfarm.co.uk/camping>MerleyHallFarm.co.uk</a>, 
Call  07557306219, or email: <a href=mailto:karen@merleyhallfarm.co.uk>karen@merleyhallfarm.co.uk</a>.<p>";

  dotail();*/
