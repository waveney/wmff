<?php
  include_once("int/fest.php");

  dohead("Camping");
  global $MASTER;

  include_once("int/MapLib.php");
?>
<h2 class="maintitle">Camping</h2>
<div class=rightdiv>
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
Camping at Meadows Campsite this year is <strong>&pound;7.50</strong> per person per night (+ booking fee) when booked in 
advance online or <strong>&pound;8.50</strong> per person per night on the gate. Under 10's are free.<p>

The site has toilets, showers, food and good 24 hour security. Entry to the campsite is by camping wristband only.<p>

Please be aware that access to/from the town on foot is over Julian's bridge, which has no footpath, so please take care when crossing at all times.</p>

<b>Note: Online Ticket sales will stop at midnight on Thursday 7th of June.  Event tickets and passes will be available from the 
Allendale Information point once it has opened on Friday at 2pm.  Camping tickets may be bought at the gate.</b><p>.

<table cellspacing="5" cellpadding="5" style="background-color:#59B404; border-color:#59B404; width:50%;">
<tr><td>Weekend Camping (Friday to Monday morning)<td>&pound;22.50<td>
<a href="https://www.ticketsource.co.uk/date/420204" target=_blank>
<img border="0" width="130" height="56" alt="Book now" src="https://www.ticketsource.co.uk/images/bookNow/bookNow-black-small.png">
</a>

<tr><td>Friday only Camping<td>&pound;7.50<td>
<a href="https://www.ticketsource.co.uk/date/420201" target=_blank>
<img border="0" width="130" height="56" alt="Book now" src="https://www.ticketsource.co.uk/images/bookNow/bookNow-black-small.png">
</a>

<tr><td>Friday and Saturday Camping<td>&pound;15.00<td>
<a href="https://www.ticketsource.co.uk/date/427252" target=_blank>
<img border="0" width="130" height="56" alt="Book now" src="https://www.ticketsource.co.uk/images/bookNow/bookNow-black-small.png">
</a>

<tr><td>Saturday only Camping<td>&pound;7.50<td>
<a href="https://www.ticketsource.co.uk/date/420202" target=_blank>
<img border="0" width="130" height="56" alt="Book now" src="https://www.ticketsource.co.uk/images/bookNow/bookNow-black-small.png">
</a>

<tr><td>Saturday and Sunday Camping<td>&pound;15.00<td>
<a href="https://www.ticketsource.co.uk/date/435615" target=_blank>
<img border="0" width="130" height="56" alt="Book now" src="https://www.ticketsource.co.uk/images/bookNow/bookNow-black-small.png">
</a>

<tr><td>Sunday only Camping<td>&pound;7.50<td>
<a href="https://www.ticketsource.co.uk/date/420203" target=_blank>
<img border="0" width="130" height="56" alt="Book now" src="https://www.ticketsource.co.uk/images/bookNow/bookNow-black-small.png">
</a>

</table>

<?php 
} else {
?>

<b>Online booking has now closed</b><p>

Camping at Meadows Campsite this year is <strong>&pound;8.50</strong> per person per night at the gate. Under 10's are free.<p>

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
