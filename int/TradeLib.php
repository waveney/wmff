<?php

// For Book -> Confirm -> Deposit ->Pay , if class begins with a - then not used/don't list
$Trade_States = array('Not Submitted','Declined','Refunded','Cancelled','Submitted','Quoted','Accepted','Deposit Paid','Balance Requested','Fully Paid','Wait List','Requote');
$Trade_State = array_flip($Trade_States);
//$Trade_StateClasses = array('TSNotSub','TSDecline','-TSRefunded','TSCancel','TSSubmit','TSInvite','TSConf','TSDeposit','TSInvoice','TSPaid','TSWaitList','TSRequote');
$Trade_State_Colours = ['white','red','-grey','grey','yellow','lightyellow','cyan','lightblue','darkseagreen','LightGreen','#ffb380','#e6d9b2'];

$TS_Actions = array('Submit,Invite,Invite Better',
                'Resend,Submit',
                'Resend',
                'Resend,Submit',
                'Resend,Quote,Accept,Invite,Decline,Hold,Cancel,Invite Better',
                'Resend,Quote,Invite,Accept,Decline,UnQuote,LastWeek',
                'Resend,Cancel',
                'Pitch,Moved,Resend,Send Bal,Cancel',
                'Pitch,Moved,Resend,Chase,Cancel',
                'Pitch,Moved,Resend,Cancel',
                'Resend,Accept,Decline,Cancel',
                'Resend,Quote,Cancel');

$Trader_Status = array('Alive','Banned','Not trading');
$Trader_State = array_flip($Trader_Status);
$ButExtra = array(
        'Accept'=>'',
        'Decline'=>'title="Decline this trader, if in doubt Hold Them"',
        'Submit'=>'title="Submit application"',
        'Hold'=>'title="Hold for space available"',
        'Dep Paid'=>'title="Deposit Paid"', // Not Used 
        'Send Bal'=>'title="Send Balance Request"',
        'Paid'=>'title=Full Fees Paid"', // Not Used
        'Quote'=>'title="Send or repeat Quote email"',
        'Invite'=>'title="Send or repeat the Invitation Email"',
        'Balance Requested'=>'title="Final Invoice Sent"',
        'Cancel'=>'onClick="javascript:return confirm(\'are you sure you want to cancel this?\');"',
        'Resend'=>'title="Resend last email to trader"',
        'Invite Better'=>'title="Send an Invitation to a better location"',
        'Artisan Invite'=>'title="Send an Artisan Invite"',
        'UnQuote'=>'title="Remove Quote or Invitation"',
        'Chase'=>'title="Chase email for final payment"',
        'Pitch'=>'title="Change of Pitch Number"',
        'Moved'=>'title="Pitch Moved"',
        'Balance'=>'title="Send Balance Payment Request',
        'LastWeek'=>'title="Last week of Quote"',
        ); 
$ButTrader = array('Submit','Accept','Decline','Cancel','Resend'); // Actions Traders can do
$ButAdmin = array('Paid','Dep Paid');
$RestrictButs = array('Paid','Dep Paid'); // If !AutoInvoice or SysAdmin
$Trade_Days = array('Both','Saturday only','Sunday only');
$Prefixes = array ('in','in the','by the');
$TaxiAuthorities = array('East Dorset','Poole','Bournemouth');
$TradeMapPoints = ['Trade','Other'];

function Get_Trade_Locs($tup=0,$Cond='') { // 0 just names, 1 all data
  global $db;
  $full = $short = [];
  $res = $db->query("SELECT * FROM TradeLocs $Cond ORDER BY SN ");
  if ($res) {
    while ($typ = $res->fetch_assoc()) {
      $short[$typ['TLocId']] = $typ['SN'];
      $full[$typ['TLocId']] = $typ;
    }
  }
  if ($tup) return $full;
  return $short;
}

function Get_Trade_Loc($id) {
  global $db;
  $res=$db->query("SELECT * FROM TradeLocs WHERE TLocId=$id");
  if ($res) {
    $ans = $res->fetch_assoc();
    return $ans;
  }
  return 0; 
}

function Put_Trade_Loc(&$now) {
  $e=$now['TLocId'];
  $Cur = Get_Trade_Loc($e);
  return Update_db('TradeLocs',$Cur,$now);
}

function Get_Trade_Pitches($loc='',$Year=0) {
  global $db,$YEAR;
  if ($Year == 0) $Year=$YEAR;
  $full = [];
//  var_dump("SELECT * FROM TradePitch " . ($loc?"WHERE Loc=$loc ":"") . " AND Year=$Year ORDER BY Posn ");
  
  $res = $db->query("SELECT * FROM TradePitch " . ($loc?"WHERE Loc=$loc ":"") . " AND Year=$Year ORDER BY Posn ");
  if ($res) {
    while ($ptch = $res->fetch_assoc()) {
      $full[$ptch['Posn']] = $ptch;
    }
  }
  return $full;
}

function Get_Trade_Pitch($id) {
  global $db;
  $res = $db->query("SELECT * FROM TradePitch WHERE id=$id");
  if ($res) return $res->fetch_assoc();
  return [];
}

function Put_Trade_Pitch(&$now) {
  $e=$now['id'];
  $Cur = Get_Trade_Pitch($e);
  return Update_db('TradePitch',$Cur,$now);
}



function Get_Trade_Types($tup=0) { // 0 just base names, 1 all data
  global $db,$PLANYEAR;
  $full = array();
  if ($tup) {
    $res = $db->query("SELECT * FROM TradePrices ORDER BY ListOrder");
    if ($res) while ($tt = $res->fetch_assoc()) $full[$tt['id']] = $tt;
  } else {
    $res = $db->query("SELECT * FROM TradePrices WHERE Addition=0 ORDER BY ListOrder");
    if ($res) while ($tt = $res->fetch_assoc()) $full[$tt['id']] = $tt['SN'];
  }
  return $full;
}

$TradeTypeData = Get_Trade_Types(1);
$TradeLocData = Get_Trade_Locs(1);

function Get_Trade_Type($id) {
  global $db;
  $res=$db->query("SELECT * FROM TradePrices WHERE id=$id");
  if ($res) return $res->fetch_assoc();
  return 0; 
}

function Put_Trade_Type(&$now) {
  $e=$now['id'];
  $Cur = Get_Trade_Type($e);
  return Update_db('TradePrices',$Cur,$now);
}

function Get_Sponsors($tup=0) { // 0 Current, 1 all data
  global $db,$SHOWYEAR;
  $full = array();
  $yr = ($tup ?"" :" WHERE Year=$SHOWYEAR ");
  $res = $db->query("SELECT * FROM Sponsors $yr ORDER BY SN ");
  if ($res) while ($spon = $res->fetch_assoc()) $full[] = $spon;
  if ($tup==0 && empty($full)) {
    $yr = " WHERE Year=" . ($SHOWYEAR-1);
    $res = $db->query("SELECT * FROM Sponsors $yr ORDER BY SN ");
    if ($res) while ($spon = $res->fetch_assoc()) $full[] = $spon;
  }
  return $full;
}

function Get_Sponsor_Names() {
  $data = Get_Sponsors();
  foreach ($data as $i=>$sp) $ans[$sp['id']]=$sp['SN'];
  return $ans;
}

function Get_Sponsor($id) {
  global $db;
  $res=$db->query("SELECT * FROM Sponsors WHERE id=$id");
  if ($res) return $res->fetch_assoc();
  return 0; 
}

function Put_Sponsor(&$now) {
  $e=$now['id'];
  $Cur = Get_Sponsor($e);
  return Update_db('Sponsors',$Cur,$now);
}

function Get_WaterRefills($tup=0) { // 0 Current, 1 all data
  global $db,$PLANYEAR;
  $full = array();
  $yr = ($tup ?"" :" WHERE Year=$PLANYEAR ");
  $res = $db->query("SELECT * FROM Water $yr ORDER BY SN ");
  if ($res) while ($spon = $res->fetch_assoc()) $full[] = $spon;
  if ($tup==0 && empty($full)) {
    $yr = " WHERE Year=" . ($PLANYEAR-1);
    $res = $db->query("SELECT * FROM Water $yr ORDER BY SN ");
    if ($res) while ($spon = $res->fetch_assoc()) $full[] = $spon;
  }
  return $full;
}

function Get_WaterRefill($id) {
  global $db;
  $res=$db->query("SELECT * FROM Water WHERE id=$id");
  if ($res) return $res->fetch_assoc();
  return 0; 
}

function Put_WaterRefill(&$now) {
  $e=$now['id'];
  $Cur = Get_WaterRefill($e);
  return Update_db('Water',$Cur,$now);
}

function Get_Trader($who) {
  global $db;
  $res = $db->query("SELECT * FROM Trade WHERE Tid='$who'");
  if (!$res || $res->num_rows == 0) return 0;
  $data = $res->fetch_assoc();
  return $data;
}

function Get_TraderByName($who) {
  global $db;
  $res = $db->query("SELECT * FROM Trade WHERE SN LIKE '$who'");
  if (!$res || $res->num_rows == 0) return 0;
  $data = $res->fetch_assoc();
  return $data;
}

function Get_Traders_Coming($type=0,$SortBy='SN') { // 0=names, 1=all
  global $db,$YEAR,$Trade_State;
  $data = array();
  $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Tid = y.Tid AND y.Year=$YEAR AND y.BookingState>=" . $Trade_State['Deposit Paid'] .
                " ORDER BY $SortBy";
  $res = $db->query($qry);
  if (!$res || $res->num_rows == 0) return 0;
  while ($tr=$res->fetch_assoc()) {
    $data[$tr['Tid']] = ($type?$tr:$tr['SN']);
  }
  return $data;
}

function Get_All_Traders($type=0) { // 0=names, 1=all
  global $db,$YEAR,$Trade_State;
  $data = array();
  $qry = "SELECT * FROM Trade WHERE Status=0 AND IsTrader=1 ORDER BY SN";
  $res = $db->query($qry);
  if (!$res || $res->num_rows == 0) return 0;
  while ($tr=$res->fetch_assoc()) {
    $data[$tr['Tid']] = ($type?$tr:$tr['SN']);
  }
  return $data;
}

function Get_All_Businesses($type=0) { // 0=names, 1=all
  global $db,$YEAR,$Trade_State;
  $data = array();
  $qry = "SELECT * FROM Trade WHERE Status=0 AND IsTrader=0 ORDER BY SN";
  $res = $db->query($qry);
  if (!$res || $res->num_rows == 0) return 0;
  while ($tr=$res->fetch_assoc()) {
    $data[$tr['Tid']] = ($type?$tr:$tr['SN']);
  }
  return $data;
}


function Put_Trader(&$now) {
//  debug_print_backtrace();
  $e=$now['Tid'];
  $Cur = Get_Trader($e);
  if ($Cur) return Update_db('Trade',$Cur,$now);
}

function Get_Trade_Years($Tid) {
  global $db;
  $Years = array();
  $res = $db->query("SELECT * FROM TradeYear WHERE Tid='$Tid'");
  if (!$res) return 0;
  while ($yr = $res->fetch_assoc()) {
    $y = $yr['Year'];
    $Years[$y] = $yr;
  }
  return $Years;
}

function Get_Trade_Year($Tid,$year=0) {
  global $db,$YEAR;
  if (!$year) $year=$YEAR;
  $qry = "SELECT * FROM TradeYear WHERE Tid='" . $Tid . "' AND Year='" . $year . "'";
  $res = $db->query($qry);
  if (!$res || $res->num_rows == 0) return 0;
  return $res->fetch_assoc();
}

function Put_Trade_Year(&$now) {
  $e=$now['Tid'];
  $Cur = Get_Trade_Year($e,$now['Year']);
  if ($Cur) return Update_db('TradeYear',$Cur,$now);
  Insert_db('TradeYear',$now);
}

function Set_Trade_Help() {
  static $t = array(
        'Website'=>'If you would like to be listed on the Folk Festival Website, please supply your website (if you have one) and an Image and tick the box (Note traders will appear on the public website shortly)',
        'GoodsDesc'=>'Describe your goods and buisness.  At least 20 words please.  This is used both to decide whether to accept your booking and as words to accompany your Image on the festival website',
        'PitchSize'=>'If you want more than 1 pitch, give each pitch size, a deposit will be required for each.  If you attempt to setup a pitch larger than booked you may be told to leave',
        'Power'=>'Some locations can provide power, some only support lower power requirements. 
There will be an additional fee for power from &pound;10-20, that will be added to your final invoice.
Any generator must meet the Euro 4 silent generator standard.',
        'Photo'=>'Give URL of Image to use or upload one (landscape is prefered)',
        'TradeType'=>'Fees depend on trade type, pitch size and location',
//        'BookingState'=>'ONLY change this if you are fixing a problem, use the state change buttons',
        'PublicInfo'=>'Information in this section may be used on the public website', 
        'PrivateInfo'=>'Information in this section is only visible to you and the revelent members of the festival, you can amend this at any time',
        'PublicHealth'=>'Please give the NAME of the local authority your registered with',
        'IsTrader'=>'Used to indicate the business is a trader (useful for finance) do not touch (normally)',

  );
  Set_Help_Table($t);
}

function Pitch_Size_Def($type) {
  global $YEAR,$TradeTypeData;
  $DefPtch = (isset($TradeTypeData[$type]['DefaultSize'])?$TradeTypeData[$type]['DefaultSize']:'');
  if (!$DefPtch) $DefPtch = Feature('DefaultPitch','3Mx3M');
  return $DefPtch;
}

function Default_Trade($id,$type=1) {
  global $YEAR;
  return array('Year'=>$YEAR,'Tid'=>$id,'PitchSize0'=>Pitch_Size_Def($type),'Power0'=>0,'BookingState'=>0);
}

// OLD CODE DELETE
function PayCodeGen($Type,$TYid) { // Type = DEP, BAL, PAY
  $digits = (string)($TYid*123) . "000000000000";
  // 1. Add the values of the digits in the even-numbered positions: 2, 4, 6, etc.
  $even_sum = ord($Type[0]) + ord($Type[2]) + $digits{1} + $digits{3} + $digits{5} + $digits{7} + $digits{9} + $digits{11};
  // 2. Multiply this result by 3.
  $even_sum_three = $even_sum * 3;
  // 3. Add the values of the digits in the odd-numbered positions: 1, 3, 5, etc.
  $odd_sum = ord($Type[1]) + $digits{0} + $digits{2} + $digits{4} + $digits{6} + $digits{8} + $digits{10};
  // 4. Sum the results of steps 2 and 3.
  $total_sum = $even_sum_three + $odd_sum;

  $check_digit = chr(($total_sum%26) + ord('A'));
  return "$Type$TYid$check_digit";
}

function Show_Trader($Tid,&$Trad,$Form='Trade',$Mode=0) { // Mode 1 = Ctte, 2=Finance
  global $YEAR,$ADDALL,$Mess,$Action,$Trader_Status,$TradeTypeData,$TradeLocData;
  Set_Trade_Help();

//  if (isset($Trad['Photo']) && $Trad['Photo']) echo "<img class=floatright id=TradThumb src=" . $Trad['Photo'] . " height=80>\n";
  if ($Tid > 0) echo "<input  class=floatright type=Submit name='Update' value='Save Changes' form=mainform>";
  if ($Mode && isset($Trad['Email']) && strlen($Trad['Email']) > 5) {
    echo "If you click on the " . linkemailhtml($Trad,'Email');
    echo ", press control-V afterwards to paste the <button type=button onclick=Copy2Div('Email$Tid','SideLink$Tid')>standard link</button>";
    echo "<p>\n";
  }

  $Adv = '';
  $Imp = '';
  if ($Mode ==1) {
    echo "<span class=NotSide>Fields marked are not visible to Trader.</span>";
    echo "  <span class=NotCSide>Marked are visible if set, but not changeable by Trader.</span>";
  } else {
    $Adv = 'class=Adv';
  }
  echo "<div id=ErrorMessage class=ERR></div>";
//********* PUBLIC

  if (!isset($Trad['TradeType']) || ($Trad['TradeType'] == 0)) $Trad['TradeType'] = 1;

  echo "<form method=post id=mainform enctype='multipart/form-data' action=$Form>";
  Register_AutoUpdate('Trader',$Tid);
  if (isset($_REQUEST['ORGS'])) echo fm_hidden('ORGS',1);
  echo "<div class=tablecont><table width=90% border class=SideTable>\n";
    echo "<tr><th colspan=8><b>Public Information</b>" . Help('PublicInfo');
    echo "<tr>" . fm_text('Business Name', $Trad,'SN',2,'','autocomplete=off id=SN');
    echo "<tr>";
      if (isset($Trad['Website']) && strlen($Trad['Website'])>1) {
        echo fm_text(weblink($Trad['Website']),$Trad,'Website');
      } else {
        echo fm_text('Website',$Trad,'Website');
      };
      if ($Tid >0) {
        echo "<td>Recent Photo:" . fm_DragonDrop(1, 'Photo','Trade',$Tid,$Trad,$Mode); // TODO  <td><a href=PhotoProcess.php?Cat=Perf&id=$snum>Edit/Change</a>";
      } else {
        echo "<td colspan=3>You can upload a photo once you have created your record\n";
      }
    if ($Mode != 2) echo "<tr>" . fm_textarea('Products Sold <span id=DescSize></span>',$Trad,'GoodsDesc',7,2,
                        'maxlength=500 oninput=SetDSize("DescSize",500,"GoodsDesc")');     

//********* PRIVATE

    echo "<tr><th colspan=8><b>Private Information</b>" . Help('PrivateInfo');
    if ($Mode < 2) {
      echo "<tr>";
        echo "<td>Trade Type:" . help('TradeType') . "<td colspan=7>";
        foreach ($TradeTypeData as $i=>$d) {
          if ($d['Addition']) continue;
          echo " <div class=KeepTogether style='background:" . $d['Colour'] . ";'>" . $d['SN'] . ": ";
          echo " <input type=radio name=TradeType $ADDALL value=$i ";
          if ($Trad['TradeType'] == $i) echo " checked";
          echo " onclick='SetTradeType(" . $d['NeedPublicHealth'] . "," . $d['NeedCharityNum'] . "," .
                                          $d['NeedInsurance'] . "," . $d['NeedRiskAssess'] . ',"' . $d['Description'] . '","' . 
                                          $d['Colour'] . '","' . Pitch_Size_Def($i) . '")\''; // not fm-Radio because of this line
          echo " id=TradeType$i oninput=AutoRadioInput('TradeType',$i) ";
          echo ">&nbsp;</div>\n ";
        }
        echo "<br clear=all><div id=TTDescription style='background:" . $TradeTypeData[$Trad['TradeType']]['Colour'] . ";'>" . 
          $TradeTypeData[$Trad['TradeType']]['Description'] . "</div>\n";
    } else {
      fm_hidden('TradType',$Trad['TradeType']);
    }
    echo "<tr>" . fm_text('<span id=ContactLabel>Contact Name</span>',$Trad,'Contact');
      echo fm_text1('Email',$Trad,'Email',2);
      echo fm_text('Phone',$Trad,'Phone');
      echo fm_text('Mobile',$Trad,'Mobile',1,$Imp,'onchange=updateimps()') . "\n";
    echo "<tr>" . fm_text('Address',$Trad,'Address',5,$Imp,'onchange=updateimps()');
      echo fm_text('Post Code',$Trad,'PostCode')."\n";
    if ($Mode < 2) {
      echo "<tr class=PublicHealth " . ($TradeTypeData[$Trad['TradeType']]['NeedPublicHealth']?'':'hidden') . ">" ;
        echo fm_text("Registered with which Local Authority ",$Trad,'PublicHealth',2,'colspan=2');
      echo "<tr><td>Are you a <td>" . fm_checkbox('BID Levy Payer',$Trad,'BID') . "<td>" . fm_checkbox('Chamber of Commerce Member',$Trad,'ChamberTrade');
      if ($Mode) echo "<td>" . fm_checkbox('Previous Festival Trader',$Trad,'Previous');
        echo fm_text('Charity Number',$Trad,'Charity',1,'class=Charity ' . ($TradeTypeData[$Trad['TradeType']]['NeedCharityNum']?'':'hidden'));
        if ($Mode) echo "<td class=NotSide colspan=2>" . fm_radio("",$Trader_Status,$Trad,'Status','',0);
      }
    if (Access('SysAdmin') && isset($Trad['AccessKey'])) {
      echo "<tr>";
        if ($Tid > 0) echo "<td class=NotSide>Id: $Tid";
        echo fm_nontext('Access Key',$Trad,'AccessKey',3,'class=NotSide','class=NotSide'); 
        if (isset($Trad['AccessKey'])) {
          echo "<td class=NotSide><a href=Direct?id=$Tid&t=trade&key=" . $Trad['AccessKey'] . ">Use</a>" . help('Testing');
        }
      echo "  <td class=NotSide><button name=Action value=Delete onClick=\"javascript:return confirm('are you sure you want to delete this?');\">Delete</button>\n";
    }
    if ($Mode && Capability("EnableFinance")) {
      echo "<tr><td class=NotSide>" . fm_checkbox("Is a Trader",$Trad,'IsTrader');
    } else { 
      echo fm_hidden('IsTrader',$Trad['IsTrader']);
    }
    if ($Tid > 0 && Access('Committee',"Finance")) {
      include_once("InvoiceLib.php");
      if (isset($Trad['SN'])) $Scode = Sage_Code($Trad);
      echo fm_text("Sage Code",$Trad,'SageCode',1,'class=NotSide','class=NotSide');
    }
    echo fm_hidden("Tid", $Tid);
    echo fm_hidden("Id", $Tid);

    if ($Mode) {
      echo "<tr>" . fm_textarea('Notes',$Trad,'Notes',7,2,'class=NotSide','class=NotSide');
    }
  echo "</table></div>";
}

function Trade_TandC() {
  global $FESTSYS;
  echo $FESTSYS['TradeTandC'];
}

function Show_Trade_Year($Tid,&$Trady,$year=0,$Mode=0) {
  global $YEAR,$PLANYEAR,$YEARDATA,$Trade_States,$Mess,$Action,$ADDALL,$Trade_State_Colours,$InsuranceStates,$Trade_State,$Trade_Days,$EType_States;
  $Trad = Get_Trader($Tid);
  if ($year==0) $year=$YEAR;
  $CurYear = date("Y");
  if ($year < $PLANYEAR) { // Then it is historical - no changes allowed
    fm_addall('disabled readonly');
  }

  $Self = $_SERVER{'PHP_SELF'};
  if ($year > $CurYear) {
    if ($Mode && Get_Trade_Year($Tid,$CurYear)) 
      echo "<div class=floatright><h2><a href=$Self?id=$Tid&Y=$CurYear>$CurYear</a></h2></div>";  
    echo "<h2>Trading in $year</h2>";
  } else if ($year == $PLANYEAR) {
    if ($Mode && Get_Trade_Year($Tid,$CurYear-1)) 
      echo "<div class=floatright><h2><a href=$Self?id=$Tid&Y=" . ($CurYear-1) . ">" . ($CurYear-1) . "</a></h2></div>";  
    echo "<h2>Trading in $year</h2>";
  } else {
    if ($Mode) echo "<div class=floatright><h2><a href=$Self?id=$Tid>$PLANYEAR</a></h2></div>"; 
    echo "<h2>Details for $year</h2>";
  }
  echo fm_hidden('Year',$year);
  if (isset($Trady['TYid']) && $Trady['TYid']) echo fm_hidden('TYid',$Trady['TYid']);

  $TradeLocs = Get_Trade_Locs();

  echo "<div class=tablecont><table width=90% border class=SideTable>\n";
  echo fm_hidden('Year',$year);
  if (isset($Trady['TYid'])) echo fm_hidden('TYid',$Trady['TYid']);

  if ($Mode) {
    echo "<td class=NotCSide>Booking State:" . help('BookingState') . "<td colspan=2 class=NotCSide>";
      foreach ($Trade_States as $i=>$ts) {
        if( preg_match('/^-/',$Trade_State_Colours[$i])) continue;
        $cls = " style='background:" . $Trade_State_Colours[$i] . ";padding:4; white-space: nowrap;'";
        echo " <div class=KeepTogether $cls>$ts: ";
        echo " <input type=radio name=BookingState $ADDALL value=$i ";
        if (!Access('SysAdmin')) echo " readonly disabled ";
        if (isset($Trady['BookingState']) && ($Trady['BookingState'] == $i)) echo " checked";
        echo ">&nbsp;</div>\n ";
      }
//    echo fm_radio("Booking State",$Trade_States,$Trady,'BookingState','class=NotCSide',1,'colspan=2 class=NotCSide');
    echo "<td class=NotSide>" . fm_checkbox('Local Auth Checked',$Trady,'HealthChecked');
  } else {
    $stat = $Trady['BookingState'];
    if (!$stat) $stat = 0;
    echo fm_hidden('BookingState',$stat);
    if ($stat == $Trade_State['Fully Paid'] && ($Trady['Insurance'] == 0 || $Trady['RiskAssessment'] == 0)) {
      echo "<td>Booking State:" . help('BookingState') . "<td class=TSNoInsRA>";
      if ($Trady['Insurance'] ==0) echo "No Insurance";
      if ($Trady['RiskAssessment'] ==0) echo " No Risk Assessment";
    } else {
        echo "<td>Booking State:" . help('BookingState') . "<td ";
        if ($stat == $Trade_State['Fully Paid'] && ($Trady['Insurance'] == 0 || $Trady['RiskAssessment'] == 0)) {
          echo " class=TSNoInsRA>Paid";
          if ($Trady['Insurance'] ==0) echo ", no Insurance";
          if ($Trady['RiskAssessment'] ==0) echo ", no Risk Assess";
        } else {
          echo " style='background:" . $Trade_State_Colours[$stat] . ";padding:4; white-space: nowrap;'>" . $Trade_States[$stat];
        }
    }
  }
  
//  if (Access('SysAdmin') && isset($Trady['TYid']) ) {
//    echo "<tr><td>" . PayCodeGen("DEP",$Trady['TYid']) ."<td>" . PayCodeGen("BAL",$Trady['TYid']) ."<td>" . PayCodeGen("PAY",$Trady['TYid']);
//    echo "<tr><td>" . PayCodeGen("DEP",$Trady['TYid']+1) ."<td>" . PayCodeGen("BAL",$Trady['TYid']+1) ."<td>" . PayCodeGen("PAY",$Trady['TYid']+1);
//  }
  
  echo "<tr><td>Days:<td>" . fm_select($Trade_Days,$Trady,'Days');
  echo "<tr><td>Requested Pitch Sizes, <span class=DefaultPitch>" . Pitch_Size_Def($Trad['TradeType']) . "</span> is default" . Help('PitchSize');
  if (Feature("TradePower")) echo "<td colspan=2>Power Requirements" . Help('Power') . "<br>3 Amps - Lighting, 13 Amps - 1 Kettle...";
  if (isset($Trady['PitchLoc0']) && $Trady['PitchLoc0']) {
    echo "<td>Location<td>Pitch Number";
  } else {
    echo "<td>Location (When Assigned)<td>Pitch Number";
  }
  for ($i = 0; $i < 3; $i++) {
    $pwr = (isset($Trady["Power$i"])?$Trady["Power$i"]:0);
    echo "<tr>" . fm_text1("",$Trady,"PitchSize$i");
    if (Feature("TradePower")) {
      echo "<td colspan=2>None: <input type=radio name=PowerType$i value=0 onclick=PowerChange(0,$i) " . ($pwr==0?"checked ":"") . "> ";
      echo "My own Euro 4 Silent Generator: <input type=radio name=PowerType$i value=1 onclick=PowerChange(1,$i) " . ($pwr<0?"checked ":"") . "><br>";
      echo "<input type=radio name=PowerType$i hidden id=PowerTypeRequest$i value=2>Requested: <input type=number id=Power$i name=Power$i onchange=PowerChange(2,$i) " . 
          ($pwr>0?" value=" . $Trady["Power$i"] : "") . " min=0 max=1000>Amps";
    }
    if ($Mode) {
      echo "<td class=NotCSide>" . fm_select($TradeLocs,$Trady,"PitchLoc$i",1,'class=NotCSide');
      echo fm_text1("",$Trady,"PitchNum$i",1,'class=NotCSide','class=NotCSide');
      if (isset($Trady["PitchLoc$i"]) && $Trady["PitchLoc$i"]) echo " <a href=TradeStandMap?l=" . $Trady["PitchLoc$i"] . ">Map</a>";
    } else {
      echo "<td>";
      if (isset($Trady["PitchLoc$i"])  && $Trady["PitchLoc$i"]) {
        echo $TradeLocs[$Trady["PitchLoc$i"]];
        echo fm_hidden("PitchLoc$i",$Trady["PitchLoc$i"]);
        echo "<td>";
        if ($Trady["PitchNum$i"]) echo $Trady["PitchNum$i"] . " <a href=TradeStandMap?l=" . $Trady["PitchLoc$i"] . ">Map</a>"; // TODO Trade State testing for partial
      } else {
        echo "<td>";
      }
    }
  }
  
  include_once("InvoiceLib.php");
  $Pay = Pay_Code_Find(1,$Tid);
  if ($Pay && $Pay['State']==0) {
    echo "<tr><td>Payment due for<td colspan=5><b>" . $Pay['Reason'] . "</b><br>Due " . date('j/n/Y',$Pay['DueDate']) . "<br>Please pay " . Print_Pence($Pay['Amount']) . " to:<br>" . 
        Feature("FestBankAdr") . "<br>Sort Code: " . Feature("FestBankSortCode") . "<br>Account No: " . Feature("FestBankAccountNum") . "<p>Quote Reference: " .
        $Pay['Code'];
  };

  
  echo "<tr>";
    if ($Mode) {
      echo fm_text("Total Fee, put -1 for free",$Trady,'Fee',1,'class=NotCSide','class=NotCSide');
      echo fm_text("Paid so far",$Trady,'TotalPaid',1,'class=NotCSide','class=NotCSide');
    } else {
      echo "<td>Total Fee:<td>";
      if (!isset($Trady['Fee']) || $Trady['Fee'] == 0 ) {
        echo "To be set";
      } else if ($Trady['Fee']<0) {
        echo "Free";
      } else  {
        echo "&pound;" . $Trady['Fee'];
        echo "<td>Paid so far: &pound;" . $Trady['TotalPaid'];
      }
    }

// Notes, Insurance upload, Risk Assess inline/upload, download, Deposit Required, 
// State (Requesting, Accepted, Declined, Invoiced, Deposit Paid, Rejected, Paid, Ammended) Store when Accept
// Email link, and confamation, have means to request new link (use email address known), import existing dataZZ

// Insurance

  echo fm_DragonDrop(1,'Insurance','Trade',$Tid,$Trady,$Mode,"You <b>must</b> have a copy available with you during the festival");

// Risc Assessment function fm_DragonDrop($Call, $Type,$Cat,$id,&$Data,$Mode=0,$Mess='',$Cond=1,$tddata1='',$tdclass='',$hide=0) {
  echo fm_DragonDrop(1,'RiskAssessment','Trade',$Tid,$Trady,$Mode);

// Notes - As Sides
  echo "<tr>" . fm_textarea('Notes/Requests',$Trady,'YNotes',6,2);
  if ($Mode) echo "<tr>" . fm_textarea('Private Notes',$Trady,'PNotes',6,2,'class=NotSide','class=NotSide');
  if ($Mode) {
    if (Access('SysAdmin')) {
      echo "<tr>" . fm_textarea('History',$Trady,'History',6,2,'class=NotSide',"class='NotSide ScrollEnd'");
    } else {
      $hist = $Trady['History'];
      echo "<tr><td class=NotSide>History:<td colspan=8 class=NotSide>";
      if ($hist) {
        $hist = preg_replace('/\n/','<br>\n"',$hist);
        echo $hist . fm_hidden("History",$hist);
      }
    }
  } 
  if ($Mode) {
    if (isset($Trady['SentInvite']) && $Trady['SentInvite']) {
      echo "<tr>"; 
      echo fm_date('Invite Sent',$Trady,'SentInvite');
    }
  }
  if (Access('SysAdmin')) echo "<tr><td class=NotSide>Debug<td colspan=6 class=NotSide><textarea id=Debug></textarea>";
  echo "</table></div>\n";
}

function Get_Trade_Details(&$Trad,&$Trady) {
  global $Trade_Days,$TradeLocData,$TradeTypeData,$YEARDATA,$EType_States;

  $Body = "\nBusiness: " . $Trad['SN'] . "\n";
  $Body .= "Goods: " . $Trad['GoodsDesc'] . "\n\n";
  $Body .= "Type: " . $TradeTypeData[$Trad['TradeType']]['SN'] . "\n\n";
  if (isset($Trad['Website']) && $Trad['Website']) $Body .= "Website: " . weblink($Trad['Website'],$Trad['Website']) . "\n\n";
  $Body .= "Contact: " . $Trad['Contact'] . "\n";
  if (isset($Trad['Phone']) && $Trad['Phone']) $Body .= "Phone: " . $Trad['Phone'] . "\n";
  if (isset($Trad['Mobile']) && $Trad['Mobile']) $Body .= "Mobile: " . $Trad['Mobile'] . "\n";
  $Body .= "Email: <a href=mailto:" . $Trad['Email'] . ">" . $Trad['Email'] . "</a>\n";
  $Body .= "Address: " . $Trad['Address'] . "\n";
  $Body .= "PostCode: " . $Trad['PostCode'] . "\n\n";
  if (isset($Trad['Charity']) && $Trad['Charity']) $Body .= "Charity: " . $Trad['Charity'] . "\n";
  if (isset($Trad['PublicHealth']) && $Trad['PublicHealth']) $Body .= "Local Authority: " . $Trad['PublicHealth'] . "\n";
  if (isset($Trad['BID']) && $Trad['BID']) $Body .= "BID Member: Yes\n";
  if (isset($Trad['ChamberTrade']) && $Trad['ChamberTrade']) $Body .= "Chamber of Trade Member: Yes\n";
  if (isset($Trad['Previous']) && $Trad['Previous']) $Body .= "Previous Trader: Yes\n";
  $Body .= "\n\n";

  $Body .= "For " . $Trady['Year'] .":\n";
  $Body .= "Days: " . $Trade_Days[$Trady['Days']] . "\n";
  $Body .= "Pitch:" . $Trady['PitchSize0'];
  $Partial = (array_flip($EType_States))['Partial'];
  if ($Trady['PitchLoc0']) $Body .= " at " . $TradeLocData[$Trady['PitchLoc0']]['SN'];
  if ($YEARDATA['TradeState']>= $Partial && $Trady['PitchNum0']) $Body .= "Pitch Number "  . $Trady['PitchNum0'];
  if ($Trady['Power0']) $Body .= " with " . ($Trady["Power0"]> 0 ? $Trady['Power0'] . " Amps\n" : " own Euro 4 silent generator\n");

  if ($Trady['PitchSize1']) {
    $Body .= "\nPitch 2:" . $Trady['PitchSize1'];
    if ($Trady['PitchLoc1']) $Body .= " at " . $TradeLocData[$Trady['PitchLoc1']]['SN'];
    if ($YEARDATA['TradeState']>= $Partial && $Trady['PitchNum1']) $Body .= "Pitch Number "  . $Trady['PitchNum1'];
    if ($Trady['Power1']) $Body .= " with " . $Trady['Power1'] . " Amps\n";
  }
  if ($Trady['PitchSize2']) {
    $Body .= "\nPitch 3:" . $Trady['PitchSize2'];
    if ($Trady['PitchLoc2']) $Body .= " at " . $TradeLocData[$Trady['PitchLoc2']]['SN'];
    if ($YEARDATA['TradeState']>= $Partial && $Trady['PitchNum2']) $Body .= "Pitch Number "  . $Trady['PitchNum2'];
    if ($Trady['Power2']) $Body .= " with " . $Trady['Power2'] . " Amps\n";
  }

  if ($Trady['Fee']) {
    if ($Trady['Fee'] < 0 ) {
      $Body .= "\nFee: None.\n";
    } else {
      $Dep = T_Deposit($Trad);
      $Body .= "\nDeposit: &pound;$Dep\nBalance: &pound;" . ($Trady['Fee'] - $Dep) . "\nTotal: &pound;" . $Trady['Fee'] . "\n\n";
    }
  }
  
  $Body .= "*PAYCODES*\n";
  
  if ($Trady['YNotes']) $Body .= "Notes: " . $Trady['YNotes'] . "\n";
  if ($Trady['Insurance']) $Body .= "Insurance already upload\n";
  if ($Trady['RiskAssessment']) $Body .= "Risk Assessment already upload\n";

  $Body = preg_replace('/\n/',"<br>\n",$Body);
  return $Body;
}

function Trade_Finance(&$Trad,&$Trady) { // Finance statement as part of statement
  $Invs = Get_Invoices(" OurRef='" . Sage_Code($Trad) . "'"," IssueDate DESC ");
  if (!$Invs) return "";
  $PaidSoFar = (isset($Trady['TotalPaid']) ? $Trady['TotalPaid'] : 0);
  
  $Str = "Paid so far: &pound;$PaidSoFar<br>";
  $Dep = T_Deposit($Trad);
  if ($Dep) $Str .= "The deposit is: &pound;$Dep<br>";
  if ($PaidSoFar) {
    if ($PaidSoFar < $Trady['Fee']) $Str .= "There will be a balance of: &pound;" . ($Trady['Fee'] - $PaidSoFar) . "<br>";
  } else {
    $Str .= "There will be a balance of: &pound;" . ($Trady['Fee'] - $Dep) . "<br>";
  }
  
  $Str .= "*PAYCODES*";
  
  if ($Invs[0]['PayDate']) {
    $Str .= "The most recently paid invoice is attached for your records.<p>";
  } else {
    $Str .= "There is an outstanding invoice for " . Print_Pence($Invs[0]['Total']) . " (attached)<p>";
  }
  return $Str;
}

function Trader_Details($key,&$data,$att=0) {
  global $Trade_Days,$TradeLocData,$TradeTypeData,$Prefixes;
  $Trad = &$data[0];
  if (isset($data[1])) $Trady = &$data[1];
  $host = "https://" . $_SERVER{'HTTP_HOST'};
  $Tid = $Trad['Tid'];
  switch ($key) {
  case 'WHO':  return $Trad['Contact']? UpperFirstChr(firstword($Trad['Contact'])) : $Trad['SN'];
  case 'LINK': return "<a href='$host/int/Direct?t=Trade&id=$Tid&key=" . $Trad['AccessKey'] . "'  style='background:lightblue;'><b>link</b></a>";
  case 'WMFFLINK': 
  case 'FESTLINK' : return "<a href='$host/int/Trade?id=$Tid'><b>link</b></a>";
  case 'HERE':
  case 'REMOVE': return "<a href='$host/int/Remove?t=Trade&id=$Tid&key=" . $Trad['AccessKey'] . "'><b>remove</b></a>";
  case 'LOCATION': 
    $Locs = Get_Trade_Locs(1);
    $Location = '';
    if ($Trady['PitchLoc0']) $Location = $Locs[$Trady['PitchLoc0']]['SN'];
    if ($Trady['PitchLoc1']) {
      if ($Trady['PitchLoc2']) { $Location .= ", " . $Locs[$Trady['PitchLoc1']]['SN']; }
      else { $Location .= " and " . $Locs[$Trady['PitchLoc1']]['SN']; }
    };
    if ($Trady['PitchLoc2']) { $Location .= " and " . $Locs[$Trady['PitchLoc2']]['SN']; }
    return $Location;
  case 'PRICE':
    $Price = $Trady['Fee'];
    if ($Price < 0) return "Free";
    if ($Price ==0) return "Not Known";
    return "&pound;" . $Price;
  case 'DEPOSIT': return T_Deposit($Trad);
  case 'BALANCE': return ($Trady['Fee'] - max(T_Deposit($Trad),$Trady['TotalPaid']));
  case 'DETAILS': return Get_Trade_Details($Trad,$Trady);
  case 'PAIDSOFAR': return $Trady['TotalPaid'];
  case 'STATE': return ['No application has been made',
                        'Invitation/Quote has been declined',
                        'A refund has been made',
                        'The application has been cancelled',
                        'The application has been submitted',
                        'A price has been quoted',
                        'The application has been accepted, no deposit paid',
                        'The deposit has been paid',
                        'Final balacing payment has been requested but not paid',
                        'Fully Paid',
                        'On a wait list',
                        'Awaiting a requote after change'][$Trady['BookingState']] . "<P>";
  case 'BACSREF':
    preg_match('/(\d*)\.pdf/',$att,$mtch);
    return Sage_Code($Trad) . "/" . (isset($mtch[1]) ? $mtch[1] : '0000' );
  case 'FINANCIAL': return Trade_Finance($Trad,$Trady);
  case 'PAYDAYS' : return Feature('PaymentTerms',30);
  case 'TRADEMAP': 
    $MapLinks = '';
    for ($i=0; $i<3; $i++) {
      if ($Trady["PitchLoc$i"] && $Trady["PitchNum$i"]) {
        $plural = (strchr(',',$Trady["PitchNum$i"])?"Pitches numbered ":"Pitch number ");
        $MapLinks .= "You have been assigned $plural " . $Trady["PitchNum$i"] . " " . 
                     $Prefixes[$TradeLocData[$Trady["PitchLoc$i"]]['prefix']] . " " . $TradeLocData[$Trady["PitchLoc$i"]]['SN'] . 
                     " please see this <a href='$host/int/TradeStandMap?l=" . $Trady["PitchLoc$i"] . "' style='background:lightblue;'>map</a> " .
                     "- Note the formatting of the business names on this will be improved soon<p>";
      }
    }
    if (!$MapLinks) return "";
    return "<b>Pitch assignments</b>.  The new layouts of many areas are for health and safety reasons and are not negotiable.<p> " . $MapLinks;
  case 'WEBSITESTUFF':
    $webstuff = '';
    if (!$Trad['Photo']) {
      $webstuff = "If you would like a photo to appear on our website, please use the *LINK* to upload one.  ";
    }
    $webstuff .= "If you would like to revise the description of what sell or you do, please use the *LINK* to revise it (this will appear on our website).  ";
    return "$webstuff<p>";
  case 'DEPCODE': return $Trady['DepositCode'];
  case 'BALCODE': return $Trady['BalanceCode'];  
  case 'OTHERCODE': return $Trady['OtherCode'];
  case 'PAYCODES':
    $Pay = Pay_Code_Find(1,$Tid);
    if ($Pay && $Pay['State']==0) {
      return "<b>Payment due</b><br>For: <b>" . $Pay['Reason'] . "</b><br>Due: " . date('j/n/Y',$Pay['DueDate']) . "<br>Please pay: " . Print_Pence($Pay['Amount']) . " to:<br>" . 
          Feature("FestBankAdr") . "<br>Sort Code: " . Feature("FestBankSortCode") . "<br>Account No: " . Feature("FestBankAccountNum") . "<p>Quote Reference: " .
          $Pay['Code'] . "<p>";
    };
    return "";
  case 'VAT': if (Feature('FestVatNumber')) {
      return "Prices include VAT at " . Feature('VatRate') . "%<p>";
    } else {
      return "";
    }

/* TODO DUFF
  case 'DUEDATE' return 
    $tc = Trade_Date_Cutoff();
    if ($tc) return $tc;
    return Feature('PaymentTerms',30);
*/
  default: return "UNKNOWN CODE $key UNKNOWN UNKNOWN";
  }
}

function Trader_Admin_Details($key,&$data,$att=0) {
  $Trad = &$data[0];
  $Trady = &$data[1];
  $res = Trader_Details($key,$data,$att);
  if ($key == 'DETAILS') {
    if ($Trad['Status'] == 1) $res = "THIS IS FROM A BANNED TRADER<P>" . $res;
    if ($Trad['Notes']) $res .= "<p>PRIVATE NOTES:<br>" . $Trad['Notes'] . "<p>";
    if ($Trady['PNotes']) $res .= "<p>PRIVATE NOTES:<br>" . $Trady['PNotes'] . "<p>";
  }
  return $res;
}

function Send_Trader_Email(&$Trad,&$Trady,$messcat='Link',$att='') {
  global $PLANYEAR,$FESTSYS;
  include_once("Email.php");
  $bccto = Feature('CopyTradeEmailsTo');
  $bcc=[];
  $from = Feature('SendTradeEmailFrom');
  if ($from) $from .= "@" . $FESTSYS['HostURL'];
  if ($bccto) $bcc = ['bcc' , "$bccto@" . $FESTSYS['HostURL'],Feature('CopyTradeEmailsName')];
  Email_Proforma([['to',$Trad['Email'],$Trad['Contact']],$bcc],
    $messcat,$FESTSYS['FestName'] . " $PLANYEAR and " . $Trad['SN'],'Trader_Details',[&$Trad,&$Trady],'TradeLog',$att,0,$from);
}

function Send_Trader_Simple_Email(&$Trad,$messcat='Link',$att='') {
  global $PLANYEAR,$FESTSYS;
  include_once("Email.php");
  $from = Feature('SendTradeEmailFrom');
  if ($from) $from .= "@" . $FESTSYS['HostURL'];
  Email_Proforma([$Trad['Email'],$Trad['Contact']],$messcat,$FESTSYS['FestName'] . " $PLANYEAR and " . $Trad['SN'],'Trader_Details',[&$Trad],'TradeLog',$att,0,$from);
}

function Send_Trade_Finance_Email(&$Trad,&$Trady,$messcat,$att=0) {
  global $PLANYEAR,$FESTSYS;
  include_once("Email.php");

  Email_Proforma("treasurer@" . $FESTSYS['HostURL'],$messcat,$FESTSYS['FestName'] . " $PLANYEAR and " . $Trad['SN'],'Trader_Details',[&$Trad,&$Trady],'TradeLog',$att);
}

function Send_Trade_Admin_Email(&$Trad,&$Trady,$messcat,$att=0) {

  global $PLANYEAR,$FESTSYS;
  include_once("Email.php");

  Email_Proforma("trade@" . $FESTSYS['HostURL'],$messcat,$FESTSYS['FestName'] . " $PLANYEAR and " . $Trad['SN'],'Trader_Admin_Details',[&$Trad,&$Trady],'TradeLog',$att);
}

//  Mark as submitted, email fest and trader, record data of submission
function Submit_Application(&$Trad,&$Trady,$Mode=0) {  
  global $Trade_State,$PLANYEAR,$USER,$Trade_Days;
  $Trady['Date'] = time();
  if (!isset($Trady['History'])) $Trady['History'] = '';
  $Trady['History'] .= "Action: Submit on " . date('j M Y H:i') . " by " . ($Mode?$USER['Login']:'Trader') . ".\n";
  if ($Trady['TYid']) {
    Put_Trade_Year($Trady);
  } else { // Its new...
    $Trady['Year'] = $PLANYEAR;
    $TYid = Insert_db_post('TradeYear',$Trady);
    $Trady = Get_Trade_Year($Trad['Tid']); // Read data to get all the 0's in place
  }

  Send_Trader_Email($Trad,$Trady,'Trade_Submit');
  Send_Trade_Admin_Email($Trad,$Trady,'Trade_NewSubmit');

  echo "<h3>Your application has been submitted</h3>\nAn email has been sent to you with a summary of your submission and a link to enable you to update it.\n<p>";
  
  echo "<b>IF</b> you do not see the email, Please check your SPAM folder and mark the message as <b>Not SPAM</b>, otherwise you will not see any subsequent message from us.<p>";
}

function Validate_Trade($Mode=0) { // Mode 1 for Staff Submit, less stringent
  global $TradeTypeData;
  $Orgs = isset($_REQUEST['ORGS']);
      $proc = 1;
      if (!isset($_POST['SN']) || strlen($_POST['SN']) < 3 ) {
        echo "<h2 class=ERR>No Business Name Given</h2>\n";
        $proc = 0;
      }
      
      if ($Orgs==0 && $Mode == 0 && ($TradeTypeData[$_POST['TradeType']]['TOpen'] == 0)) {
        echo "<h2 class=ERR>Sorry that category is full for this year</h2>\n";
        $proc = 0;
      }

      if (!isset($_POST['Contact']) || strlen($_POST['Contact']) < 4 ) {
        echo "<h2 class=ERR>No Contact Name Given</h2>\n";
        $proc = 0;
      }
      if ($Orgs==0 && (!isset($_POST['Phone']) && !isset($_POST['Mobile'])) || (strlen($_POST['Phone']) < 6 && strlen($_POST['Mobile']) < 6)) {
        echo "<h2 class=MERR>No Phone/Mobile Numbers Given</h2>\n";
        if (!$Mode) $proc = 0;
      }
      if (!isset($_POST['Email']) || strlen($_POST['Email']) < 8) {
        echo "<h2 class=MERR>No Email Given</h2>\n";
        if (!$Mode) $proc = 0;
      }
      if ($Orgs==0 && !isset($_POST['Address']) || strlen($_POST['Address']) < 10) {
        echo "<h2 class=MERR>No Address Given</h2>\n";
        if (!$Mode) $proc = 0;
      }
      if ($Orgs==0 ) {
      } else if (!isset($_POST['GoodsDesc'])) {
        echo "<h2 class=ERR>No Products Description Given</h2>\n";
        $proc = 0;
      } else if ((strlen($_POST['GoodsDesc']) < 30) && ($Mode == 0)){
        echo "<h2 class=ERR>The Product Description is too short</h2>\n";
        $proc = 0;
      }
      if ($Orgs==0 && (!isset($_POST['PublicHealth']) || strlen($_POST['PublicHealth']) < 5) && ($TradeTypeData[$_POST['TradeType']]['NeedPublicHealth']) && ($Mode == 0)) {
        echo "<h2 class=ERR>No Public Health Authority Given</h2>\n";
        $proc = 0;
      }
  return $proc;
}

function Trader_Name($Tid) {
  $Trad = Get_Trader($Tid);
  return $Trad['SN'];
}

function T_Deposit(&$Trad) {
  global $TradeTypeData;
  return $TradeTypeData[$Trad['TradeType']]['Deposit'];
}

function Validate_Pitches(&$CurDat) {
  return ''; // TODO Completely wrong...
  
// Pitches not only trade - 
// Lists of Pitches
  
  global $db,$PLANYEAR,$TradeLocData;
  for ($pn=0; $pn<3; $pn++) {
    if ($_POST["PitchLoc$pn"] != $CurDat["PitchLoc$pn"] || $_POST["PitchNum$pn"] != $CurDat["PitchNum$pn"]) {
      if ($_POST["PitchLoc$pn"]) {
        if ($_POST["PitchNum$pn"]) { // Loc & NUm set, lets check them
          $pl = $_POST["PitchLoc$pn"];
          $ln = $_POST["PitchNum$pn"];
          if ($CurDat['Days'] == 0) {
            $DayTest = '';
          } else if ($CurDat['Days'] == 1) {
            $DayTest = " AND ( Days!=2 ) ";
          } else {
            $DayTest = " AND ( Days!=1 ) ";
          }
          $qry = "SELECT * FROM TradeYear WHERE Year=$PLANYEAR AND (( PitchLoc0=$pl AND PitchNum0=$ln ) || (PitchLoc1=$pl AND PitchNum1=$ln) " .
                 " || (PitchLoc2=$pl AND PitchNum2=$ln)) $Daytest";
          $res = $db->query($qry);
          if ($res->num_rows != 0) {
            $dat = $res->fetch_assoc();
            return "Pitch " . ($pn+1) . " already in use by " . Trader_Name($dat['Tid']);
          }
          if ($ln > $TradeLocData[$pl]['Pitches']) return "Pitch Number " . ($pn+1) . " Out of range (1-" . $TradeLocData[$pl]['Pitches'] . ")";
        }
      }
    }
  }
  return '';   
}

function Trade_Main($Mode,$Program,$iddd=0) {
// Mode 0 = Traders, 1 = ctte, 2 = Finance (for other invoices) Program = Trade/Trader $iddd if set starts it up, with that Tid

  global $YEAR,$PLANYEAR,$Mess,$Action,$Trade_State,$Trade_States,$USER,$TS_Actions,$ButExtra,$ButTrader,$ButAdmin,$RestrictButs;
  global $TradeTypeData,$TradeLocData,$FESTSYS;
  include_once("DateTime.php"); 
  echo "<div class=content><h2>Add/Edit " . ($Mode<2?'Trade Stall Booking':'Buisness or Organisation') . "</h2>";

/*
  $file = fopen("LogFiles/moeslog",'a+');
  fwrite($file,json_encode($_REQUEST));
  fclose($file);
*/


  $Orgs = isset($_REQUEST['ORGS']);
  
  $Action = 0; 
  $Mess = '';
  if (isset($_POST{'Action'})) {
    include_once("Uploading.php");
    $Action = $_POST{'Action'};
    switch ($Action) {
    case 'PASpecUpload':
      $Mess = Upload_PASpec();
      break;
    case 'Insurance':
      $Mess = Upload_Insurance('Trade');
      break;
    case 'Photo':
      $Mess = Upload_Photo('Trade');
      break;
    case 'Delete':
      if (Access('SysAdmin')) {
        $Tid = $_POST{'Tid'};
        db_delete('Trade',$Tid);
        include_once("Staff.php");  // No return
      }
      break;
    default:
      $Mess = "!!!";
    }
  }

  if ($iddd != 0) {
    unset($_POST['Tid']);
    if ($iddd > 0) {
      $_GET{'id'} = $iddd;
    } else {
      unset($_GET['id']);
    }
  }

  if (isset($_POST{'Tid'})) { /* Response to update button */
    $Tid = $_POST{'Tid'};

//    A_Check('Participant','Trader',$Tid); // Check Surpressed until access resolved

    if (!$Orgs) {
      if (Feature("TradePower")) for ($i=0;$i<3;$i++) if ($_POST["PowerType$i"]==1) $_POST["Power$i"] = -1;
      Clean_Email($_POST{'Email'});
//    Clean_Email($_POST{'AltEmail'});
      $proc = Validate_Trade($Mode);
    }

//echo "Trade Validation: $proc <br>;
    if ($Tid > 0) {                                 // existing Trader 
      $Trad = Get_Trader($Tid);
      if ($Trad) {
        if (!$Orgs) {
          $Tradyrs = Get_Trade_Years($Tid);
          if (isset($Tradyrs[$PLANYEAR])) $Trady = $Tradyrs[$PLANYEAR];
        }
      } else {
        echo "<h2 class=ERR>Could not find Trader $Tid</h2>\n";
      }

      if (isset($_POST{'NewAccessKey'})) $_POST{'AccessKey'} = rand_string(40);

      Update_db_post('Trade',$Trad);
      Report_Log('Trade');
      if ($Mode < 2 && !$Orgs) {
        if ($_POST{'Year'} == $PLANYEAR) {
          $same = 1;
          if (isset($Trady) && $Trady) {
            $OldFee = $Trady['Fee'];
            if ($Mode && isset($Trady['BookingState'])) {
              if ($Trady['BookingState'] != $_POST['BookingState']) {
                $_POST['History'] .= "Action: " . $Trade_States[$_POST['BookingState']] . " on " . date('j M Y H:i') . " by " . $USER['Login'] . ".\n";
              }
              if ($_POST['Fee'] < 0 && $_POST['BookingState'] == $Trade_State['Deposit Paid']) {
                $_POST['BookingState'] = $Trade_State['Fully Paid'];
                $_POST['History'] .= "Action: " . $Trade_States[$_POST['BookingState']] . " on " . date('j M Y H:i') . " by " . $USER['Login'] . ".\n";
              } 
              if ($_POST['PitchLoc0'] != $Trady['PitchLoc0'] || $_POST['PitchLoc1'] != $Trady['PitchLoc1'] || $_POST['PitchLoc2'] != $Trady['PitchLoc2'] ||
                  $_POST['PitchNum0'] != $Trady['PitchNum0'] || $_POST['PitchNum1'] != $Trady['PitchNum1'] || $_POST['PitchNum2'] != $Trady['PitchNum2'] ) {
                $Mess = Validate_Pitches($Trady);
                if ($Mess) echo "<h2 class=Err>$Mess</h2>";
              };
            } 
          
            $same=1;
            foreach(["PitchSize0","PitchSize1","PitchSize2","Days"] as $cc) if ($Trady[$cc] != $_POST[$cc]) $same = 0; 
            if ($Trad['TradeType'] != $_POST['TradeType']) $same = 0; 
            foreach(["Power0","Power1","Power2"] as $cc) if ($Trady[$cc] && $_POST[$cc] && $Trady[$cc] != $_POST[$cc]) $same = 0;

            if (!$Mess) Update_db_post('TradeYear',$Trady);
            if (!$Mess && $same == 0 && $Trady['BookingState'] > $Trade_State['Submitted']) {
              Send_Trade_Admin_Email($Trad,$Trady,'Trade_Changes');
              $Trady['BookingState'] = $Trade_State['Requote'];
              Put_Trade_Year($Trady);
            }
            if (!Feature('AutoInvoices') && $Trady['Fee'] >=0 && $OldFee != $Trady['Fee'] && $Trady['BookingState'] >= $Trade_State['Accepted']) 
                  Send_Trade_Finance_Email($Trad,$Trady,'Trade_UpdateBalance');
          } else {
            $chks = ['Insurance','RiskAssessment','PitchSize0','PitchSize1','PitchSize2','Power0','Power1','Power2','YNotes','BookingState','Submit','Days','Fee','PitchLoc0','PitchLoc1',
                      'PitchLoc2','ACTION'];
            foreach($chks as $c) if (isset($_POST[$c]) && $_POST[$c]) {
              if ($c == 'PitchSize0' && $_POST[$c] == "3Mx3M") continue; // This is the only non blank default
              if (isset($_POST['Fee']) && ($_POST['Fee'] < 0) && ($_POST['BookingState'] >= $Trade_State['Accepted'])) $_POST['BookingState'] = $Trade_State['Fully Paid'];
              $_POST['Year'] = $PLANYEAR;
              $TYid = Insert_db_post('TradeYear',$Trady);
              $Trady = Get_Trade_Year($Trad['Tid']);
              break;
            }
          }
        }
        if ($proc && isset($_POST['ACTION'])) Trade_Action($_POST['ACTION'],$Trad,$Trady,$Mode);
      } else { // Mode ==2 || Orgs
//        if (isset($_POST['ACTION'])) Invoice_Action($_POST['ACTION'],$Trad);
      }
    } else { // New trader 
      $_POST['AccessKey'] = rand_string(40);
      $Tid = Insert_db_post('Trade',$Trad);
      if ($Tid && !$Orgs && $Trad['IsTrader'] ) {
        Insert_db_post('TradeYear',$Trady);
        $Trady = Get_Trade_Year($Trad['Tid']);
      }
      if ($Mode == 2 || $Orgs) {
//        if (isset($_POST['ACTION'])) Invoice_Action($_POST['ACTION'],$Trad);
      } else {
        if ($proc && isset($_POST['ACTION'])) Trade_Action($_POST['ACTION'],$Trad,$Trady,$Mode);
      }
    }
    if ($Mode !== 2 && $proc && isset($_POST['Submit'])) Submit_Application($Trad,$Trady,$Mode);

  } elseif (isset($_GET{'id'})) { // Link from elsewhere 
    $Tid = $_GET{'id'};
    $Trad = Get_Trader($Tid);
    if ($Trad && $Trad['IsTrader'] && !$Orgs) {
      $Tradyrs = Get_Trade_Years($Tid);
      if (isset($Tradyrs[$YEAR])) {
        $Trady = $Tradyrs[$YEAR];
      } else {
        $Trady = Default_Trade($Tid,$Trad['TradeType']);
      }
    } elseif (!$Trad) {
      echo "<h2 class=ERR>Could not find Trader $Tid</h2>\n";
    }
  } elseif ($Mode != 2 && !$Orgs) {
    $Tid = -1;
    $Trad = ['TradeType' => 1, 'IsTrader' => 1];
  } else {
    $Tid = -1;
    $Trad = ['TradeType' => 1, 'IsTrader' => 0];  
  }
  if (!isset($Trady)) $Trady = Default_Trade($Tid,$Trad['TradeType']);

  Show_Trader($Tid,$Trad,$Program,$Mode);
  if ($Mode < 2 && !$Orgs) Show_Trade_Year($Tid,$Trady,$YEAR,$Mode);

  if ($Mode == 0 && !$Orgs) {
    Trade_TandC();
    echo $FESTSYS['TradeTimes'];
    echo $FESTSYS['TradeFAQ'];
  }

  if ($Tid > 0) {
    if ($Mode < 2 && !$Orgs) {
      if (!isset($Trady['BookingState'])) { $Trady['BookingState'] = 0; $Trady['Fee'] = 0; }
      if (Access('SysAdmin')) {
        echo "<div class=floatright>";
        echo "<input type=Submit id=smallsubmit name='NewAccessKey' value='New Access Key'>";
        if (!Feature("AutoInvoices") && $Trady['BookingState'] >= $Trade_State['Accepted']) echo "<input type=Submit id=smallsubmit name='ACTION' value='Resend Finance'>";
        echo "</div>\n";
      }
    }
    echo "<Center>";
    echo "<input type=Submit name='Update' value='Save Changes'>";
    if (Access('Committee','Finance')) {
      echo "<input type=Submit name='NewInvoice' title='Send a NON TRADE Invoice to this trader' value='New Invoice' formaction='InvoiceManage?ACTION=NEWFOR&Tid=$Tid'>\n";
    }
//    if (!isset($Trady['BookingState']) || $Trady['BookingState']== 0) echo "<input type=Submit name=Submit value='Save Changes and Submit Application'>";

    $Act = (($Mode < 2 && !$Orgs)? $TS_Actions[$Trady['BookingState']] :"");
    if ($Act ) {
      $Acts = preg_split('/,/',$Act); 
//      if ($TradeTypeData[$Trad['TradeType']]['ArtisanMsgs']) {
//        if ($TradeLocData[$Trady['PitchLoc0']]['ArtisanMsgs']) $dummy=1;
//      }
//echo $Trad['TradeType'];
      if ($TradeTypeData[$Trad['TradeType']]['ArtisanMsgs'] && isset($Trady['PitchLoc0']) && $Trady['PitchLoc0'] && $TradeLocData[$Trady['PitchLoc0']]['ArtisanMsgs']) $Acts[] = 'Artisan Invite';
      foreach($Acts as $ac) {
        if ($Mode==0 && !in_array($ac,$ButTrader)) continue;
        if ($Mode==1 && !Access('SysAdmin') && !in_array($ac,$ButAdmin)) continue;
        if (!isset($Trady['Fee'])) $Trady['Fee'] = 0;
        if (Feature('AutoInvoices') && !Access('SysAdmin') && in_array($ac,$RestrictButs)) continue;  // Normal people cant hit Paid have to be through the invoice
        switch ($ac) {
          case 'Quote':
            if ($Trady['Fee'] == 0) continue 2;
            break;
          case 'Artisan Invite':
            if ($Trady['Fee'] == 0) continue 2;
            break;
          case 'Invite':
            if ($Trady['Fee'] == 0) continue 2;
            break;
          case 'Invite Better':
            if ($Trady['Fee'] == 0) continue 2;
            break;
          case 'Accept':
            if ($Trady['Fee'] == 0) continue 2;
            break;
          case 'Dep Paid':
            if ($Trady['Fee'] == 0 || Feature('AutoInvoices')) continue 2;
            break;
          case 'Paid':
            if ($Trady['Fee'] == 0 || Feature('AutoInvoices')) continue 2;
            break;
          case 'Invoice':
          case 'Bal Request':
            if ($Trady['PitchLoc0'] == 0 || $Trady['Fee'] == 0) continue 2;
            break;
          default:
        }
        echo "<input type=submit name=ACTION value='$ac' " . $ButExtra[$ac] . " >";
      }
    }
    if ($Mode == 0) { 
      include_once("InvoiceLib.php");   
      $Invs = Get_InvoicesFor($Tid);
      if ($Invs) echo "<input type=submit name=ACTION value='Invoices'>";
    }
    
    echo "</center>\n";
  } else { 
    echo "<Center>";
    echo "<input type=Submit name=ACTION value='Create'>\n";
    if ($Mode < 2) echo "<input type=Submit name=ACTION value='Create and Submit Application'>";
    echo "</center>\n";
  }
  echo "</form>\n";

  if ($Mode==1 && $Tid>0) {
    $Invs = Get_Invoices(" OurRef='" . Sage_Code($Trad) . "'"," IssueDate DESC ");
    echo "<h2><a href=ListCTrade>List Traders Coming</a> ";
//    var_dump($Invs);
    if ($Invs) echo ", <a href=InvoiceManage?FOR=$Tid>Show All Invoices for " . $Trad['SN'] . "</a>";
    echo "</h2>";
  }
}

function Trade_Date_Cutoff() { // return 0 - normal, 30, full payment (normal duration), >0 = Days left to trade stop (full payment)
  global $YEARDATA;
  $Now = time();
  $PayTerm = Feature('PaymentTerms',30);
  if ($YEARDATA['TradeMainDate'] > $Now) return 0;
  if ($Now >= $YEARDATA['TradeLastDate']) return 2;
  $DaysLeft = intdiv(($YEARDATA['TradeLastDate'] - $Now),24*60*60);
  if ($DaysLeft > $PayTerm) $DaysLeft = $PayTerm;
  if ($DaysLeft < 2) $DaysLeft = 2;
  return $DaysLeft;
}

function Trade_Invoice_Code(&$Trad,&$Trady) {
  global $TradeLocData,$TradeTypeData;
  $InvCode = 0;
  if ($Trady['PitchLoc0']) $InvCode = $TradeLocData[$Trady['PitchLoc0']]['InvoiceCode'];
  if ($InvCode == 0) $InvCode = $TradeTypeData[$Trad['TradeType']]['SalesCode'];
//  echo "<p>Returning Invoice Code $InvCode<p>";
  return $InvCode;
}

function Trade_Deposit_Invoice(&$Trad,&$Trady,$Full='Full',$extra='',$Paid=0) {
  global $Trade_Days,$PLANYEAR;
  if (! Feature("AutoInvoices")) return 0;
  
  $Dep = T_Deposit($Trad);
  $InvPay = Feature('TradeInvoicePay');
  if (!$InvPay ) {
    $PaidSoFar = (isset($Trady['TotalPaid']) ? $Trady['TotalPaid'] : 0);
    if ($PaidSoFar) {
      $Dep -= $PaidSoFar;
      if ($Dep < 0) $Dep = 0;
    }
  }
  $InvCode = Trade_Invoice_Code($Trad,$Trady);
  $DueDate = Trade_Date_Cutoff();
  if ($DueDate == 0 || $InvPay ) {
//      if (Now < Main invoice date, Due = 30, else invoice full amount (if Now < 30 before cut date, Due = 30, else Due = CutDate - now
    $ipdf = New_Invoice($Trad,
                        ["Deposit for trade stand at the $PLANYEAR festival",$Dep*100],
                        'Trade Stand Deposit',
                        $InvCode,1,-1,0,0,$Paid);
  } else {
    $details = ["$Full fees for trade stand at the $PLANYEAR festival",$Trady['Fee']*100];
    if ($extra) $details = [$details,$extra];
    $ipdf = New_Invoice($Trad,
                        $details,
                        'Trade Stand Full Charge',
                        $InvCode, 1, $DueDate,0,0,$Paid);
  }
  return $ipdf;
}

// Highly recursive set of actions - some trigger others amt = paid amount (0 = all)
function Trade_Action($Action,&$Trad,&$Trady,$Mode=0,$Hist='',$data='', $invid=0) {
  global $Trade_State,$TradeTypeData,$USER,$TradeLocData,$PLANYEAR;
  include_once("InvoiceLib.php");
  $Tchng = $Ychng = 0;
  $PaidSoFar = (isset($Trady['TotalPaid']) ? $Trady['TotalPaid'] : 0);
  $CurState = $NewState = (isset($Trady['BookingState']) ? $Trady['BookingState'] : 0);
  $xtra = '';
  $InvPay = Feature("TradeInvoicePay"); // Switch Invoice or Just Paycodes

  switch ($Action) {
  case 'Create' :
    break;

  case 'Create and Submit Application':
  case 'Submit' :
    if (isset($Trady['Fee']) && $Trady['Fee']) {
      Trade_Action('Accept',$Trad,$Trady,$Mode,"$Hist $Action");
      return;
    } else {
      if ($CurState >= $Trade_State['Submitted']) {
        echo "<h3>This has already been Submitted</h3>";
        return;
      }

      echo "This takes a few seconds, please be patient.<p>";
      $NewState = $Trade_State['Submitted'];
      Submit_Application($Trad,$Trady,$Mode);
    }
    break;

  case 'Accept' :
    if ($CurState >= $Trade_State['Accepted'] && $CurState < $Trade_State['Wait List']) {
      echo "<h3>This has already been accepted</h3>";
      return;
    }
    $Dep = T_Deposit($Trad);
    $NewState = $Trade_State['Accepted'];
    if ($Dep <= $PaidSoFar) {
      Trade_Action('Dep Paid',$Trad,$Trady,$Mode,"$Hist $Action");
      Send_Trader_Email($Trad,$Trady,'Trade_AcceptNoDeposit');
      return;
    }

    $ProformaName = (($TradeTypeData[$Trad['TradeType']]['ArtisanMsgs'] && $TradeLocData[$Trady['PitchLoc0']]['ArtisanMsgs']) ? "Trade_Artisan_Accept" : "Trade_Accepted");
    if ($InvPay) {
      $DueDate = Trade_Date_Cutoff();
      if ($DueDate) { // Single Pay Request
        $Code = Pay_Rec_Gen("PAY",$Trady['Fee']*100,1,$Trad['Tid'],$Trad['SN'],'Trade Stand Full Payment',$DueDate);
        Send_Trader_Email($Trad,$Trady,$ProformaName . "_FullPayment");    
      } else { // Deposit 
        $Code = Pay_Rec_Gen("DEP",$Dep*100,1,$Trad['Tid'],$Trad['SN'],'Trade Stand Deposit',Feature('PaymentTerms',30));
        Send_Trader_Email($Trad,$Trady,$ProformaName . "_DepositPayment");        
      }
    } else {
      $ipdf = Trade_Deposit_Invoice($Trad,$Trady);
   
      if ($ipdf) {
        $DueDate = Trade_Date_Cutoff();
        Send_Trader_Email($Trad,$Trady,$ProformaName . ($DueDate?"_FullInvoice":"_Invoice"),$ipdf);
      } else {
        Send_Trader_Email($Trad,$Trady,$ProformaName);
        Send_Trade_Finance_Email($Trad,$Trady,'Trade_RequestDeposit');
      }
    }
    break;
    
  case 'Invoice':
    if ($CurState >= $Trade_State['Invoiced']) {
      echo "<h3>This has already been Invoiced</h3>";
      return;
    }

    if ($CurState == $Trade_State['Fully Paid']) break; // should not be here...
    $Fee = $Trady['Fee'];
    if ($Fee <= $PaidSoFar) { // Fully paid on depoist invoice - needs final invoice
      $NewState = $Trade_State['Fully Paid']; // Should not be here...
      break; 
    }

    if (Feature("AutoInvoices")) {
      $ProformaName = (($TradeTypeData[$Trad['TradeType']]['ArtisanMsgs'] && $TradeLocData[$Trady['PitchLoc0']]['ArtisanMsgs']) ? "Trade_Artisan_Final_Invoice" : "Trade_Final_Invoice");
      $InvCode = Trade_Invoice_Code($Trad,$Trady);
      $DueDate = Trade_Date_Cutoff();
      $ipdf = New_Invoice($Trad,
                          [["Balance payment to secure trade stand at the $PLANYEAR festival",$Fee*100],["Less your deposit payment",-$PaidSoFar*100]],
                           'Trade Stand Balance Charge',
                           $InvCode, 1, ($DueDate?$DueDate:30) );
      Send_Trader_Email($Trad,$Trady,$ProformaName,$ipdf);
      $NewState = $Trade_State['Balance Requested'];
    }
    break;
    
  case 'Send Bal': // Send requests for final payments
  
    if ($CurState == $Trade_State['Fully Paid']) break; // should not be here...
    $Fee = $Trady['Fee'];
    if ($Fee <= $PaidSoFar) { // Fully paid on depoist invoice - needs final invoice
      $NewState = $Trade_State['Fully Paid']; // Should not be here...
      break; 
    } 
    if ($CurState == $Trade_State['Deposit Paid']) {
      $DueDate = Trade_Date_Cutoff();
      $ProformaName = (($TradeTypeData[$Trad['TradeType']]['ArtisanMsgs'] && $Trady['PitchLoc0'] && $TradeLocData[$Trady['PitchLoc0']]['ArtisanMsgs']) ? 
                        "Trade_Artisan_FinalPayment" : "Trade_FinalPayment");
      $Code = Pay_Rec_Gen("BAL",($Trady['Fee']- $PaidSoFar)*100,1,$Trad['Tid'],$Trad['SN'],'Trade Stand Balance Payment',$DueDate);
      Send_Trader_Email($Trad,$Trady,$ProformaName);    
    }
    $NewState = $Trade_State['Balance Requested']; 
    break;
  
  case 'LastWeek' : // Send Last week message
    Send_Trader_Email($Trad,$Trady,'Trade_Quote_WeekLeft');   
    break;
  
  case 'Resend Finance':
    Send_Trade_Finance_Email($Trad,$Trady,'Trade_RequestDeposit');  // Only used when no auto invoices
    break;

  case 'Decline' :
    if ($CurState == $Trade_State['Declined']) {
      echo "<h3>This has already been Declined</h3>";
      return;
    }

    Pay_Code_Remove(1,$Tid);
    
    $NewState = $Trade_State['Declined'];
    $att = 0;
    if ($InvPay) {
      Invoice_RemoveCode(PayCodeGen("DEP",$Trady['TYid']));
      Invoice_RemoveCode(PayCodeGen("BAL",$Trady['TYid']));
      Invoice_RemoveCode(PayCodeGen("PAY",$Trady['TYid']));
    } else {
      if ($CurState == $Trade_State['Accepted']) { // Should not be here ...
        // Is there an invoice ? If so credit it and attach credit note
        $Invs = Get_Invoices(" PayDate=0 AND OurRef='" . Sage_Code($Trad) . "'"," IssueDate DESC ");
        if ($Invs) $att = Invoice_Credit_Note($Invs[0]);
      }
    }
    Send_Trader_Email($Trad,$Trady,'Trade_Decline',$att);
    break;

  case 'Hold' :
    if ($CurState == $Trade_State['Wait List']) {
      echo "<h3>This has already been Wait Listed</h3>";
      return;
    }

    $NewState = $Trade_State['Wait List'];
    Send_Trader_Email($Trad,$Trady,'Trade_Hold');
    break;

  case 'Dep Paid' : // Old Invoice Code
    if ($Trady['Fee'] < 0 || $Trady['Fee'] <= $PaidSoFar) {
      Trade_Action('Paid',$Trad,$Trady,$Mode,"$Hist $Action");
      return;
    } else  { // Should not need anything
      $Dep = T_Deposit($Trad);
      if (!$data) $data = $Dep;
      $Trady['TotalPaid'] += $data;
      $Ychng = 1;
      
      $xtra = " of $Dep ";
      if ($Trady['TotalPaid'] >= $Dep) {
        $NewState = $Trade_State['Deposit Paid'];
        $DueDate = Trade_Date_Cutoff();
        if ($DueDate) Trade_Action('Bal Request',$Trad,$Trady,$Mode,"$Hist $Action");
      }
    }
    break;
    
  case 'PPaid': // Paid Clicked from Payment page
    $Trady['TotalPaid'] += $data;
    $Fee = $Trady['Fee'];
    $InvCode = Trade_Invoice_Code($Trad,$Trady);
    $Ychng = 1;
    $xtra = $data;
    if ($CurState == $Trade_State['Accepted']) {
      $NewState = $Trade_State['Deposit Paid'];
      $ipdf = Trade_Deposit_Invoice($Trad,$Trady,'Deposit','',1);
      Send_Trader_Email($Trad,$Trady,'Trade_Deposit_Paid_Invoice',$ipdf);
      // mark paid, get invoice email invoice
    } else if ($CurState == $Trade_State['Balance Requested']) {
      $NewState = $Trade_State['Fully Paid'];
      $DueDate = Trade_Date_Cutoff();      
      if ($PaidSoFar) {
        $ipdf = New_Invoice($Trad,
                          [["Balance payment to secure trade stand at the $PLANYEAR festival",$Fee*100],["Less your deposit payment",-$PaidSoFar*100]],
                           'Trade Stand Balance Charge',
                           $InvCode, 1, ($DueDate?$DueDate:-1),0,0,1 );
        Send_Trader_Email($Trad,$Trady,'Trade_Fully_Paid_Invoice',$ipdf);
      } else {
        $ipdf = New_Invoice($Trad,
                          [["Full payment to secure trade stand at the $PLANYEAR festival",$Fee*100]],
                           'Trade Stand Full Charge',
                           $InvCode, 1, ($DueDate?$DueDate:-1),0,0,1 );
        Send_Trader_Email($Trad,$Trady,'Trade_Fully_Paid_Invoice',$ipdf);
      }
      // Mark Fully Paid, get invoice, email invoice
    
    } else { // error report
      Send_SysAdmin_Email('Payment Paid in wrong state',$Trady);
    }
    break;

  case 'PDiff' :
    $PaidBefore = $Trady['TotalPaid'];
    $Trady['TotalPaid'] += $data;
    $PaidSoFar = $Trady['TotalPaid'];
    $Ychng = 1;
    $Dep = T_Deposit($Trad);
    $Fee = $Trady['Fee'];
    $InvCode = Trade_Invoice_Code($Trad,$Trady);
    $xtra = $data;
/* 
  if Paid < Deposit, nothing
  Paid < Full, Invoice Depost (Plus what was paid) leave as dep paid 
  if Paid == Full, Invoice Full, State -> Fully Paid
  if Paid > Full, Invoice Full, State -> Fully Paid, sys message

*/    
    if ($PaidSoFar < $Dep) {
      Send_Trader_Email($Trad,$Trady,"Trade_Partial_Payment");
    } elseif ($PaidSoFar == $Dep) {
      if ($CurState == $Trade_State['Accepted']) {
        $NewState = $Trade_State['Deposit Paid'];
        $ipdf = Trade_Deposit_Invoice($Trad,$Trady,'Deposit','',1);
        Send_Trader_Email($Trad,$Trady,'Trade_Deposit_Paid_Invoice',$ipdf);
      } else {
        Send_SysAdmin_Email('Trader Paid when not expected',$Trady);
      }
    } elseif ($PaidSoFar < $Fee) {
      if ($CurState == $Trade_State['Accepted']) {
        $NewState = $Trade_State['Deposit Paid'];
        $ipdf = Trade_Deposit_Invoice($Trad,$Trady,'Deposit','',1);
        Send_Trader_Email($Trad,$Trady,'Trade_Deposit_Paid_Invoice',$ipdf);
      } elseif ($CurState != $Trade_State['Balance Requested']) {
        Send_SysAdmin_Email('Trader Paid when not expected',$Trady);
      }
    }  else {
      if ($PaidSoFar > $Fee) {
        Send_SysAdmin_Email('Trader Paid more than fee!',$Trady);
      }
      if ($CurState == $Trade_State['Accepted']) {
        $NewState = $Trade_State['Fully Paid'];
        $ipdf = New_Invoice($Trad,
                          [["Full payment to secure trade stand at the $PLANYEAR festival",$Fee*100]],
                           'Trade Stand Full Charge',
                           $InvCode, 1,-1,0,0,1 );
        Send_Trader_Email($Trad,$Trady,'Trade_Fully_Paid_Invoice',$ipdf);
      } elseif ($CurState == $Trade_State['Balance Requested']) {                    
        $NewState = $Trade_State['Fully Paid'];
        $ipdf = New_Invoice($Trad,
                          [["Balance payment to secure trade stand at the $PLANYEAR festival",$Fee*100],["Less your deposit payment",-$PaidBefore*100]],
                           'Trade Stand Balance Charge',
                           $InvCode, 1,-1,0,0,1 );
        Send_Trader_Email($Trad,$Trady,'Trade_Balance_Paid_Invoice',$ipdf);
      } else {
        Send_SysAdmin_Email('Trader Paid when not expected',$Trady);
      }
    }
    break;
  

  case 'Paid' :
    $Dep = T_Deposit($Trad);
    $fee = $Trady['Fee'];
    if (($fee > 0) && ($fee > $PaidSoFar)) {
      if (!$data) $data = $fee-$Dep;
//var_dump($data);
      $Trady['TotalPaid'] += $data;
      $Ychng = 1;
//var_dump($Trady);
    }
    $xtra = $data;
    if ($Trady['TotalPaid'] >= $fee) { 
      $NewState = $Trade_State['Fully Paid'];  // if paid > invoiced amend invoice to full 
      if ($invid) {
        Update_Invoice($invid,["Balance of Fees for trade stand at the $PLANYEAR festival",($fee-$Dep)*100],0);
        $inv = Get_Invoice($invid);
        $att = Get_Invoice_Pdf($invid,'',$inv['Revision']);
        Send_Trader_Email($Trad,$Trady,'Trade_Statement',$att);
      }
    } else if ($Trady['TotalPaid'] >= $Dep && $CurState == $Trade_State['Accepted']) {
      $NewState = $Trade_State['Deposit Paid'];
      $Action = "Deposit Paid";
    }
    break;

  case 'Local Auth Checked' :
    $Trady['HealthChecked'] = 1;
    $Ychng = 1;
    break;

  case 'Ins Checked' :
    $Trady['Insurance'] = 2;
    $Ychng = 1;
    break;

  case 'RA Checked' :
    $Trady['RiskAssessment'] = 2;
    $Ychng = 1;
    break;

  case 'Cancel' : // If invoiced - credit note, free up fee and locations if set email moe need a reason field
    if ($CurState == $Trade_State['Cancelled']) {
      echo "<h3>This has already been Cancelled</h3>";
      return;
    }

    $att = 0;

    Pay_Code_Remove(1,$Tid);
    
    // Is there an invoice ? If so credit it and attach credit note
    $Invs = Get_Invoices(" PayDate=0 AND OurRef='" . Sage_Code($Trad) . "'"," IssueDate DESC ");
    if ($Invs) $att = Invoice_Credit_Note($Invs[0],$data);  // TODO BUG
    $NewState = $Trade_State['Cancelled'];
    Send_Trader_Email($Trad,$Trady,'Trade_Cancel',$att);
    Send_Trade_Admin_Email($Trad,$Trady,'Trade_Cancel_Admin');
    
    $xtra .= "Fee was " . $Trady['Fee'] . ", Pitch was " . $Trady['PitchLoc0'] . ", Number was " . $Trady['PitchNum0'] . "\n";
    $Trady['Fee'] = 0;
    $Trady['PitchLoc0'] = $Trady['PitchLoc1'] = $Trady['PitchLoc2'] = '';
    $Trady['PitchNum0'] = $Trady['PitchNum1'] = $Trady['PitchNum2'] = '';
    $Ychng = 1;
    break;

  case 'Change' :
    $NewState = $Trade_State['Requote'];
    Send_Trader_Email($Trad,$Trady,'Trade_Changes');
    Send_Trade_Admin_Email($Trad,$Trady,'Trade_Changes');
    break;

  case 'Invite' :
    if ($Trady['Fee']) {
      $NewState = $Trade_State['Quoted'];
      Send_Trader_Email($Trad,$Trady,'Trade_Invitation');
    }
    break;

  case 'Artisan Invite' :
    if ($Trady['Fee']) {
      $NewState = $Trade_State['Quoted'];
      Send_Trader_Email($Trad,$Trady,'Trade_Artisan_Invite');
    }
    break;

  case 'Invite Better' :
    if ($Trady['Fee']) {
      $NewState = $Trade_State['Quoted'];
      Send_Trader_Email($Trad,$Trady,'Trade_InvitationBetter');
    }
    break;

  case 'Quote' :
  /*
    if (!requote) just quote (Quoted)
    else if free then fully paid and message (fully Paid)
    else if dep not paid and not due and dep not changed Statement (Accepted)
    else if dep not paid and due and not invoiced Issue full invoice (Invoiced)
    else if dep not paid and due and invoice credit and new invoice (Invoiced) 
    else if dep paid and not due - statement (Dep Paid)
    else if no invoice issue balance invoice (Invoiced)
    else if not yet paid - additional invoice (Invoiced)
    if paid new invoice for extra (Invoiced)
    
    if dep not paid and dep not changed { if due
    
  */
  
    if ($CurState != $Trade_State['Requote']) {
      $NewState = $Trady['BookingState'] = $Trade_State['Quoted'];
      Send_Trader_Email($Trad,$Trady,'Trade_Quote');    
    } elseif ($Trady['Fee'] <0) {
      $NewState = $Trady['BookingState'] = $Trade_State['Fully Paid'];
      Send_Trader_Email($Trad,$Trady,'Trade_AcceptNoDeposit');
    } else {
      $Invs = Get_Invoices(" OurRef='" . Sage_Code($Trad) . "'"," IssueDate DESC ");
      $InvoicedTotal = 0;
      foreach ($Invs as $inv) $InvoicedTotal += $inv['Total'];
      $Dep = T_Deposit($Trad);
      $DueDate = Trade_Date_Cutoff();
      if ($Invs) $invoice = Get_Invoice_Pdf($Invs[0]['id']);
      
      if ($PaidSoFar < $Dep && $DueDate==0) {  // Need a deposit
        if ($Invs && $PaidSoFar==0 && $InvoicedTotal>=$Dep) { // For info no action required, existing deposit fine, repeat it
          $NewState = $Trady['BookingState'] = $Trade_State['Accepted'];
          Send_Trader_Email($Trad,$Trady,'Trade_Statement',$invoice);  
        } elseif (!$Invs) {
          $ProformaName = (($TradeTypeData[$Trad['TradeType']]['ArtisanMsgs'] && $TradeLocData[$Trady['PitchLoc0']]['ArtisanMsgs']) ? "Trade_Artisan_Accept" : "Trade_Accepted");
          $ipdf = Trade_Deposit_Invoice($Trad,$Trady);
          if ($ipdf) Send_Trader_Email($Trad,$Trady,$ProformaName . ($DueDate?"_FullInvoice":"_Invoice"),$ipdf);
        } else {
          $NewState = $Trady['BookingState'] = $Trade_State['Deposit Paid'];
          Send_Trader_Email($Trad,$Trady,'Trade_Statement');  // For info no action required
        }
      } elseif ($DueDate) { // Issue /update final invoice
        $Fee = $Trady['Fee'];
        if (Feature("AutoInvoices")) {
          $ProformaName = (($TradeTypeData[$Trad['TradeType']]['ArtisanMsgs'] && $TradeLocData[$Trady['PitchLoc0']]['ArtisanMsgs']) ? 
                            "Trade_Artisan_Final_Invoice" : "Trade_Final_Invoice");
          $InvCode = Trade_Invoice_Code($Trad,$Trady);
          $details = [["Full payment to secure your trade stand at the $PLANYEAR festival",$Fee*100]];
          if ($InvoicedTotal) $details[] = ["Less previous invoice(s)",$InvoicedTotal];
          $type = "Full";
          if ($InvoicedTotal > $Dep) $type = "Change ";

          $ipdf = New_Invoice($Trad,$details, "Trade Stand $type", $InvCode, 1, ($DueDate?$DueDate:30));
          $NewState = $Trady['BookingState'] = $Trade_State['Balance Requested'];
          Send_Trader_Email($Trad,$Trady,$ProformaName,$ipdf);
        } else { // Old case - not right
          $NewState = $Trady['BookingState'] = $Trade_State['Quoted'];
          Send_Trader_Email($Trad,$Trady,'Trade_Quote');    
        }
      } else { // No need for a deposit - send update to trader
        $NewState = $Trady['BookingState'] = $Trade_State['Deposit Paid'];        
        Send_Trader_Email($Trad,$Trady,'Trade_Statement');  // For info no action required
      }
    }
    $Ychng = 1;
    break;

  case 'Resend' :
    $att = 0;
    $Invs = Get_Invoices(" OurRef='" . Sage_Code($Trad) . "'"," IssueDate DESC ");
    if ($Invs) $att = Get_Invoice_Pdf($Invs[0]['id']);

    Send_Trader_Email($Trad,$Trady,'Trade_Statement',$att); 
    echo "<h3>An Email has been sent " . (Access('Staff')?'':'to you') . " with a statement of where your booking is</h3>";
    break;
    
  case 'UnQuote' :
    Pay_Code_Remove(1,$Tid);
    $NewState = $Trade_State['Declined'];
    Send_Trader_Email($Trad,$Trady,'Trade_UnQuote');
    break;  
  
  case 'Invoices' :
    $Tid = $Trad['Tid'];
    $Invs = Get_InvoicesFor($Tid);

    if ($Invs) {
      $Now = time();
      $coln = 0;
      echo "<div class=tablecont><table id=indextable border>\n";
      echo "<thead><tr>";
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Our Ref</a>\n";
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'D')>Date Raised</a>\n";
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'D')>Date Due</a>\n";
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'D')>Date Paid</a>\n";
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Amount (left)</a>\n";
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>View</a>\n";
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Download</a>\n";
      echo "</thead><tbody>";
      foreach($Invs as $i=>$inv) {
        $id = $inv['id'];
        echo "<td>" . $inv['OurRef'] . '/' . $inv['id'];
        echo "<td>" . date('j/n/Y',$inv['IssueDate']);
        echo "<td>";
        if ($inv['Total'] > 0) {
          if  ($inv['DueDate'] < $Now && $inv['PaidTotal']<$inv['Total']) {
            echo "<span class=red>" . date('j/n/Y',$inv['DueDate']) . "</span>";
          } else {
            echo date('j/n/Y',$inv['DueDate'] );
          }
        }
        echo "<td>" . ($inv['PayDate']>0? date('j/n/Y',abs($inv['PayDate'])) : ($inv['PayDate']<0? "NA": ""));
        echo "<td>" . Print_Pence($inv['Total']);
        if ($inv['PaidTotal'] > 0 && $inv['PaidTotal'] != $inv['Total']) echo " (" . Print_Pence($inv['Total'] - $inv['PaidTotal']) . ")";
        $Rev = ($inv['Revision']?"R" .$inv['Revision']:"");
        echo "<td><a href=ShowFile?l=" . Get_Invoice_Pdf($id,'',$Rev) . ">View</a>";
        echo "<td><a href=ShowFile?D=" . Get_Invoice_Pdf($id,'',$Rev) . ">Download</a>";
        echo "\n";
      }
      echo "</table></div><p>";
      echo "<h2><a href=TraderPage?id=$Tid>Back to Trade Details</a></h2>";
      dotail();
    } else {
      echo "<h3>No Invoices Found</h3>";
    }
    break;
  case 'UnPaid':
    $PaidSoFar -= $data;
    $Trady['TotalPaid'] -= $data;
    $Ychng = 1;    
    $Dep = T_Deposit($Trad);
    $fee = $Trady['Fee'];
    $xtra = $data;
    if ($Trady['TotalPaid'] >= $fee) { // No change?
    } else if ($Trady['TotalPaid'] >= $Dep) {
      $NewState = $Trade_State['Deposit Paid'];
    } else {
      $NewState = $Trade_State['Accepted'];
    }
    break;
  
  case 'Chase':
    $att = 0;
    $Invs = Get_Invoices(" OurRef='" . Sage_Code($Trad) . "'"," IssueDate DESC ");
    if ($Invs) $att = Get_Invoice_Pdf($Invs[0]['id']);

    Send_Trader_Email($Trad,$Trady,'Trade_Chase1',$att); 
    break;
    
  case 'Pitch':
    Send_Trader_Email($Trad,$Trady,'Trade_PitchChange'); 
    break;
  
  case 'Moved':
    Send_Trader_Email($Trad,$Trady,'Trade_PitchMoved'); 
    break;
  

  default:
    break;
  }
/* TODO
   Need schedualled events:
     Send final invoices
     Overdue Invoices
   */


// var_dump($Ychng,$CurState,$NewState);

  if ($Tchng && $Action) Put_Trader($Trad);
  if ($Action && ($Ychng || $CurState != $NewState )) {
    $Trady['BookingState'] = $NewState; // Action test is to catch the moe errors
    $By = (isset($USER['Login'])) ? $USER['Login'] : 'Trader';
    $Trady['History'] .= "Action: $Hist $Action $xtra on " . date('j M Y H:i') . " by $By.\n";
    Put_Trade_Year($Trady);
  }
}

function Get_Taxis() {
  global $db;
  $cs = array();
  $res = $db->query("SELECT * FROM TaxiCompanies ORDER BY Authority,SN");
  if ($res) while ($c = $res->fetch_assoc()) $cs[] = $c;
  return $cs;
}

function Get_Taxi($id) {
  global $db;
  $res = $db->query("SELECT * FROM TaxiCompanies WHERE id=$id");
  if ($res) while($c = $res->fetch_assoc()) return $c;
}

function Put_Taxi($now) {
  $e=$now['id'];
  $Cur = Get_Taxi($e);
  return Update_db('TaxiCompanies',$Cur,$now);
}

function Get_OtherLinks($xtra='') {
  global $db;
  $cs = array();
  $res = $db->query("SELECT * FROM OtherLinks $xtra");
  if ($res) while($c = $res->fetch_assoc()) $cs[] = $c;
  return $cs;
}

function Get_OtherLink($id) {
  global $db;
  $res = $db->query("SELECT * FROM OtherLinks WHERE id=$id");
  if ($res) while($c = $res->fetch_assoc()) return $c;
}

function Put_OtherLink($now) {
  $e=$now['id'];
  $Cur = Get_OtherLink($e);
  return Update_db('OtherLinks',$Cur,$now);
}

function Trade_F_Action($Uid,$Action,$xtra='',$invid=0) { // Call from Invoicing 
  if (is_numeric($Uid)) {
    $Trad = Get_Trader($Uid);
    $Trady = Get_Trade_Year($Uid);
    Trade_Action($Action,$Trad,$Trady,1,'', $xtra,$invid); // OLD CODE
  } else if (preg_match('/(\D*)(\d*)\D$/',$Uid,$PCRec)) {
    $Tid = $PCRec[1];
    $Trad = Get_Trader($Tid);
    $Trady = Get_Trade_Year($Tid);
    Trade_Action($Action,$Trad,$Trady,1,'', $xtra,$invid);
    
  } else {
    // Unrecognised Uid
  }
}

function Trade_P_Action($Tid,$action,$xtra='') { // Call From Payment
  $Trad = Get_Trader($Tid);
  $Trady = Get_Trade_Year($Tid);
  Trade_Action($action,$Trad,$Trady,1,'', $xtra);
}

function Get_Traders_For($loc,$All=0 ) {
  global $db, $Trade_State,$YEAR;
  $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE " . 
        ($All? ("y.BookingState>= " . $Trade_State['Submitted'] ) : 
          ( "(y.BookingState=" . $Trade_State['Deposit Paid'] . " OR y.BookingState=" . $Trade_State['Balance Requested'] . " OR y.BookingState=" . $Trade_State['Fully Paid'] . ")" ) ) . 
         " AND t.Tid = y.Tid AND y.Year=$YEAR AND (y.PitchLoc0=$loc OR y.PitchLoc1=$loc OR y.PitchLoc2=$loc ) ORDER BY SN";

  $res = $db->query($qry);
  $Traders = [];
  if ($res) while ($trad = $res->fetch_assoc()) $Traders[] = $trad;
  return $Traders;
}


/* Get map size
   get scale 
   send the image
   setup the svg
   plot the pitches
   */

function Pitch_Map(&$loc,&$Pitches,$Traders=0,$Pub=0,$Scale=1,$Links=0) {  // Links 0:none, 1:traders, 2:Trade areas
  global $TradeTypeData,$Trade_State;
  
  if (!$loc['MapImage']) return;
  $scale=$Scale*$loc['Showscale'];
  $Mapscale = $loc['Mapscale'];
  $sp = $scale*100;
  $Factor = 20*$scale*$Mapscale;

  $TLocId = $loc['TLocId'];
  $FSize = 10*$scale;
  
  $Usage = [];$TT = [];$TNum = [];
  if ($Traders) {
    foreach ($Traders as $Trad) 
      for ($i=0; $i<3; $i++) 
        if ($Trad["PitchLoc$i"] == $TLocId) {
          $list = explode(',',$Trad["PitchNum$i"]);
          foreach ($list as $p) {
            $Usage[$p] = (isset($Usage[$p])?"CLASH!":$Trad['SN']);
            if ( $Trad['BookingState'] == $Trade_State['Deposit Paid'] || $Trad['BookingState'] == $Trade_State['Balance Requested'] || $Trad['BookingState'] == $Trade_State['Fully Paid'] ) {
              $TT[$p] = $Trad['TradeType'];
            } else {
              $TT[$p] = -1;
            }
            $TNum[$p] = $Trad['Tid'];
          }
        }
  }
  
  $ImgHt = 1200;
  $ImgWi = 700;
  $stuff = getimagesize($loc['MapImage']);
  if ($stuff) {
    $ImgHt = $stuff[1];
    $ImgWi = $stuff[0];
  }

//var_dump($TNum);

//  echo "scale=$scale sp=$sp Ht=$ImgHt Mapscale=$Mapscale <br>";
  echo "<div class=img-overlay-wrap>";
  echo "<img src=" . $loc['MapImage'] . " width=" . ($ImgWi*$scale) . ">";
  echo "<svg width=" . ($ImgWi*$scale) . " height=" . ($ImgHt*$scale) . ">";
  foreach ($Pitches as $Pitch) {
    $Posn = $Pitch['Posn'];
    $Name = '';
    $Lopen = 0;
    if (isset($Usage[$Posn])) $Name = $Usage[$Posn];
    if ($Pitch['Type']) $Name = $Pitch['SN'];
    if ($Links) {
      if ($Links == 1 && !$Pitch['Type']) {
        if (isset($TNum[$Posn])) {
          echo "<a href=#Trader" . $TNum[$Posn] . ">";
          $Lopen = 1;
        }
      } elseif ($Links == 2) {
        echo "<a href='TradeShow?SEL=" . $Pitch['SN'] . "'>";
        $Lopen = 1;
      }
    } else {
      echo "<a>";
      $Lopen = 1;
    }
    echo "<rect x=" . ($Pitch['X'] * $Factor) . " y=" . ($Pitch['Y'] * $Factor) . " width=" . ($Pitch['Xsize'] * $Factor) . " height=" . ($Pitch['Ysize'] * $Factor);
    echo " style='fill:" . ($Pitch['Type']?$Pitch['Colour']:($TT[$Posn]>=0?($Name?$TradeTypeData[$TT[$Posn]]['Colour']  : "yellow"):"white")) . ";stroke:black;";
    if ($Pitch['Angle']) echo "transform: rotate(" . $Pitch['Angle'] . "Deg);" ;

    echo "' id=Posn$Posn ondragstart=drag(event) ondragover=allow(event) ondrop=drop(event) />"; // Not used at present

    echo "<title>$Name</title>";

    echo "<text x=" . (($Pitch['X']+0.2) * $Factor)  . " y=" . (($Pitch['Y']+($Name?0.7:1.2)/$Mapscale) * $Factor);
    echo " style='";
    if ($Pitch['Angle']) echo "transform: rotate(" . $Pitch['Angle'] . "Deg);" ;
    echo "font-size:10px;'>";
    if (!$Pub) echo "#" . $Posn;
    if ($Name) {
    // Divide into Chunks each line has a chunk display Ysize chunks - the posn is a chunk,  chunk length = 3xXsize 
    // Chunking - split to Words then add words to full - if no words split word (hard)
    // Remove x t/a 
    // Lowercase 
    // Spilt at words of poss, otherwise at length (for now)
    
      $ChSize = floor($Pitch['Xsize']*($Pitch['Type']?18:34)*$Mapscale/($Pitch['Font']+10));
      $Ystart = ($Pub?0.6:1.2) *($Pitch['Type']?2:1);
      $MaxCnk = floor(($Pitch['Ysize']*2.5*$Mapscale) - ($Pub?1:2));
//      $Name = preg_replace('/.*t\/a (.*)/',
//      $Chunks = str_split($Name,$ChSize);
      $Chunks = ChunkSplit($Name,$ChSize,$MaxCnk);
      
      foreach ($Chunks as $i=>$Chunk) {
        if ($i>=$MaxCnk) break; 
 //       $Chunk = substr($Name,0,$ChSize);
        echo "<tspan x=" . (($Pitch['X']+0.2) * $Factor)  . " y=" . (($Pitch['Y']+$Ystart/$Mapscale) * $Factor) . 
             " style='font-size:" . (($Pitch['Type']?$FSize*2:$FSize)+$Pitch['Font']) . "px;'>$Chunk</tspan>";
        $Ystart += ($Pitch['Type']?1.2:0.6)*(10+$Pitch['Font']*2.1)/10;
      }
    }
    echo "</text>";
    if ($Lopen) echo "</a>";

  }   
  echo "</svg>";
  echo "</div>";
}


?>
