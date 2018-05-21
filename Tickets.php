<?php
  include_once("int/fest.php");

  dohead("Tickets");

?>
<div class="biodiv">
<img src="http://wimbornefolk.co.uk/images/Mawkin-Accordion.jpg" alt="Wimborne Minster Folk Festival" class="bioimg" />
<p>Buy Tickets for Wimborne Minster Folk Festival 2018.</p>
</div>

<h2 class="maintitle">Buy Festival Tickets</h2>

Select from the options below to purchase your tickets for Wimborne Minster Folk Festival 2018. 
Your ticket will grant you access to official festival concerts and ceilidhs listed below. 
During the festival weekend there are unofficial events in Wimborne that these tickets do
not cover and we are unable to refund tickets bought in error.<p>

Please note that there is a booking fee of &pound;1.00 per ticket when ordering tickets online. 
Please take care whilst ordering tickets as we are unable to process exchanges or refunds.<p>

Please <a href="mailto:carers@wimbornefolk.co.uk">Contact Us</a> if you require a carer ticket.<p>

Order your festival tickets and camping together, by selecting <strong>Continue Shopping</strong> 
before you checkout!  Camping costs &pound;7.50pppn + booking fee in advance or &pound;8.50pppn on the gate.<p>

<a href=TermsAndConditions.php>Full Terms and Conditions</a>.<p>

<a href=/InfoCamping.php><b>Camping Information and Camping Tickets.</b></a><p>

<p><table cellspacing="5" cellpadding="5" style="background-color:#39a5d8; border-color:#39a5d8">
<tr>
<th colspan="5">Festival Passes</th>
</tr>
<tr>
<td><a href="https://www.ticketsource.co.uk/event/208030" rel="tag" target="_blank" style="font-size:18px"><strong>Weekend Pass</strong></a>
<br />Adult (13+): <strong>&pound;50.00</strong>
<br />Child (5-12): <strong>&pound;25.00</strong>
<br />Infant (0-4): <strong>Free</strong></td>
<td style="width:70%">Book your Weekend Pass for official festival events. <!--Events at the Tivoli are not covered by this pass.-->  See below for the events that it covers.<p>

Only this ticket includes entry to <a href="http://partyinthepaddock.com" rel="tag">Party In The Paddock</a>*<p>
<td style="text-align:center; font-size:20px"><a href="https://www.ticketsource.co.uk/event/208030" rel="tag" target="_blank"><strong>Buy Now</strong></a></td>
</tr>
<tr>
<td><a href="https://www.ticketsource.co.uk/event/210981" rel="tag" target="_blank" style="font-size:18px"><strong>Friday Pass</strong></a>
<br />Adult (13+): <strong>&pound;15.00</strong>
<br />Child (5-12): <strong>&pound;7.50</strong>
<br />Infant (0-4): <strong>Free</strong></td>
<td style="width:70%">Book your Friday Pass for official festival events. <!--Events at the Tivoli are not covered by this pass.-->  See below for the events that it covers.<p>

Does <b>NOT</b> Include entry to <a href="http://partyinthepaddock.com" rel="tag">Party In The Paddock</a>*<p>
</td>
<td style="text-align:center; font-size:20px"><a href="https://www.ticketsource.co.uk/event/210981" rel="tag" target="_blank"><strong>Buy Now</strong></a></td>
</tr>

<tr>
<td><a href="https://www.ticketsource.co.uk/event/211013" rel="tag" target="_blank" style="font-size:18px"><strong>Saturday Pass</strong></a>
<br />Adult (13+): <strong>&pound;30.00</strong>
<br />Child (5-12): <strong>&pound;15.00</strong>
<br />Infant (0-4): <strong>Free</strong></td>
<td style="width:70%">Book your Saturday Pass for official festival events. See below for the events that it covers.<p>

Does <b>NOT</b> Include entry to <a href="http://partyinthepaddock.com" rel="tag">Party In The Paddock</a>*<p>
</td>
<td style="text-align:center; font-size:20px"><a href="https://www.ticketsource.co.uk/event/211013" rel="tag" target="_blank"><strong>Buy Now</strong></a></td>
</tr>

<tr>
<td><a href="https://www.ticketsource.co.uk/event/211014" rel="tag" target="_blank" style="font-size:18px"><strong>Sunday Pass</strong></a>
<br />Adult (13+): <strong>&pound;20.00</strong>
<br />Child (5-12): <strong>&pound;10.00</strong>
<br />Infant (0-4): <strong>Free</strong></td>
<td style="width:70%">Book your Sunday Pass for official festival events. See below for the events that it covers.<p>

Does <b>NOT</b> Include entry to <a href="http://partyinthepaddock.com" rel="tag">Party In The Paddock</a>*<p>
</td>
<td style="text-align:center; font-size:20px"><a href="https://www.ticketsource.co.uk/event/211014" rel="tag" target="_blank"><strong>Buy Now</strong></a></td>
</tr>
</table>

<?php

  include_once "int/fest.php";
  include_once "int/ProgLib.php";
  global $YEAR,$db,$DayList,$MASTER;

  $Vens = Get_Venues(1);
  $qry = "SELECT * FROM Events WHERE Year='$YEAR' AND ((Price1!=0 AND TicketCode!='') OR SpecPriceLink!='')  AND SubEvent<=0 AND (Public=0 || Public=1) ORDER BY Day,Start";
  $Evs = $db->query($qry);

  while ($E = $Evs->fetch_assoc()) {
    DayTable($E['Day'],"Event Tickets",($MASTER['PriceComplete' . $E['Day']]?'':'(More to come)'));
    $bl = "<a href=" . ($E['SpecPriceLink']? $E['SpecPriceLink'] : ("https://www.ticketsource.co.uk/event/" . $E['TicketCode'])) . " target=_blank>" ;
    echo "<tr><td><strong><a href=/int/EventShow.php?e=" . $E['EventId'] . ">" . $E['SName'] . "</a></strong><br>"; 
      echo Price_Show($E);
    echo "<td>" . $DayList[$E['Day']] . " " . ($MASTER['DateFri']+$E['Day']) ."th June $YEAR" . "<br>";
      if ($E['Venue']) {
	echo "At: <a href=/int/VenueShow.php?v=" . $E['Venue'] . ">" . VenName($Vens[$E['Venue']]) . "</a><br>";
      } else {
	echo "At: </b>Venue not yet known</b><br>";
      }
      echo "From: " . timecolon($E['Start']) . " to " . timecolon($E['End']);
    echo "<td style='width:50%'>";
      if ($E['Description']) echo $E['Description'] . "<br>";
      echo Get_Event_Participants($E['EventId'],1,15);
    echo "<td>";
    if ($E['TicketCode'] || $E['SpecPriceLink']) echo "<strong>$bl Buy Now</a></strong>\n";
  }
?>

</table></p>

<h2 class="subtitle">Child Tickets</h2>

<p>Child ticket pricing for the festival is 0-4 Free, 5-12 Half Price, 13+ Standard ticket price.</p>

<h2 class="subtitle">Official Campsite</h2>
<p>
<a href=/InfoCamping.php><b>Camping Information.</b></a><p>

<p>Order your festival tickets and camping together, by selecting <strong>Continue Shopping</strong> before you checkout!</p> 

<span style="float:left;">*</span><h2 class="subtitle">Party In The Paddock</h2>

<p>If you're looking to combine a weekend of official festival events and a trip to <a href="http://partyinthepaddock.com" rel="tag">Party In The Paddock</a>, then book your tickets with us!</p>

<h2 class="subtitle">Official Ticket Outlets</h2>
<p>Event tickets and day/weekend passes are on sale at these Wimborne outlets, with zero booking fee*:</p>
<ul>
<li><strong>The Allendale Centre</strong>, Wimborne, BH21 1AS (but not the camping tickets)</li>
<li><strong>Tourist Information Centre</strong>, Wimborne, BH21 1HR &#8211; Telephone bookings: 01202 886116</li>
</ul>

<p>*subject to card transaction fees.</p>

</div>
<?php
  dotail();
?>
