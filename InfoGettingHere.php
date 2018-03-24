<?php
  include_once("int/fest.php");

  dohead("Getting Here");

  include_once("int/MapLib.php");
  include_once("int/ProgLib.php");

  echo "<div class=venueimg>";
  echo "<button onclick=ShowDirect(1000001)>Directions</button>\n";
  echo "<div id=MapWrap>";
  echo "<div id=DirPaneWrap><div id=DirPane><div id=DirPaneTop></div><div id=Directions></div></div></div>";
  echo "<p><div id=map></div></div>";
  echo "</div>\n";
  Init_Map(-1,0,10);
//  echo "</div></div>";
?>
<script language=Javascript defer>
function ShowBus(Route,Direct) {
  $('#Timetab').load("/cache/BusRoute" +Route );
}

</script>

<h2 class="maintitle">Getting Here</h2>
Wimborne Minster Folk Festival takes place in the historic market town of Wimborne Minster in Dorset.<p>

<a href=#Buses class=PurpButton>Busses</a> <a href=#Taxis class=PurpButton>Taxis</a><p>

<h2 class="subtitle">By Road</h2>

Wimborne has good road connections with easy access from the A31, B3082 and B3078.<p>

<h2 class="subtitle">By Public Transport</h2>

The most convienent train station is <strong>Poole</strong>, with train services operated by 
<a href="http://www.southwesttrains.co.uk" rel="tag" target="_blank">South West Trains</a>.<p>

<a name=Buses></a>Regular buses service Wimborne during the folk festival from Poole and Bournemouth. Bus services are operated by 
<a href="http://morebus.co.uk" rel="tag" target="_blank">More Buses</a> (3, 4 & 13).<p>

Route 3 runs every 30 minutes to and from <b>Poole</b> Monday to Saturday daytime (and is relatively fast).<p>
Route 4 runs every 30 minutes to and from <b>Poole</b> every day (but is slower).<p>
Route 13 goes to and from <b>Bournemouth</b> every 30 minutes Monday to Saturday and hourly on Sunday.<p>

These are the currently publicised Bus times, there will probably be additional buses at some times over the festival.<p>
<table border class=TueTab>
<tr><td>To Wimborne<td>From Wimborne
<tr><td><a onclick=ShowBus('3.1') class=FakeLink>Route 3 from Poole</a><td><a onclick=ShowBus('3.2') class=FakeLink>Route 3 to Poole</a>
<tr><td><a onclick=ShowBus('4.1') class=FakeLink>Route 4 from Poole</a><td><a onclick=ShowBus('4.2') class=FakeLink>Route 4 to Poole</a>
<tr><td><a onclick=ShowBus('13.1') class=FakeLink>Route 13 from Bournemouth</a><td><a onclick=ShowBus('13.2') class=FakeLink>Route 13 to Bournemouth</a>
</table>

<div id=Timetab></div>

<h2 class="subtitle"><a name=Taxis></a>By Taxi</h2>
This is a list of Taxi firms.  Note only those from East Dorset can wait at Taxi ranks in Wimborne, others can pick up booked passengers.<p>
<table border class=WedTab>
<tr><td>Authority<td>Name<td>Phone
<?php
  include_once("int/TradeLib.php");
  global $TaxiAuthorities;
  $Taxis = Get_Taxis();
  foreach($Taxis as $t) echo "<tr><td>" . $TaxiAuthorities[$t['Authority']] . "<td>" . $t['SName'] . "<td>" . $t['Phone'];
?>
</table><p>

<?php 
  dotail();
?>
