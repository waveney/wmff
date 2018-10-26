<?php
  include_once("int/fest.php");

  dohead("Camping");
  global $MASTER;

  include_once("int/MapLib.php");
?>
<h2 class="maintitle">Camping</h2>
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
if ($MASTER['TicketControl'] == 1) {
?>
Camping at Meadows Campsite this year is <strong>&pound;10</strong> for the first night and <strong>&pound;8</strong> for each additional night.  
Under 8's are free.<p>

<?php
  if ($MASTER['BookingFee']) echo "Please note that there is a booking fee of " . $MASTER['BookingFee'] . " when ordering tickets online. <p>";
?>

The site has toilets, showers, food and good 24 hour security. Entry to the campsite is by camping wristband only.<p>

Please be aware that access to/from the town on foot is over Julian's bridge, which has no footpath, so please take care when crossing at all times.</p>

This year the campsite will be available from Thursday (5pm onwards).<p>


<table cellspacing="5" cellpadding="5" style="background-color:#59B404; border-color:#59B404; max-width:900;">
<?php
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
   foreach ($Avails as $txt=>$dat) {
     if (!$MASTER['CampingCode_' . $dat[0] ]) continue;
     echo "<tr><td>$txt Camping<td>" . Print_Pence($MASTER['CampingPrice' . $dat[1] . 'Day']*100) . "<td>";
     echo "<a href='https://www.ticketsource.co.uk/date/" . $MASTER['CampingCode_' . $dat[0] ] . "' target=_blank><b>Buy Now</b></a>";
   }
?>
</table><p>

<?php 
} else {
?>

<b>Online booking has now closed</b><p>

Camping at Meadows Campsite this year is from <strong>&pound;8</strong> per person per night. Under 8's are free.<p>

The site has toilets, showers, food and good 24 hour security. Entry to the campsite is by camping wristband only.<p>

Please be aware that access to/from the town on foot is over Julian's bridge, which has no footpath, so please take care when crossing at all times.</p>

<?php
}
?>

For more information about the campsite, visit <a href="http://merleyhallfarm.co.uk/camping" rel="tag"><strong>MerleyHallFarm.co.uk</strong></a>, but
please book through these links above.<p>

<?php
  dotail();
?>
