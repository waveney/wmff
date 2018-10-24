<?php
  include_once("int/fest.php");

  dohead("Tickets");
  global $MASTER,$YEAR;
  set_ShowYear();
  include_once "int/ProgLib.php";
?>
<div class="biodiv">
<img src="http://wimbornefolk.co.uk/images/Mawkin-Accordion.jpg" alt="Wimborne Minster Folk Festival" class="bioimg" />
<p>Buy Tickets for Wimborne Minster Folk Festival 2018.</p>
</div>

<h2 class="maintitle">Buy Festival Tickets</h2>

Select from the options below to purchase your tickets for Wimborne Minster Folk Festival <?php echo $YEAR ?>.<p>
 
Your ticket will grant you access to official festival concerts and ceilidhs listed below.<p>

<?php
  if ($MASTER['BookingFee']) echo "Please note that there is a booking fee of " . $MASTER['BookingFee'] . " when ordering tickets online.<p> ";
?>
Please take care whilst ordering tickets as we are unable to process exchanges or refunds.<p>

Please <a href="mailto:carers@wimbornefolk.co.uk">Contact Us</a> if you require a carer ticket.<p>

<?php
  if ($MASTER['CampingCost']) {
    echo "Order your festival tickets and camping together, by selecting <strong>Continue Shopping</strong> ";
    echo "before you checkout!  Camping costs <strong>&pound;" . $MASTER['CampingPrice1Day'] . "</strong> for the first night and <strong>&pound;" .
     ($MASTER['CampingPrice2Day'] - $MASTER['CampingPrice1Day']) . "</strong> for each additional night.<p>";
  } else {
    echo "Camping is not yet open to book<p>";
  }
?>

<a href=TermsAndConditions.php>Full Terms and Conditions</a>.<p>

<a href=/InfoCamping.php><b>Camping Information and Camping Tickets.</b></a><p>

<p><table cellspacing="5" cellpadding="5" style="background-color:#39a5d8; border-color:#39a5d8">
<tr><th colspan="5">Festival Passes</th>
<?php
  foreach(['Weekend','Friday','Saturday','Sunday'] as $day) {
//  echo $day;
    if ($MASTER[$day . "PassCode"]) {
      echo "<tr><td>";
      if ($MASTER['TicketControl'] == 1) echo "<a href='https://www.ticketsource.co.uk/event/" . $MASTER[$day . "PassCode"] . "' target=_blank style='font-size:18px'>";
      echo "<strong>$day Pass</strong></a><br>";
      echo "Adult (13+): <strong>";
      
      $str = '';
      $Cpri = $MASTER[$day . 'Pass'];

      if ($MASTER['PriceChange1']) {
        $pc = $MASTER['PriceChange1'];
        $Npri = $MASTER[$day . 'Pass1'];
        if ($Npri != $Cpri && $Npri != 0) {
          if ($pc > time()) $str .= "&pound;" . $Cpri . "</strong> until " . date('j M Y',$pc);
          $Cpri = $Npri;
        }
      }
  
      if ($MASTER['PriceChange2']) {
        $pc = $MASTER['PriceChange2'];
        $Npri = $MASTER[$day . 'Pass2'];
        if ($Npri != $Cpri && $Npri != 0) {
          if ($pc > time()) {
            if ($str) $str .= ", then ";
            $str .= "&pound;" . $Cpri . "</strong> until " . date('j M Y',$pc);
          }
          $Cpri = $Npri;
        }
      }

      if ($str) $str .= ", then ";
      $str .= "&pound;$Cpri </strong><br>"; 

      echo $str;
      echo "Child (5-12): <strong>" . Print_Pence($Cpri*50) . "</strong><br>";
      echo "Infant (0-4): <strong>Free</strong>";
      echo "<td style='width:70%'>" . $MASTER[$day . "Text"] . "<td style='text-align:center; font-size:20px'>";

      switch ($MASTER['TicketControl']) {
      case 0: echo "Not Yet"; break;
      case 1: echo "<a href='https://www.ticketsource.co.uk/event/" . $MASTER[$day . "PassCode"] . "' target=_blank ><strong>Buy Now</strong></a>"; break;
      case 2: echo "Closed"; break;
      }
    }
  }
  echo "</table><p>";

  global $YEAR,$db,$DayList,$MASTER;

  $Vens = Get_Venues(1);
  $qry = "SELECT * FROM Events WHERE Year='$YEAR' AND ((Price1!=0 AND TicketCode!='') OR SpecPriceLink!='')  AND SubEvent<=0 AND (Public=0 || Public=1) ORDER BY Day,Start";
  $Evs = $db->query($qry);

  while ($E = $Evs->fetch_assoc()) {
    DayTable($E['Day'],"Event Tickets",($MASTER['PriceComplete' . $E['Day']]?'':'(More to come)'));
    $bl = "<a href=" . ($E['SpecPriceLink']? $E['SpecPriceLink'] : ("https://www.ticketsource.co.uk/event/" . $E['TicketCode'])) . " target=_blank>" ;
    echo "<tr><td><strong><a href=/int/EventShow.php?e=" . $E['EventId'] . ">" . $E['SN'] . "</a></strong><br>"; 
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
      echo Get_Event_Participants($E['EventId'],0,1,15);
    if (($MASTER['TicketControl'] == 1) && ($E['TicketCode'] || $E['SpecPriceLink'])) echo "<td><strong>$bl Buy Now</a></strong>\n";
  }
  
  if (!$Evs->num_rows) echo "No Ticketed Events are yet published.<p>";
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
<p>Event tickets and day/weekend passes are on sale at these Wimborne outlets:</p>
<ul>
<li><strong>Tourist Information Centre</strong>, Wimborne, BH21 1HR &#8211; Telephone bookings: 01202 886116</li>
</ul>


</div>
<?php
  dotail();
?>
