<?php
  include_once("fest.php");
  A_Check('SysAdmin');
  global $EType_States,$TicketStates,$YEARDATA;

  dostaffhead("General Year Settings");

  include_once("DateTime.php");
  $Dates = array('PriceChange1','PriceChange2','TradeMainDate','TradeLastDate');
  $Years2Show = ['This','Both','Next'];

  echo "<div class='content'><h2>General Festival Year Settings</h2>\n";
  $Gens = Get_Years();
  
  function Put_General($now) {
    global $Gens;
    $y=$now['Year'];
    $Cur = $Gets[$y];
    Update_db('General',$Cur,$now);
  }

  if (isset($_REQUEST['ACTION'])) {
    switch ($_REQUEST['ACTION']) {
    case 'Update' :
      Parse_DateInputs($Dates);
      $Gen = $Gens[$_POST['Year']];
      Update_db_post('General',$Gen);
      $ynum = $Gen['Year'];
      break;
          
    case 'Create' :
      Parse_DateInputs($Dates);
      Insert_db_post('General',$Gen);
      $ynum = $Gen['Year'];
      break;    
    
    case 'Setup' :
      $Gen = [];
      $ynum = 0;
      break;
    }
  } else { // Display
    if (isset($_REQUEST['yearnum'])) {
      $ynum = $_REQUEST['yearnum'];
      if ($ynum && isset($Gens[$ynum])) {
        $Gen = $Gens[$ynum];
      } else {
        $Gen = $YEARDATA;
        $ynum = $Gen['Year'];
      }
    } else {
      $Gen = $YEARDATA;    
      $ynum = $Gen['Year'];
    }
  }
  
  
  echo "<form method=post>\n";

//  var_dump($Gens);

//  echo "<!-- " . var_dump($Gen) . " -->\n";
  echo "<div class=tablecont><table width=90% border>\n";
    echo "<tr><td>Id:<td>";
    if (isset($Gen['id'])) {
      echo $Gen['id'] . fm_hidden('id',$Gen['id']);
    } else {
      echo "Not Assigned";
    }
    echo "<tr>" . fm_text('Year',$Gen,'Year') . "<td colspan=2>For a second event in a year put something like 2020A or 2020.1  - max 10 chars";
// NOTE General contains LOTS of no longer used feilds - just ignore them
//    echo "<tr>" . fm_text('Version Number',$Gen,'Version') . "<td>Software Version Number - change will force css/js reload";
//    echo "<tr>" . fm_text('Prefix',$Gen,'Prefix') . "<td>Title prefix - used for testing only";
    echo "<tr><td>State of Family:<td>" . fm_select($EType_States,$Gen,'FamilyState') . "<td>Controls level of Participant interfaces";
    echo "<tr><td>State of Specials:<td>" . fm_select($EType_States,$Gen,'SpecialState') . "<td>";
    echo "<tr><td>State of Trade:<td>" . fm_select($EType_States,$Gen,'TradeState') . "<td>Affects the visibility of Pitches to traders";
    echo "<tr>" . fm_number1('Date of Friday',$Gen,'DateFri') . fm_number1('Month of Friday',$Gen,'MonthFri') . "<td>ie 8,6 for 8th of June.  It works out the rest from these\n";
    echo "<tr>" . fm_number1('First Day',$Gen,'FirstDay') . fm_number1('Last Day',$Gen,'LastDay') . 
         "<td colspan=2> Start -4 to 2 days before/after Friday, finish up to 10 days later - save changes after change\n";
    echo "<tr>" . fm_text('Trading Days',$Gen,'TradeDates') . "<td>Can be 0,1,2 or -1:3 or 1:2,8:9 etc (Not Used yet)";
    echo "<tr><td>Years to Show:<td>" . fm_select($Years2Show,$Gen,'Years2Show') . "<td>\n";
    echo "<tr>" . fm_date('Date of Price Change 1',$Gen,'PriceChange1') . "<td>\n";
    echo "<tr>" . fm_date('Date of Price Change 2',$Gen,'PriceChange2') . "<td>\n";
    if (isset($id)) for ($day=$Gen['FirstDay']; $day<=$Gen['LastDay']; $day++) {
      echo "<tr><td>Priced Complete " . FestDate($day,'s') . "<td>" . fm_checkbox('',$Gen,"PriceComplete" .($day>=0?$day:"_" . (-$day))) . 
           "<td>This and all completes surpress more to come on tickets/events\n";
    }
    echo "<tr>" . fm_date('Date Sending Main Trade Invoices',$Gen,'TradeMainDate') . "<td>\n";    
    echo "<tr>" . fm_date('Date Last Trade Payments',$Gen,'TradeLastDate') . "<td>\n";    

    echo "<tr>" . fm_text('Previous Festival',$Gen,'PrevFest') . "<td>Previous Festival in Calender Order (as in its Year see above)";
    echo "<tr>" . fm_text('Next Festival',$Gen,'NextFest') . "<td>Next Festival in Calender Order";
    echo "<tr>" . fm_textarea("Features",$Gen,'FestFeatures',4,4);
        
    echo "<tr><td>Ticket Control:<td>" . fm_select($TicketStates,$Gen,'TicketControl') . "<td>Master Ticketing control\n";
    echo "<tr><td>Camping Control:<td>" . fm_select($TicketStates,$Gen,'CampingControl') . "<td>Master Camping Ticket control\n";
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
    if (Feature('Camping')) {
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
    }


/*    
    echo "<tr>" . fm_text("Friday Pass",$Gen,'FridayPass');
    echo "<tr>" . fm_text("Friday Pass",$Gen,'FridayPass');
    echo "<tr>" . fm_text("Friday Pass",$Gen,'FridayPass');
    echo "<tr>" . fm_text("Friday Pass",$Gen,'FridayPass');    
   */ 
    
//    $comps = array('Ceildih','Session','Workshop','Concert','Family','Comedy','Special','Craft');

//    foreach($comps as $c) echo "<tr><td>$c Complete:" . fm_checkbox('',$Gen,$c . "Complete");
  echo "</table></div>\n";

  if ($ynum) {
    echo "<Center><input type=Submit name=ACTION value=Update>\n";
    echo "</center>\n";

    // Last Year, // Current // Next | Create
    echo "<p><h2>Settings for ";
    $Prev = $Gen['PrevFest'];
    if (isset($Gens[$Prev])) echo "<a href=YearData?yearnum=$Prev>$Prev</a>, ";
    echo $ynum;
    $Next = $Gen['NextFest'];
    if (isset($Gens[$Next])) echo ", <a href=YearData?yearnum=$Next>$Next</a> ";
    echo ", <input type=Submit name=ACTION value='Setup'</a>";

    echo "</h2>";
    
  } else { 
    echo "<Center><input type=Submit name=ACTION value=Create></center>\n";
    echo "</form>\n";
  }

  dotail();
?>
