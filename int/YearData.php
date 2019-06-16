<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("General Year Settings");

  global $EType_States,$TicketStates;
  include_once("DateTime.php");
  $Dates = array('PriceChange1','PriceChange2','TradeMainDate','TradeLastDate');
  $Years2Show = ['This','Both','Next'];

  echo "<div class='content'><h2>General Year Settings</h2>\n";
  
  function Put_General($now) {
    $y=$data['Year'];
    $Cur = Get_General($y);
    Update_db('General',$Cur,$now);
  }

  echo "<form method=post action='YearData.php'>\n";
  if (isset($_POST['Year'])) { /* Response to update button */
    $ynum = $_POST['Year'];
    Parse_DateInputs($Dates);
    if ($ynum > 0) {                                 // existing Year
      $Gen = Get_General($ynum);
      if (isset($_POST['ACTION'])) {
        switch ($_POST['ACTION']) {
        case 'New Year' :
          break;
        }
      } else {
//      var_dump($_POST);
        Update_db_post('General',$Gen);
      }
    } else { /* New Year */
      $_POST{'Year'} = $YEAR;
      $ynum = Insert_db_post('General',$Gen);
    }
  } elseif (isset($_GET{'yearnum'})) {
    $ynum = $_GET{'yearnum'};
    $Gen = Get_General($ynum);
  } elseif (isset($_GET{'Create'})) {
    global $db;
    $ynum = $_GET{'Create'};
    Parse_DateInputs($Dates);
    $db->query("INSERT INTO General SET Year=$ynum");
    $Gen = Get_General($ynum);
  } else {
    $Gen = Get_General();
    if ($Gen) $ynum = $Gen['Year'];
  }

  $Gens = Get_Years();
//  var_dump($Gens);

//  echo "<!-- " . var_dump($Gen) . " -->\n";
  echo "<div class=tablecont><table width=90% border>\n";
    echo "<tr><td>Year:<td>";
      if (isset($ynum) && $ynum > 0) {
        echo $ynum . fm_hidden('Year',$ynum);
      } else {
        echo $YEAR;
        echo fm_hidden('Year',-1);
      }
// NOTE General contains LOTS of no longer used feilds - just ignore them
//    echo "<tr>" . fm_text('Version Number',$Gen,'Version') . "<td>Software Version Number - change will force css/js reload";
//    echo "<tr>" . fm_text('Prefix',$Gen,'Prefix') . "<td>Title prefix - used for testing only";
    echo "<tr><td>State of Family:<td>" . fm_select($EType_States,$Gen,'FamilyState') . "<td>Controls level of Participant interfaces";
    echo "<tr><td>State of Specials:<td>" . fm_select($EType_States,$Gen,'SpecialState') . "<td>";
    echo "<tr><td>State of Trade:<td>" . fm_select($EType_States,$Gen,'TradeState') . "<td>Affects the visibility of Pitches to traders";
    echo "<tr>" . fm_number1('Date of Friday',$Gen,'DateFri') . fm_number1('Month of Friday',$Gen,'MonthFri') . "<td>ie 8,6 for 8th of June.  It works out the rest from these\n";
    echo "<tr>" . fm_number1('First Day',$Gen,'FirstDay') . fm_number1('Last Day',$Gen,'LastDay') . 
         "<td colspan=2> Start -4 to 2 days before/after Friday, finish up to 10 days later - save changes after change\n";
    echo "<tr><td>Years to Show:<td>" . fm_select($Years2Show,$Gen,'Years2Show') . "<td>\n";
    echo "<tr>" . fm_date('Date of Price Change 1',$Gen,'PriceChange1') . "<td>\n";
    echo "<tr>" . fm_date('Date of Price Change 2',$Gen,'PriceChange2') . "<td>\n";
    for ($day=$Gen['FirstDay']; $day<=$Gen['LastDay']; $day++) {
      echo "<tr><td>Priced Complete " . FestDate($day,'s') . "<td>" . fm_checkbox('',$Gen,"PriceComplete" .($day>=0?$day:"_" . (-$day))) . 
           "<td>This and all completes surpress more to come on tickets/events\n";
    }
    echo "<tr>" . fm_date('Date Sending Main Trade Invoices',$Gen,'TradeMainDate') . "<td>\n";    
    echo "<tr>" . fm_date('Date Last Trade Payments',$Gen,'TradeLastDate') . "<td>\n";    
    
    echo "<tr><td>Ticket Control:<td>" . fm_select($TicketStates,$Gen,'TicketControl') . "<td>Master Ticketing control\n";
    echo "<tr>" . fm_text1("Weekend Pass Code",$Gen,'WeekendPassCode') . fm_number1("Weekend Pass Price",$Gen,'WeekendPass') . 
                  fm_number1("Weekend Pass Price1",$Gen,'WeekendPass1') . fm_number1("Weekend Pass Price2",$Gen,'WeekendPass2');
    echo "<tr>" . fm_textarea("Text for Weekend Pass",$Gen,'WeekendText',6,2);
    echo "<tr>" . fm_text1("Friday Pass Code",$Gen,'FridayPassCode') . fm_number1("Friday Pass Price",$Gen,'FridayPass') . 
                  fm_number1("Friday Pass Price1",$Gen,'FridayPass1') . fm_number1("Friday Pass Price2",$Gen,'FridayPass2');
    echo "<tr>" . fm_textarea("Text for Friday Pass",$Gen,'FridayText',6,2);
    echo "<tr>" . fm_text1("Saturday Pass Code",$Gen,'SaturdayPassCode') . fm_number1("Saturday Pass Price",$Gen,'SaturdayPass') . 
                  fm_number1("Saturday Pass Price1",$Gen,'SaturdayPass1') . fm_number1("Saturday Pass Price2",$Gen,'SaturdayPass2');
    echo "<tr>" . fm_textarea("Text for Saturday Pass",$Gen,'SaturdayText',6,2);
    echo "<tr>" . fm_text1("Sunday Pass Code",$Gen,'SundayPassCode') . fm_number1("Sunday Pass Price",$Gen,'SundayPass') . 
                  fm_number1("Sunday Pass Price1",$Gen,'SundayPass1') . fm_number1("Sunday Pass Price2",$Gen,'SundayPass2');
    echo "<tr>" . fm_textarea("Text for Sunday Pass",$Gen,'SundayText',6,2);

    echo "<tr>" . fm_number1("Programme Book",$Gen,'ProgrammeBook');
    echo "<tr>" . fm_text1("Booking Fee",$Gen,'BookingFee');
    echo "<tr>" . fm_number1("Camping Cost",$Gen,'CampingCost') . "<td>This is the cost to us per night - not the public price";
    echo "<tr>" . fm_number1("Camping Gate Fee",$Gen,'CampingGateFee') . "<td>This is the cost at the gate per night";
    echo "<tr>" . fm_number1("Camping Fee 1 Night",$Gen,'CampingPrice1Day') . "<td>If any of these are zero, then it is not available";
    echo "<tr>" . fm_number1("Camping Fee 2 Nights",$Gen,'CampingPrice2Day');
    echo "<tr>" . fm_number1("Camping Fee 3 Nights",$Gen,'CampingPrice3Day');
    echo "<tr>" . fm_number1("Camping Fee 4 Nights",$Gen,'CampingPrice4Day'); 
    echo "<tr>" . fm_text("Camping Thurs-Sun Code",$Gen,'CampingCode_TFSS') . "<td>Put a - as the first char of the code to close just that";
    echo "<tr>" . fm_text("Camping Thurs-Sat Code",$Gen,'CampingCode_TFSx');
    echo "<tr>" . fm_text("Camping Thurs-Fri Code",$Gen,'CampingCode_TFxx');
    echo "<tr>" . fm_text("Camping Thurs Code",$Gen,'CampingCode_Txxx');
    echo "<tr>" . fm_text("Camping Fri Code",$Gen,'CampingCode_xFxx');
    echo "<tr>" . fm_text("Camping Fri-Sun Code",$Gen,'CampingCode_xFSS');
    echo "<tr>" . fm_text("Camping Fri-Sat Code",$Gen,'CampingCode_xFSx');
    echo "<tr>" . fm_text("Camping Sat Code",$Gen,'CampingCode_xxSx');
    echo "<tr>" . fm_text("Camping Sat-Sun Code",$Gen,'CampingCode_xxSS');
    echo "<tr>" . fm_text("Camping Sun Code",$Gen,'CampingCode_xxxS');


/*    
    echo "<tr>" . fm_text("Friday Pass",$Gen,'FridayPass');
    echo "<tr>" . fm_text("Friday Pass",$Gen,'FridayPass');
    echo "<tr>" . fm_text("Friday Pass",$Gen,'FridayPass');
    echo "<tr>" . fm_text("Friday Pass",$Gen,'FridayPass');    
   */ 
    
//    $comps = array('Ceildih','Session','Workshop','Concert','Family','Comedy','Special','Craft');

//    foreach($comps as $c) echo "<tr><td>$c Complete:" . fm_checkbox('',$Gen,$c . "Complete");
  echo "</table></div>\n";

  if ($ynum > 0) {
    echo "<Center><input type=Submit name='Update' value='Update'>\n";
    echo "</center>\n";

    // Last Year, // Current // Next | Create
    echo "<p><h2>Settings for ";
    if (isset($Gens[$ynum-1])) echo "<a href=YearData.php?yearnum=". ($ynum-1) . ">" . ($ynum-1) . "</a>, ";
    echo $ynum;
    if (isset($Gens[$ynum+1])) {
      echo ", <a href=YearData.php?yearnum=". ($ynum+1) . ">" . ($ynum+1) . "</a> ";
    } else {
      echo ", <a href=YearData.php?Create=". ($ynum+1) . ">Create " . ($ynum+1) . "</a> ";
    }
    echo "</h2>";
  } else { 
    echo "<Center><input type=Submit name=Create value='Create'></center>\n";
    echo "</form>\n";
  }

  dotail();
?>
