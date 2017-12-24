<?php
  include_once("int/fest.php");

  dohead("Tickets");

?>
<div class="biodiv">
<img src="/images/Mawkin-Accordion.jpg" alt="Wimborne Minster Folk Festival" class="bioimg" />
<p>Buy Tickets for Wimborne Minster Folk Festival 2018.</p>
</div>

<h2 class="maintitle">Buy Festival Tickets</h2>

<p>Select from the options below to purchase your tickets for Wimborne Minster Folk Festival 2018. Your ticket will grant you access to official festival concerts and ceilidhs listed below. During the festival weekend there are unofficial events in Wimborne that these tickets do not cover and we are unable to refund tickets bought in error.</p>

<p>Please note that there is a booking fee of &#163;1.00 per ticket when ordering tickets online. Please take care whilst ordering tickets as we are unable to process exchanges or refunds.</p>

<p>Due to the restricted size of some venues used during the festival, <strong>tickets <u>DO NOT</u> guarantee entry once capacity has been reached</strong>, so we advise arriving early for popular performances to avoid dissapointment. Full terms and conditions can be read prior to checkout.</p>

<p>Please <a href="mailto:music@wimbornefolk.co.uk">Contact Us</a> if you require a carer ticket.</p>

<p>Camping Tickets should be available soon.<p>

<p>Order your festival tickets and camping together, by selecting <strong>Continue Shopping</strong> before you checkout! Camping is available at <a href="http://merleyhallfarm.co.uk/camping" rel="tag"><strong>Merley Hall Farm</strong></a> for &#163;7.50pppn + booking fee in advance or &#163;8.50pppn on the gate.</p>


<p><table cellspacing="5" cellpadding="5" style="background-color:#59B404; border-color:#59B404;">
<tr>
<th colspan="5">Festival Passes</th>
</tr>
<tr>
<td><a href="https://www.ticketsource.co.uk/booking/event/208030" rel="tag" target="_parent" style="font-size:18px"><strong>Weekend Pass</strong></a>
<br />Adult (13+): <strong>&#163;50.00</strong>
<br />Child (5-12): <strong>&#163;25.00</strong>
<br />Infant (0-4): <strong>Free</strong></td>
<td style="width:70%">Book your Weekend Pass for official festival events. Event details will follow.<p>
There is a &pound;5 discount on the weekend pass with the code EARLYBIRD until 31st Jan 2018.<p>
Includes entry to <a href="http://partyinthepaddock.com" rel="tag">Party In The Paddock</a>*<p>
<td style="text-align:center; font-size:20px"><a href="https://www.ticketsource.co.uk/event/208030" rel="tag" target="_parent"><strong>Buy Now</strong></a></td>
</tr>
<tr>
<td><a href="https://www.ticketsource.co.uk/booking/event/210981" rel="tag" target="_parent" style="font-size:18px"><strong>Friday Pass</strong></a>
<br />Adult (13+): <strong>&#163;15.00</strong>
<br />Child (5-12): <strong>&#163;7.50</strong>
<br />Infant (0-4): <strong>Free</strong></td>
<td style="width:70%">Book your Friday Pass for official festival events. Event details will follow.

<br />
</td>
<td style="text-align:center; font-size:20px"><a href="https://www.ticketsource.co.uk/event/210981" rel="tag" target="_parent"><strong>Buy Now</strong></a></td>
</tr>

<tr>
<td><a href="https://www.ticketsource.co.uk/booking/event/211013" rel="tag" target="_parent" style="font-size:18px"><strong>Saturday Pass</strong></a>
<br />Adult (13+): <strong>&#163;30.00</strong>
<br />Child (5-12): <strong>&#163;15.00</strong>
<br />Infant (0-4): <strong>Free</strong></td>
<td style="width:70%">Book your Saturday Pass for official festival events. Event details will follow.
<br />
</td>
<td style="text-align:center; font-size:20px"><a href="https://www.ticketsource.co.uk/event/211013" rel="tag" target="_parent"><strong>Buy Now</strong></a></td>
</tr>

<tr>
<td><a href="https://www.ticketsource.co.uk/booking/event/211014" rel="tag" target="_parent" style="font-size:18px"><strong>Sunday Pass</strong></a>
<br />Adult (13+): <strong>&#163;20.00</strong>
<br />Child (5-12): <strong>&#163;10.00</strong>
<br />Infant (0-4): <strong>Free</strong></td>
<td style="width:70%">Book your Sunday Pass for official festival events. Event details will follow.
<br />

</td>
<td style="text-align:center; font-size:20px"><a href="https://www.ticketsource.co.uk/event/211014" rel="tag" target="_parent"><strong>Buy Now</strong></a></td>
</tr>
</table>

<p><table cellspacing="5" cellpadding="5" style="background-color:#59B404; border-color:#59B404;">
<?php
  echo "<th colspan=5>Event Tickets " . ($MASTER['PriceComplete']?'':'(More to come)') . "</th>";

  include_once "int/fest.php";
  include_once "int/ProgLib.php";
  global $YEAR,$db,$DayList,$MASTER;

  $Vens = Get_Venues(1);
  $qry = "SELECT * FROM Events WHERE Year='$YEAR' AND Price1!=0 AND TicketCode!='' AND SubEvent<=0 ORDER BY Day,Start";
  $Evs = $db->query($qry);

  while ($E = $Evs->fetch_assoc()) {
    
    $bl = "<a href=https://www.ticketsource.co.uk/event/" . $E['TicketCode'] . ">" ;
    echo "<tr><td><strong>$bl" . $E['Name'] . "</a></strong><br>"; // Change to link to event later
      echo Price_Show($E);
    echo "<td>" . $DayList[$E['Day']] . " " . ($MASTER['DateFri']+$E['Day']) ."th June $YEAR" . "<br>";
      echo "At: " . VenName($Vens[$E['Venue']]) . "<br>";
      echo "From: " . timecolon($E['Start']) . " to " . timecolon($E['End']);
    echo "<td style='width:50%'>";
      if ($E['Description']) echo $E['Description'] . "<br>";
      echo Get_Event_Participants($E['EventId'],1,15);
    echo "<td><strong>$bl Buy Now</a></strong>\n";
  }
?>

</table></p>

<h2 class="subtitle">Child Tickets</h2>

<p>Child ticket pricing for the festival is 0-4 Free, 5-12 Half Price, 13+ Standard ticket price.</p>

<h2 class="subtitle">Official Campsite</h2>

<p>If you are looking for somewhere to stay over the festival weekend, Meadows Campsite is a fantastic choice! It is our official campsite that is within a few minutes walk from the town centre, making it the perfect place to stay. Find out more info at <a href="http://merleyhallfarm.co.uk" rel="tag"><strong>MerleyHallFarm.co.uk</strong></a>.</p> 

<p>Order your festival tickets and camping together, by selecting <strong>Continue Shopping</strong> before you checkout!</p> 

<span style="float:left;">*</span><h2 class="subtitle">Party In The Paddock</h2>

<p>If you're looking to combine a weekend of official festival events and a trip to <a href="http://partyinthepaddock.com" rel="tag">Party In The Paddock</a>, then book your tickets with us!</p>

<h2 class="subtitle">Official Ticket Outlets</h2>
<p>Tickets are on sale at these Wimborne outlets, with zero booking fee*:</p>
<ul>
<li><strong>The Allendale Centre</strong>, Wimborne, BH21 1AS</li>
<li><strong>Tourist Information Centre</strong>, Wimborne, BH21 1HR &#8211; Telephone bookings: 01202 886116</li>
</ul>

<p>*subject to card transaction fees.</p>

</div>
<?php
  dotail();
?>
