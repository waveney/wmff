<?php
  include_once("int/fest.php");

  dohead("Getting Here");

  include_once("int/ProgLib.php");

?>
<script language=Javascript defer>
function ShowBus(Route) {
  $('#TimeTab').load("/files/BusRoute" +Route );
}
</script>

<h2 class="subtitle">Buses</h2>
<a name=Buses></a>Regular buses service Wimborne during the folk festival from Poole and Bournemouth. Bus services are operated by 
<a href="http://morebus.co.uk" target="_blank">More Buses</a> (3, 4 & 13).<p>

Route 3 runs every 30 minutes to and from <b>Poole</b> to <b>Wimborne</b> Monday to Saturday daytime (and is faster).<p>
Route 4 runs every 30 minutes to and from <b>Poole</b> to <b>Wimborne</b> every day (but is slower). 
Note: Only half of Route 4 Buses go as far as Wimborne, only those that do are listed in the tables below.<p>

Route 13 goes to and from <b>Bournemouth</b> to <b>Wimborne</b> every 30 minutes Monday to Saturday daytime and hourly on Sunday and the evening.<p>

These are the currently publicised Bus times, there will be additional buses in the evenings (until 1:30am), over the festival, 
when they known they will be shown here.<p>

<table border class=TueTab>
<tr><td>Route<td colspan=3>To Wimborne<td colspan=3>From Wimborne
<tr><td>Route 3<td><a onclick=ShowBus('3.1') class=FakeLink>Friday</a>
		<td><a onclick=ShowBus('3.2') class=FakeLink>Saturday</a>
		<td>
                <td><a onclick=ShowBus('3.4') class=FakeLink>Friday</a>
		<td><a onclick=ShowBus('3.5') class=FakeLink>Saturday</a>
		<td>
<tr><td>Route 4<td><a onclick=ShowBus('4.1') class=FakeLink>Friday</a>
		<td><a onclick=ShowBus('4.2') class=FakeLink>Saturday</a>
		<td><a onclick=ShowBus('4.3') class=FakeLink>Sunday</a>
                <td><a onclick=ShowBus('4.4') class=FakeLink>Friday</a>
		<td><a onclick=ShowBus('4.5') class=FakeLink>Saturday</a>
		<td><a onclick=ShowBus('4.6') class=FakeLink>Sunday</a>
<tr><td>Route 13<td><a onclick=ShowBus('13.1') class=FakeLink>Friday</a>
		<td><a onclick=ShowBus('13.2') class=FakeLink>Saturday</a>
		<td><a onclick=ShowBus('13.3') class=FakeLink>Sunday</a>
                <td><a onclick=ShowBus('13.4') class=FakeLink>Friday</a>
		<td><a onclick=ShowBus('13.5') class=FakeLink>Saturday</a>
		<td><a onclick=ShowBus('13.6') class=FakeLink>Sunday</a>
</table>

<div id=TimeTab></div>

<?php 
  dotail();
?>
