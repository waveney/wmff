<?php
  include_once("int/fest.php");

  dohead("Travel: Road, Buses, Trains and Taxis",[],1);

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
function ShowBus(Route) {
  $('#TimeTab').load("/files/BusRoute" +Route );
}
</script>

<h2>Getting To Wimborne</h2>
Wimborne Minster Folk Festival takes place in the historic market town of Wimborne Minster in Dorset.<p>

Junmp to: <a href=#Buses class='DaySkipTo sattab'>Public Transport</a> <a href=#Taxis class='DaySkipTo suntab'>Taxis</a><p>
<div class=BorderBox>
<h2>By Road</h2>

Wimborne has good road connections with easy access from the A31, B3082 and B3078.<p>

<a href=InfoParking.php>Information on Parking - Car parks and bicycle parking</a>.<p>

</div><div class=BorderBox>
<h2>By Public Transport</h2>

The most convienent train station is <strong>Poole</strong>, with train services operated by 
<a href="https://www.southwesternrailway.com" target="_blank">South Western Railway</a>.<p>

<a name=Buses></a>Regular buses service Wimborne during the folk festival from Poole and Bournemouth. Bus services are operated by 
<a href="http://morebus.co.uk" target="_blank">More Buses</a> (routes 3, 4 &amp; 13) and <a href="https://www.yellowbuses.co.uk" target="_blank">Yellow Buses</a> (route 6). <p>

Route 3 runs every 30 minutes to and from <b>Poole</b> to <b>Wimborne</b> Monday to Saturday daytime (and is faster).<p>
Route 4 runs every 30 minutes to and from <b>Poole</b> to <b>Wimborne</b> every day (but is slower). <p>
Note: Only half of Route 4 Buses go as far as Wimborne, only those that do, are listed in the tables below.<p>

Route 13 goes to and from <b>Bournemouth</b> to <b>Wimborne</b> every 30 minutes Monday to Saturday daytime and hourly on Sunday and the evening.<p>

Route 6 goes to and from <b>Bournemouth</b> to <b>Wimborne</b> every 30 minutes Monday to Saturday daytime (only).<p>

Note there will be a temporary bus stop by the Campsite, this is on the route of the 3, and the 4's that go via Corfe Mullen in the evenings and on Sunday.<p>

Buses in <span class=ExtraBus>Bold Blue</span> are additional buses being run for the festival.<p>
<div class=tablecont>
<table class=InfoTable>
<tr><td>Route<td>Operator<td colspan=3>To Wimborne<td colspan=3>From Wimborne
<tr><td>Route 3<td>More<td><a onclick=ShowBus('3.1') class=FakeLink>Friday</a>
		<td><a onclick=ShowBus('3.2') class=FakeLink>Saturday</a>
		<td>
                <td><a onclick=ShowBus('3.4') class=FakeLink>Friday</a>
		<td><a onclick=ShowBus('3.5') class=FakeLink>Saturday</a>
		<td>
<tr><td>Route 4<td>More<td><a onclick=ShowBus('4.1') class=FakeLink>Friday</a>
		<td><a onclick=ShowBus('4.2') class=FakeLink>Saturday</a>
		<td><a onclick=ShowBus('4.3') class=FakeLink>Sunday</a>
                <td><a onclick=ShowBus('4.4') class=FakeLink>Friday</a>
		<td><a onclick=ShowBus('4.5') class=FakeLink>Saturday</a>
		<td><a onclick=ShowBus('4.6') class=FakeLink>Sunday</a>
<tr><td>Route 13<td>More<td><a onclick=ShowBus('13.1') class=FakeLink>Friday</a>
		<td><a onclick=ShowBus('13.2') class=FakeLink>Saturday</a>
		<td><a onclick=ShowBus('13.3') class=FakeLink>Sunday</a>
                <td><a onclick=ShowBus('13.4') class=FakeLink>Friday</a>
		<td><a onclick=ShowBus('13.5') class=FakeLink>Saturday</a>
		<td><a onclick=ShowBus('13.6') class=FakeLink>Sunday</a>
<tr><td>Route 6<td>Yellow<td><a onclick=ShowBus('6.1') class=FakeLink>Friday</a>
		<td><a onclick=ShowBus('6.2') class=FakeLink>Saturday</a>
		<td>
                <td><a onclick=ShowBus('6.4') class=FakeLink>Friday</a>
		<td><a onclick=ShowBus('6.5') class=FakeLink>Saturday</a>
		<td>
</table></div><p>

<div id=TimeTab></div>
</div><div class=BorderBox>
<h2><a name=Taxis></a>By Taxi</h2>
This is a list of Taxi firms.  Note only those from East Dorset can wait at Taxi ranks in Wimborne, others can pick up booked passengers.<p>
<div class=tablecont><table class=InfoTable>
<tr><td>Authority<td>Name<td>Phone
<?php
  include_once("int/TradeLib.php");
  global $TaxiAuthorities;
  $Taxis = Get_Taxis();
  foreach($Taxis as $t) echo "<tr><td>" . $TaxiAuthorities[$t['Authority']] . "<td>" . $t['SN'] . "<td>" . $t['Phone'];
?>
</table></div><p>
</div>
<?php 
  dotail();
?>
