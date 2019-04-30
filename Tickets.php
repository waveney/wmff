<?php
  include_once("int/fest.php");

  dohead("Tickets");
  global $MASTER,$YEAR;
  set_ShowYear();
  include_once "int/ProgLib.php";
?>
<div class="biodiv">
<img src="https://wimbornefolk.co.uk/images/Weekend-Wristband.jpg" alt="Wimborne Minster Folk Festival" class="bioimg" />
<p>Buy Tickets for Wimborne Minster Folk Festival</p>
</div>

<h2 class="maintitle">Buy Festival Tickets</h2>

Select from the options below to purchase your passes and tickets for Wimborne Minster Folk Festival <?php echo $YEAR ?>.<p>
 
Your passes will grant you access to official festival concerts and ceilidhs listed below (excluding anything at the Tivoli).<p>

<?php
  if ($MASTER['BookingFee']) echo "Please note that there is a booking fee of " . $MASTER['BookingFee'] . " when ordering tickets online.<p> ";
?>

Please <a href="mailto:carers@wimbornefolk.co.uk">Contact Us</a> if you require a carer ticket.<p>

<?php
  if ($MASTER['CampingCost']) {
    echo "Order your festival tickets and camping together, by selecting <strong>Continue Shopping</strong> ";
    echo "before you checkout!<p>  Camping costs <strong>&pound;" . $MASTER['CampingPrice1Day'] . "</strong> for the first night and <strong>&pound;" .
     ($MASTER['CampingPrice2Day'] - $MASTER['CampingPrice1Day']) . "</strong> for each additional night.<p>";
  } else {
    echo "Camping is not yet open to book<p>";
  }
?>

<a href=TermsAndConditions.php>Full Terms and Conditions</a>.<p>

<a href=/InfoCamping.php><b>Camping Information and Camping Tickets.</b></a><p>

<p><table cellspacing="5" cellpadding="5" style="background-color:#39a5d8; border-color:#39a5d8; max-width:1200">
<tr><th colspan="5">Festival Passes</th>
<?php
  foreach(['Weekend','Friday','Saturday','Sunday'] as $day) {
//  echo $day;
    if ($MASTER[$day . "PassCode"]) {
      echo "<tr><td>";
      if ($MASTER['TicketControl'] == 1) echo "<a href='https://www.ticketsource.co.uk/date/" . $MASTER[$day . "PassCode"] . "' target=_blank style='font-size:18px'>";
      echo "<strong>$day Pass</strong></a><br>";
      echo "Adult (16+): <strong>";
      
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
      echo "Child (5-15): <strong>" . Print_Pence($Cpri*50) . "</strong><br>";
      echo "Infant (0-4): <strong>Free</strong>";
      echo "<td style='width:70%'>" . $MASTER[$day . "Text"] . "<td style='text-align:center; font-size:20px'>";

      switch ($MASTER['TicketControl']) {
      case 0: echo "Not Yet"; break;
      case 1: echo "<a href='https://www.ticketsource.co.uk/date/" . $MASTER[$day . "PassCode"] . "' target=_blank ><strong>Buy Now</strong></a>"; break;
      case 2: echo "Closed"; break;
      }
    }
  }
  echo "</table><p>";

  global $YEAR,$db,$DayList,$MASTER;

  echo "<div class='FullWidth TicketTable'>";
  $Vens = Get_Venues(1);
  $qry = "SELECT * FROM Events WHERE Year='$YEAR' AND ((Price1!=0 AND TicketCode!='') OR SpecPriceLink!='')  AND SubEvent<=0 AND (Public=0 || Public=1) ORDER BY Day,Start";
  $Evs = $db->query($qry);

  while ($E = $Evs->fetch_assoc()) {
    DayTable($E['Day'],"Event Tickets",($MASTER['PriceComplete' . ($E['Day'] >=0?$E['Day']:"_" . (-$E['Day'])) ]?'':'(More to come)'));
    $bl = "<a href=" . ($E['SpecPriceLink']? $E['SpecPriceLink'] : ("https://www.ticketsource.co.uk/date/" . $E['TicketCode'])) . " target=_blank>" ;
    echo "<tr><td><strong><a href=/int/EventShow.php?e=" . $E['EventId'] . ">" . $E['SN'] . "</a></strong><br>"; 
      echo Price_Show($E);
    echo "<td>" . FestDate($E['Day'],'L') . "<br>";
      if ($E['Venue']) {
        echo "At: <a href=/int/VenueShow.php?v=" . $E['Venue'] . ">" . VenName($Vens[$E['Venue']]) . "</a><br>";
      } else {
        echo "At: </b>Venue not yet known</b><br>";
      }
      echo "From: " . timecolon($E['Start']) . " to " . timecolon($E['End']);
    echo "<td style='width:50%'>";
      if ($E['Description']) echo $E['Description'] . "<br>";
      if ($E['BigEvent']) {
        $Others = Get_Other_Things_For($E['EventId']);
        echo Get_Other_Participants($Others,0,1,15,1,'',$E);
      } else {
        echo Get_Event_Participants($E['EventId'],0,1,15);
      }
      if ($E['ExcludePass']) {
        echo "<p><b>Note:</b> This is event excluded from the Weekend Pass ";
        if ($E['ExcludeDay'] && $MASTER[$DayLongList[$E['Day']] . "Pass"]!='') echo " or " . $DayLongList[$Ev['Day']] . " ticket\n";
      } elseif ($E['ExcludeDay'] && $MASTER[$DayLongList[$E['Day']] . "Pass"]!='') {
        echo "<p><b>Note:</b> This is event excluded from the " . $DayLongList[$E['Day']] . " ticket\n";
      } 
    if (($MASTER['TicketControl'] == 1) && ($E['TicketCode'] || $E['SpecPriceLink'])) echo "<td><strong>$bl Buy Now</a></strong>\n";
  }
  
  if (!$Evs->num_rows) echo "No Ticketed Events are yet published.<p>";

  echo "</table></div></p>";

  echo "<table class=GreenTable>";
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
     if (!$MASTER['CampingCode_' . $dat[0] ]) continue;
     echo "<tr><td>Camping for:";
     foreach (str_split($dat[0]) as $i=>$c) echo "<td>" . ($c == 'x'?"":$DName[$i]);
     echo "<td>" . Print_Pence($MASTER['CampingPrice' . $dat[1] . 'Day']*100) . "<td>";
     echo "<a href='https://www.ticketsource.co.uk/date/" . $MASTER['CampingCode_' . $dat[0] ] . "' target=_blank><b>Buy Now</b></a>";
   }
?>
</table><p>

<h2 class="subtitle">Child Tickets</h2>

<p>Child ticket pricing for the festival is 0-4 Free, 5-15 Half Price, 16+ Standard ticket price.</p>

<h2 class="subtitle">Official Campsite</h2>
<p>
<a href=/InfoCamping.php><b>Camping Information.</b></a><p>

<p>Order your festival tickets and camping together, by selecting <strong>Continue Shopping</strong> before you checkout!</p> 

<span style="float:left;">*</span><h2 class="subtitle">Party In The Paddock</h2>

<p>If you're looking to combine a weekend of official festival events and a trip to <a href="http://partyinthepaddock.com" rel="tag">Party In The Paddock</a>, then book your tickets with us!</p>

<h2 class="subtitle">Official Ticket Outlets</h2>
<p>Tickets and day/weekend passes are on sale at these Wimborne outlets:</p>
<ul>
<li><strong>Tourist Information Centre</strong>, Wimborne, BH21 1HR &#8211; Telephone bookings: 01202 886116</li>
</ul>


</div>
<?php
  dotail();
?>
