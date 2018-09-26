<?php

// For Book -> Confirm -> Deposit ->Pay , if class begins with a - then not used/don't list
$Trade_States = array('Not Submitted','Declined','Refunded','Cancelled','Submitted','Quoted','Accepted','Deposit Paid','Invoiced','Fully Paid','Wait List','Requote');
$Trade_State = array_flip($Trade_States);
$Trade_StateClasses = array('TSNotSub','TSDecline','-TSRefunded','TSCancel','TSSubmit','TSInvite','TSConf','TSDeposit','TSInvoice','TSPaid','TSWaitList','TSRequote');
$TS_Actions = array('Submit,Invite,Invite Better',
                'Resend,Submit',
                'Resend',
                'Resend,Submit',
                'Resend,Quote,Accept,Decline,Hold,Cancel',
                'Resend,Quote,Invite,Accept,Decline',
                'Resend,Dep Paid,Cancel',
                'Resend,Paid,Invoice,Cancel',
                'Resend,Paid,Cancel',
                'Resend,Cancel',
                'Resend,Accept,Decline,Cancel',
                'Resend,Quote,Cancel');

$Trader_Status = array('Alive','Banned','Not trading');
$Trader_State = array_flip($Trader_Status);
$ButExtra = array(
        'Accept'=>'',
        'Decline'=>'',
        'Submit'=>'',
        'Hold'=>'title="Hold for space available"',
        'Dep Paid'=>'title="Deposit Paid"',
        'Invoice'=>'',
        'Paid'=>'title=Full Fees Paid"',
        'Quote'=>'title="Send or repeat Quote email"',
        'Invite'=>'title="Send or repeat the Invitation Email"',
        'Invoiced'=>'Final Invoice Sent',
        'Cancel'=>'onClick="javascript:return confirm(\'are you sure you want to cancel this?\');"',
        'Resend'=>'Resend last email to trader',
        'Invite Better'=>'',
        'Artisan Invite'=>'',
        ); 
$ButTrader = array('Submit','Accept','Decline','Cancel','Resend'); // Actions Traders can do
$RestrictButs = array('Paid','Dep Paid'); // If !AutoInvoice or SysAdmin
$Trade_Days = array('Both','Saturday Only','Sunday Only');
$Prefixes = array ('in','in the','by the');
$TaxiAuthorities = array('East Dorset','Poole','Bournemouth');

function Get_Trade_Locs($tup=0) { // 0 just names, 1 all data
  global $db;
  $res = $db->query("SELECT * FROM TradeLocs ORDER BY SN ");
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
  global $db,$PLANYEAR;
  $full = array();
  $yr = ($tup ?"" :" WHERE Year=$PLANYEAR ");
  $res = $db->query("SELECT * FROM Sponsors $yr ORDER BY SN ");
  if ($res) while ($spon = $res->fetch_assoc()) $full[] = $spon;
  if ($tup==0 && empty($full)) {
    $yr = " WHERE Year=" . ($PLANYEAR-1);
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

function Get_Traders_Coming($type=0) { // 0=names, 1=all
  global $db,$YEAR,$Trade_State;
  $data = array();
  $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Tid = y.Tid AND y.Year=$YEAR AND y.BookingState>=" . $Trade_State['Deposit Paid'] .
                " ORDER BY SN";
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
  $qry = "SELECT * FROM Trade WHERE Status=0 ORDER BY SN";
  $res = $db->query($qry);
  if (!$res || $res->num_rows == 0) return 0;
  while ($tr=$res->fetch_assoc()) {
    $data[$tr['Tid']] = ($type?$tr:$tr['SN']);
  }
  return $data;
}

function Put_Trader(&$now) {
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
        'Photo'=>'Give URL of Image to use or upload one',
        'TradeType'=>'Fees depend on trade type, pitch size and location',
        'BookingState'=>'ONLY change this if you are fixing a problem, use the state change buttons',
        'PublicInfo'=>'Information in this section may be used on the public website if you tick the "Do you want to appear on the Folk Festival Website?" box', 
        'PrivateInfo'=>'Information in this section is only visible to you and the revelent members of the festival, you can amend this at any time',

  );
  Set_Help_Table($t);
}

function Default_Trade($id) {
  global $YEAR;
  return array('Year'=>$YEAR,'Tid'=>$id,'PitchSize0'=>'3Mx3M','Power0'=>0,'BookingState'=>0);

}

function Show_Trader($Tid,&$Trad,$Form='Trade.php',$Mode=0) { // Mode 1 = Ctte
  global $YEAR,$ADDALL,$Mess,$Action,$Trader_Status,$TradeTypeData,$TradeLocData;
  Set_Trade_Help();

  if (isset($Trad['Photo']) && $Trad['Photo']) echo "<img class=floatright src=" . $Trad['Photo'] . " height=80>\n";
  if ($Tid > 0) echo "<input  class=floatright type=Submit name='Update' value='Save Changes' form=mainform>";
  if ($Mode && isset($Trad['Email']) && strlen($Trad['Email']) > 5) {
    echo "If you click on the " . linkemailhtml($Trad,'Trade');
    echo ", press control-V afterwards to paste the <button type=button onclick=Copy2Div('Email$Tid','SideLink$Tid')>standard link</button>";
    echo "<p>\n";
  }

  $Adv = '';
  $Imp = '';
  if ($Mode) {
    echo "<span class=NotSide>Fields marked are not visible to Trader.</span>";
    echo "  <span class=NotCSide>Marked are visible if set, but not changeable by Trader.</span>";
  } else {
    $Adv = 'class=Adv';
  }

//********* PUBLIC

  if (!isset($Trad['TradeType']) || ($Trad['TradeType'] == 0)) $Trad['TradeType'] = 1;

  echo "<form method=post id=mainform enctype='multipart/form-data' action=$Form>";
  Register_AutoUpdate('Trader',$Tid);  
  echo "<table width=90% border class=SideTable>\n";
    echo "<tr><th colspan=8><b>Public Information</b>" . Help('PublicInfo');
    echo "<tr>" . fm_text('Business Name', $Trad,'SN',2,'','autocomplete=off id=SN');
    echo "<tr>";
      if (isset($Trad['Website']) && strlen($Trad['Website'])>1) {
        echo fm_text(weblink($Trad['Website']),$Trad,'Website');
      } else {
        echo fm_text('Website',$Trad,'Website');
      };
      echo "<td colspan=1>" . fm_checkbox('Do you want to appear on<br>the Folk Festival Website?',$Trad,'ListMe');
      echo fm_text('Image',$Trad,'Photo',1,'style="min-width:145;"'); 
      if ($Tid >0) {
        echo "<td colspan=3>Select file to upload:";
        echo "<input type=file $ADDALL name=PhotoForm id=PhotoForm onchange=document.getElementById('PhotoButton').click()>";
        echo "<input hidden type=submit name=Action value=Photo id=PhotoButton>";
        if ($Mess && $Action == 'Photo') echo "<br>$Mess\n";
      } else {
        echo "<td colspan=3>You can upload a photo once you have created your record\n";
      }
    echo "<tr>" . fm_textarea('Products Sold <span id=DescSize></span>',$Trad,'GoodsDesc',7,1,
                        'maxlength=500 oninput=SetDSize("DescSize",500,"GoodsDesc")');     

//********* PRIVATE

    echo "<tr><th colspan=8><b>Private Information</b>" . Help('PrivateInfo');
    echo "<tr>";
      echo "<td>Trade Type:" . help('TradeType') . "<td colspan=7>";
      foreach ($TradeTypeData as $i=>$d) {
        if ($d['Addition']) continue;
        echo " <div class=KeepTogether style='background:" . $d['Colour'] . ";'>" . $d['SN'] . ": ";
        echo " <input type=radio name=TradeType $ADDALL value=$i ";
        if ($Trad['TradeType'] == $i) echo " checked";
        echo " onclick='SetTradeType(" . $d['NeedPublicHealth'] . "," . $d['NeedCharityNum'] . "," .
                                        $d['NeedInsurance'] . "," . $d['NeedRiskAssess'] . ',"' . $d['Description'] . '","' . 
                                        $d['Colour'] . "\")'"; // not fm-Radio because of this line
        echo " id=TradeType$i oninput=AutoRadioInput('TradeType',$i) ";
        echo ">&nbsp;</div>\n ";
      }
      echo "<br clear=all><div id=TTDescription style='background:" . $TradeTypeData[$Trad['TradeType']]['Colour'] . ";'>" . 
        $TradeTypeData[$Trad['TradeType']]['Description'] . "</div>\n";
    echo "<tr>" . fm_text('<span id=ContactLabel>Contact</span>',$Trad,'Contact');
      echo fm_text1('Email',$Trad,'Email',2);
      echo fm_text('Phone',$Trad,'Phone');
      echo fm_text('Mobile',$Trad,'Mobile',1,$Imp,'onchange=updateimps()') . "\n";
    echo "<tr>" . fm_text('Address',$Trad,'Address',5,$Imp,'onchange=updateimps()');
      echo fm_text('Post Code',$Trad,'PostCode')."\n";
    echo "<tr class=PublicHealth " . ($TradeTypeData[$Trad['TradeType']]['NeedPublicHealth']?'':'hidden') . ">" ;
      echo fm_text("Registered with which Local Authority ",$Trad,'PublicHealth',2,'colspan=2');
    echo "<tr><td>Are you a Wimborne<td>" . fm_checkbox('BID Levy Payer',$Trad,'BID') . "<td>" . fm_checkbox('Chamber of Commerce Member',$Trad,'ChamberTrade');
    if ($Mode) echo "<td>" . fm_checkbox('Previous Festival Trader',$Trad,'Previous');
      echo fm_text('Charity Number',$Trad,'Charity',1,'class=Charity ' . ($TradeTypeData[$Trad['TradeType']]['NeedCharityNum']?'':'hidden'));
      if ($Mode) echo "<td class=NotSide colspan=2>" . fm_radio("",$Trader_Status,$Trad,'Status','',0);
    if (Access('SysAdmin') && isset($Trad['AccessKey'])) {
      echo "<tr>";
        if ($Tid > 0) echo "<td class=NotSide>Id: $Tid";
        echo fm_nontext('Access Key',$Trad,'AccessKey',3,'class=NotSide','class=NotSide'); 
        if (isset($Trad['AccessKey'])) {
          echo "<td class=NotSide><a href=Direct.php?id=$Tid&t=trade&key=" . $Trad['AccessKey'] . ">Use</a>" . help('Testing');
        }
      echo "  <button name=Action value=Delete onClick=\"javascript:return confirm('are you sure you want to delete this?');\">Delete</button>\n";
    } 
    if (Access('Committee',"Finance")) {
      include_once("InvoiceLib.php");
      if (isset($Trad['SN'])) $Scode = Sage_Code($Trad);
      echo fm_text("Sage Code",$Trad,'SageCode',1,'class=NotSide','class=NotSide');
    }
    echo fm_hidden("Tid", $Tid);
    echo fm_hidden("Id", $Tid);

    if ($Mode) {
      echo "<tr>" . fm_textarea('Notes',$Trad,'Notes',7,2,'class=NotSide','class=NotSide');
    }
  echo "</table>";
}

function Trade_TandC() {
  echo "<h2>Terms and Conditions</h2>\n";
  echo "<ul>";
    echo "<li>You, the stallholder, must supply a gazebo and table, we are unable to locate your stand under cover (except for a limited number of Artisan traders).\n";
    echo "<li>Generators are not permitted unless previously arranged and agreed with the festival organisers.\n";
    echo "<li>You will be responsible for the health and safety of the general public, yourself and others around you and ";
        echo "must co-operate with festival organisers and supervisors at all times.\n";
    echo "<li>The festival organisers reserve the right to refuse trade stand applications and without explanation.\n";
    echo "<li>The festival organisers accept no liability for lost, damaged or stolen property.\n";
    echo "<li>All information specified on this form (other than that show as public) is treated as strictly confidential and will be held securely.\n";
    echo "<li>Any persons sleeping in trade stands will result in removal of the stand immediately.\n";
    echo "<li>You will be solely responsible for removal of any rubbish and for cleaning your stand area to its original condition or paying for any damage caused.\n";
  echo "</ul><p>";
}

function Show_Trade_Year($Tid,&$Trady,$year=0,$Mode=0) {
  global $YEAR,$PLANYEAR,$MASTER,$Trade_States,$Mess,$Action,$ADDALL,$Trade_StateClasses,$InsuranceStates,$Trade_State,$Trade_Days;
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

  echo "<table width=90% border class=SideTable>\n";
  echo fm_hidden('Year',$year);
  if (isset($Trady['TYid'])) echo fm_hidden('TYid',$Trady['TYid']);

  if ($Mode) {
    echo "<td class=NotCSide>Booking State:" . help('BookingState') . "<td colspan=2 class=NotCSide>";
      foreach ($Trade_States as $i=>$ts) {
        $cls = $Trade_StateClasses[$i];
        if( preg_match('/^-/',$cls)) continue;
        echo " <div class='KeepTogether $cls'>$ts: ";
        echo " <input type=radio name=BookingState $ADDALL value=$i ";
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
          echo " class=" . $Trade_StateClasses[$stat] . ">" . $Trade_States[$stat];
        }
    }
  }
  
  echo "<tr><td>Days:<td>" . fm_select($Trade_Days,$Trady,'Days');
  echo "<tr><td>Requested Pitch Sizes, 3x3M is default" . Help('PitchSize');
  echo "<td>Power Requirements" . Help('Power') . "<br>3 Amps - Lighting, 13 Amps - 1 Kettle...";
  if (isset($Trady['PitchLoc0']) && $Trady['PitchLoc0']) {
    echo "<td>Location<td>Pitch Number";
  } else {
    echo "<td>Location (When Assigned)<td>Pitch Number";
  }
  for ($i = 0; $i < 3; $i++) {
    $pwr = (isset($Trady["Power$i"])?$Trady["Power$i"]:0);
    echo "<tr>" . fm_text1("",$Trady,"PitchSize$i");
    echo "<td>None: <input type=radio name=PowerType$i value=0 onclick=PowerChange(0,$i) " . ($pwr==0?"checked ":"") . "> ";
    echo "My own Euro 4 Silent Generator: <input type=radio name=PowerType$i value=1 onclick=PowerChange(1,$i) " . ($pwr<0?"checked ":"") . "><br>";
    echo "<input type=radio name=PowerType$i hidden id=PowerTypeRequest$i value=2>Requested: <input type=number id=Power$i name=Power$i onchange=PowerChange(2,$i) " . 
        ($pwr>0?" value=" . $Trady["Power$i"] : "") . " min=0 max=1000>Amps";
    if ($Mode) {
      echo "<td class=NotCSide>" . fm_select($TradeLocs,$Trady,"PitchLoc$i",1,'class=NotCSide');
      echo fm_number1("",$Trady,"PitchNum$i",'class=NotCSide','class=NotCSide');
    } else {
      echo "<td>";
      if ($Trady["PitchLoc$i"]) {
        echo $TradeLocs[$Trady["PitchLoc$i"]];
        echo fm_hidden("PitchLoc$i",$Trady["PitchLoc$i"]);
        echo "<td>";
        if ($Trady["PitchNum$i"]) echo $Trady["PitchNum$i"] . " <a href=ShowTradeMap.php?l=" . $Trady["PitchLoc$i"] . ">Map</a>";
      } else {
        echo "<td>";
      }
    }
  }
  echo "<tr>";
    if ($Mode) {
      echo fm_text("Total Fee, put -1 for free",$Trady,'Fee',1,'class=NotCSide','class=NotCSide');
      echo fm_text("Paid so far",$Trady,'TotalPaid',1,'class=NotCSide','class=NotCSide');
    } else {
      echo "<td>Total Fee:<td>";
      if ($Trady['Fee']<0) {
        echo "Free";
      } else if ($Trady['Fee'] == 0 ) {
        echo "To be set";
      } else {
        echo "&pound;" . $Trady['Fee'];
        echo "<td>Paid so far: &pound;" . $Trady['TotalPaid'];
      }
    }

// Notes, Insurance upload, Risk Assess inline/upload, download, Deposit Required, 
// State (Requesting, Accepted, Declined, Invoiced, Deposit Paid, Rejected, Paid, Ammended) Store when Accept
// Email link, and confamation, have means to request new link (use email address known), import existing dataZZ

// Insurance
  echo "<tr>";
    if ($Tid > 0) {
      echo "<td colspan=1>Select insurance file to upload:";
      echo "<input type=file $ADDALL name=InsuranceForm id=InsuranceForm onchange=document.getElementById('InsuranceButton').click()>";
      echo "<input hidden type=submit name=Action value=Insurance id=InsuranceButton>";

      if ($Mode) {
        echo "<td class=NotCSide colspan=2>" . fm_radio('Insurance',$InsuranceStates,$Trady,'Insurance','',0);
        if (isset($Trady['Insurance']) && $Trady['Insurance']) {
          $files = glob("Insurance/$YEAR/Trade/$Tid.*");
          $Current = $files[0];
          $Cursfx = pathinfo($Current,PATHINFO_EXTENSION );
          echo " <a href=ShowFile.php?l=Insurance/$YEAR/Trade/$Tid.$Cursfx>View</a>";
        }
      } else {
        $tmp['Ignored'] = $Trady['Insurance'];
        echo "<td>" . fm_checkbox('Insurance Uploaded',$tmp,'Ignored','disabled');
        echo "<td colspan=2>You <b>must</b> have a copy available with you during the festival weekend\n";
      }

      if ($Mess && $Action == 'Insurance') echo "<td colspan=2>$Mess\n"; 
    } else {
      echo "<td colspan=4>You can upload your insurance once your record has been created\n";
    }
// Risc Assessment
  echo "<tr><td>Risk Assessment<td>Coming soon";

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
        echo $hist;
      }
    }
  }
  if ($Mode) {
    if (isset($Trady['SentInvite']) && $Trady['SentInvite']) {
      echo "<tr>"; 
      echo fm_date('Invite Sent',$Trady,'SentInvite');
    }
  }
  if (file_exists("testing") || Access('SysAdmin')) echo "<tr><td class=NotSide>Debug<td colspan=6 class=NotSide><textarea id=Debug></textarea>";
  echo "</table>\n";
}

function Get_Trade_Details(&$Trad,&$Trady) {
  global $Trade_Days,$TradeLocData,$TradeTypeData;

//  $Body  = "\nWimborne Minster Folk festival Trading application\n";
  $Body = "\nBusiness: " . $Trad['SN'] . "\n";
  $Body .= "Goods: " . $Trad['GoodsDesc'] . "\n\n";
  $Body .= "Type: " . $TradeTypeData[$Trad['TradeType']]['SN'] . "\n\n";
  if ($Trad['Website']) $Body .= "Website: " . weblink($Trad['Website'],$Trad['Website']) . "\n\n";
  $Body .= "Contact: " . $Trad['Contact'] . "\n";
  if ($Trad['Phone']) $Body .= "Phone: " . $Trad['Phone'] . "\n";
  if ($Trad['Mobile']) $Body .= "Mobile: " . $Trad['Mobile'] . "\n";
  $Body .= "Email: <a href=mailto:" . $Trad['Email'] . ">" . $Trad['Email'] . "</a>\n";
  $Body .= "Address: " . $Trad['Address'] . "\n";
  $Body .= "PostCode: " . $Trad['PostCode'] . "\n\n";
  if ($Trad['Charity']) $Body .= "Charity: " . $Trad['Charity'] . "\n";
  if ($Trad['PublicHealth']) $Body .= "Local Authority: " . $Trad['PublicHealth'] . "\n";
  if ($Trad['BID']) $Body .= "BID Member: Yes\n";
  if ($Trad['ChamberTrade']) $Body .= "Chamber of Trade Member: Yes\n";
  if ($Trad['Previous']) $Body .= "Previous Trader: Yes\n";
  $Body .= "\n\n";

  $Body .= "For " . $Trady['Year'] .":\n";
  $Body .= "Days: " . $Trade_Days[$Trady['Days']] . "\n";
  $Body .= "Pitch:" . $Trady['PitchSize0'];
  if ($Trady['PitchLoc0']) $Body .= " at " . $TradeLocData[$Trady['PitchLoc0']]['SN'];
  if ($Trady['PitchNum0']) $Body .= "Pitch Number "  . $Trady['PitchNum0'];
  if ($Trady['Power0']) $Body .= " with " . ($Trady["Power0"]> 0 ? $Trady['Power0'] . " Amps\n" : " own Euro 4 silent generator\n");

  if ($Trady['PitchSize1']) {
    $Body .= "\nPitch 2:" . $Trady['PitchSize1'];
    if ($Trady['PitchLoc1']) $Body .= " at " . $TradeLocData[$Trady['PitchLoc1']]['SN'];
    if ($Trady['PitchNum1']) $Body .= "Pitch Number "  . $Trady['PitchNum1'];
    if ($Trady['Power1']) $Body .= " with " . $Trady['Power1'] . " Amps\n";
  }
  if ($Trady['PitchSize2']) {
    $Body .= "\nPitch 3:" . $Trady['PitchSize2'];
    if ($Trady['PitchLoc2']) $Body .= " at " . $TradeLocData[$Trady['PitchLoc2']]['SN'];
    if ($Trady['PitchNum2']) $Body .= "Pitch Number "  . $Trady['PitchNum2'];
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
  
  if ($Invs[0]['PayDate']) {
    $Str .= "The most recently paid invoice is attached for your records.<p>";
  } else {
    $Str .= "There is an outstanding invoice for " . Print_Pence($Invs[0]['Total']) . " (attached)<p>";
  }
  return $Str;
}

function Trader_Details($key,&$data,$att=0) {
  global $Trade_Days,$TradeLocData,$TradeTypeData;
  $Trad = &$data[0];
  if (isset($data[1])) $Trady = &$data[1];
  $Tid = $Trad['Tid'];
  switch ($key) {
  case 'WHO':  return $Trad['Contact']? firstword($Trad['Contact']) : $Trad['SN'];
  case 'LINK': return "<a href=https://" . $_SERVER['HTTP_HOST'] . "/int/Direct.php?t=Trade&id=$Tid&key=" . $Trad['AccessKey'] . "<b>link</b></a>";
  case 'WMFFLINK': return "<a href=http://wimbornefolk.co.uk/int/Trade.php?id=$Tid><b>link</b></a>";
  case 'HERE':
  case 'REMOVE': return "<a href=https://" . $_SERVER['HTTP_HOST'] . "/int/Remove.php?t=Trade&id=$Tid&key=" . $Trad['AccessKey'] . "<b>remove</b></a>";
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
  case 'BALANCE': return ($Trady['Fee'] - T_Deposit($Trad));
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
                        'Final balacing payment has been invoiced but not paid',
                        'Fully Paid',
                        'On a wait list',
                        'Awaiting a requote after change'][$Trady['BookingState']] . "<P>";
  case 'BACSREF':
    preg_match('/(\d*)\.pdf/',$att,$mtch);
    return Sage_Code($Trad) . "/" . (isset($mtch[1]) ? $mtch[1] : '0000' );
  case 'FINANCIAL': return Trade_Finance($Trad,$Trady);
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
  global $PLANYEAR,$MASTER_DATA;
  include_once("Email.php");
  Email_Proforma([$Trad['Email'],$Trad['Contact']],$messcat,$MASTER_DATA['FestName'] . " $PLANYEAR and " . $Trad['SN'],'Trader_Details',[&$Trad,&$Trady],'TradeLog',$att);
}

function Send_Trader_Simple_Email(&$Trad,$messcat='Link',$att='') {
  global $PLANYEAR,$MASTER_DATA;
  include_once("Email.php");
  Email_Proforma([$Trad['Email'],$Trad['Contact']],$messcat,$MASTER_DATA['FestName'] . " $PLANYEAR and " . $Trad['SN'],'Trader_Details',[&$Trad],'TradeLog',$att);
}

function Send_Trade_Finance_Email(&$Trad,&$Trady,$messcat,$att=0) {
  global $PLANYEAR,$MASTER_DATA;
  include_once("Email.php");

  Email_Proforma("treasurer@" . $MASTER_DATA['HostURL'],$messcat,$MASTER_DATA['FestName'] . " $PLANYEAR and " . $Trad['SN'],'Trader_Details',[&$Trad,&$Trady],'TradeLog',$att);
}

function Send_Trade_Admin_Email(&$Trad,&$Trady,$messcat,$att=0) {

  global $PLANYEAR,$MASTER_DATA;
  include_once("Email.php");

  Email_Proforma("trade@" . $MASTER_DATA['HostURL'],$messcat,$MASTER_DATA['FestName'] . " $PLANYEAR and " . $Trad['SN'],'Trader_Admin_Details',[&$Trad,&$Trady],'TradeLog',$att);
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
}

function Validate_Trade($Mode=0) { // Mode 1 for Staff Submit, less stringent
  global $TradeTypeData;
      $proc = 1;
      if (!isset($_POST['SN']) || strlen($_POST['SN']) < 3 ) {
        echo "<h2 class=ERR>No Business Name Given</h2>\n";
        $proc = 0;
      }
      
      if ($Mode == 0 && ($TradeTypeData[$_POST['TradeType']]['TOpen'] == 0)) {
        echo "<h2 class=ERR>Sorry that category is full for this year</h2>\n";
        $proc = 0;
      }

      if (!isset($_POST['Contact']) || strlen($_POST['Contact']) < 4 ) {
        echo "<h2 class=ERR>No Contact Name Given</h2>\n";
        $proc = 0;
      }
      if ((!isset($_POST['Phone']) && !isset($_POST['Mobile'])) || (strlen($_POST['Phone']) < 6 && strlen($_POST['Mobile']) < 6)) {
        echo "<h2 class=ERR>No Phone/Mobile Numbers Given</h2>\n";
        $proc = 0;
      }
      if (!isset($_POST['Email']) || strlen($_POST['Email']) < 8) {
        if ($Mode) {
          echo "<h2 class=MERR>No Email Given</h2>\n";
        } else {
          echo "<h2 class=ERR>No Email Given</h2>\n";
          $proc = 0;
        }
      }
      if (!isset($_POST['Address']) || strlen($_POST['Address']) < 10) {
        if ($Mode) {
          echo "<h2 class=MERR>No Address Given</h2>\n";
        } else {
          echo "<h2 class=ERR>No Address Given</h2>\n";
              $proc = 0;
        }
      }
      if (!isset($_POST['GoodsDesc'])) {
        echo "<h2 class=ERR>No Products Description Given</h2>\n";
        $proc = 0;
      } else if ((strlen($_POST['GoodsDesc']) < 30) && ($Mode == 0)){
        echo "<h2 class=ERR>The Product Description is too short</h2>\n";
        $proc = 0;
      }
      if ((!isset($_POST['PublicHealth']) || strlen($_POST['PublicHealth']) < 5) && ($TradeTypeData[$_POST['TradeType']]['NeedPublicHealth']) && ($Mode == 0)) {
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
  return 0;   
}


function Trade_Main($Mode,$Program,$iddd=0) {
// Mode 0 = Traders, 1 = ctte, Program = Trade/Trader$iddd if set starts it up, with that Tid

  global $YEAR,$PLANYEAR,$Mess,$Action,$Trade_State,$Trade_States,$USER,$TS_Actions,$ButExtra,$ButTrader,$RestrictButs;
  global $TradeTypeData,$TradeLocData;
  include_once("DateTime.php"); 
  echo '<div class="content"><h2>Add/Edit Trade Stall Booking</h2>';

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

    for ($i=0;$i<3;$i++) if ($_POST["PowerType$i"]==1) $_POST["Power$i"] = -1;
    Clean_Email($_POST{'Email'});
    Clean_Email($_POST{'AltEmail'});
    $proc = Validate_Trade($Mode);

//echo "Trade Validation: $proc <br>;
    if ($Tid > 0) {                                 // existing Trader 
      $Trad = Get_Trader($Tid);
      if ($Trad) {
        $Tradyrs = Get_Trade_Years($Tid);
        if (isset($Tradyrs[$PLANYEAR])) $Trady = $Tradyrs[$PLANYEAR];
      } else {
        echo "<h2 class=ERR>Could not find Trader $Tid</h2>\n";
      }

      if (isset($_POST{'NewAccessKey'})) $_POST{'AccessKey'} = rand_string(40);

      Update_db_post('Trade',$Trad);
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
          if (!$Mess && $same == 0 && $Trady['BookingState'] >= $Trade_State['Submitted']) {
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
    } else { // New trader 
      $_POST['AccessKey'] = rand_string(40);
      $Tid = Insert_db_post('Trade',$Trad,$proc);
      if ($Tid) {
        Insert_db_post('TradeYear',$Trady,$proc);
        $Trady = Get_Trade_Year($Trad['Tid']);
      }
      if ($proc && isset($_POST['ACTION'])) Trade_Action($_POST['ACTION'],$Trad,$Trady,$Mode);
    }
    if ($proc && isset($_POST['Submit'])) Submit_Application($Trad,$Trady,$Mode);

  } elseif (isset($_GET{'id'})) { // Link from elsewhere 
    $Tid = $_GET{'id'};
    $Trad = Get_Trader($Tid);
    if ($Trad) {
      $Tradyrs = Get_Trade_Years($Tid);
      if (isset($Tradyrs[$YEAR])) {
        $Trady = $Tradyrs[$YEAR];
      } else {
        $Trady = Default_Trade($Tid);
      }
    } else {
      echo "<h2 class=ERR>Could not find Trader $Tid</h2>\n";
    }
  } else {
    $Tid = -1;
    $Trad['TradeType'] = 1;
  }
  if (!isset($Trady)) $Trady = Default_Trade($Tid);

  Show_Trader($Tid,$Trad,$Program,$Mode);
  Show_Trade_Year($Tid,$Trady,$YEAR,$Mode);

  if ($Mode == 0) Trade_TandC();
  if ($Tid > 0) {
    if (!isset($Trady['BookingState'])) { $Trady['BookingState'] = 0; $Trady['Fee'] = 0; }
    if (Access('SysAdmin')) {
      echo "<div class=floatright>";
      echo "<input type=Submit id=smallsubmit name='NewAccessKey' value='New Access Key'>";
      if (!Feature("AutoInvoices") && $Trady['BookingState'] >= $Trade_State['Accepted']) echo "<input type=Submit id=smallsubmit name='ACTION' value='Resend Finance'>";
      echo "</div>\n";
    }
    echo "<Center>";
    echo "<input type=Submit name='Update' value='Save Changes'>\n";
//    if (!isset($Trady['BookingState']) || $Trady['BookingState']== 0) echo "<input type=Submit name=Submit value='Save Changes and Submit Application'>";

    $Act = $TS_Actions[$Trady['BookingState']];
    if ($Act ) {
      $Acts = preg_split('/,/',$Act); 
//      if ($TradeTypeData[$Trad['TradeType']]['ArtisanMsgs']) {
//        if ($TradeLocData[$Trady['PitchLoc0']]['ArtisanMsgs']) $dummy=1;
//      }
//echo $Trad['TradeType'];
      if ($TradeTypeData[$Trad['TradeType']]['ArtisanMsgs'] && isset($Trady['PitchLoc0']) && $TradeLocData[$Trady['PitchLoc0']]['ArtisanMsgs']) $Acts[] = 'Artisan Invite';
      foreach($Acts as $ac) {
        if ($Mode==0 && !in_array($ac,$ButTrader)) continue;
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
            if ($Trady['PitchLoc0'] == 0 || $Trady['Fee'] == 0) continue 2;
            break;
          default:
        }
        echo "<input type=submit name=ACTION value='$ac' " . $ButExtra[$ac] . " >";
      }
    }
    echo "</center>\n";
  } else { 
    echo "<Center>";
    echo "<input type=Submit name=ACTION value='Create'>\n";
    echo "<input type=Submit name=ACTION value='Create and Submit Application'>";
    echo "</center>\n";
  }
  echo "</form>\n";

  if ($Mode && $Tid>0) {
    $Invs = Get_Invoices(" OurRef='" . Sage_Code($Trad) . "'"," IssueDate DESC ");
    echo "<h2><a href=ListCTrade.php>List Traders Coming</a> ";
//    var_dump($Invs);
    if ($Invs) echo ", <a href=InvoiceManage.php?FOR=$Tid>Show All Invoices for " . $Trad['SN'] . "</a>";
    echo "</h2>";
  }
}

function Trade_Date_Cutoff() { // return 0 - normal, 30, full payment (normal duration), >0 = Days left to trade stop (full payment)
  global $MASTER;
  $Now = time();
  if ($MASTER['TradeMainDate'] > $Now) return 0;
  $DaysLeft = intdiv(($MASTER['TradeLastDate'] - $Now),24*60*60);
  if ($DaysLeft > 30) $DaysLeft = 30;
  if ($DaysLeft < 0) $DaysLeft = 1;
  return $DaysLeft;
}

function Trade_Invoice_Code(&$Trad,&$Trady) {
  global $TradeLocData,$TradeTypeData;
  $InvCode = $TradeLocData[$Trady['PitchLoc0']]['InvoiceCode'];
  if ($InvCode == 0) $InvCode = $TradeTypeData[$Trad['TradeType']]['SalesCode'];
//  echo "<p>Returning Invoice Code $InvCode<p>";
  return $InvCode;
}

function Trade_Deposit_Invoice(&$Trad,&$Trady,$Full='Full',$extra='') {
  global $Trade_Days,$PLANYEAR;
  if (! Feature("AutoInvoices")) return 0;
  
  $Dep = T_Deposit($Trad);
  $PaidSoFar = (isset($Trady['TotalPaid']) ? $Trady['TotalPaid'] : 0);
  if ($PaidSoFar) {
    $Dep -= $PaidSoFar;
    if ($Dep < 0) $Dep = 0;
  }
  $InvCode = Trade_Invoice_Code($Trad,$Trady);
  $DueDate = Trade_Date_Cutoff();
  if ($DueDate == 0) {
//      if (Now < Main invoice date, Due = 30, else invoice full amount (if Now < 30 before cut date, Due = 30, else Due = CutDate - now
    $ipdf = New_Invoice($Trad,
                        ["Deposit for trade stand at the $PLANYEAR festival",$Dep*100],
                        'Trade Stand Deposit',
                        $InvCode);
  } else {
    $details = ["$Full fees for trade stand at the $PLANYEAR festival",$Trady['Fee']*100];
    if ($extra) $details = [$details,$extra];
    $ipdf = New_Invoice($Trad,
                        $details,
                        'Trade Stand Full Charge',
                        $InvCode, 1, $DueDate);
  }
  return $ipdf;
}

// Highly recursive set of actions - some trigger others amt = paid amount (0 = all)
function Trade_Action($Action,&$Trad,&$Trady,$Mode=0,$Hist='',$data='') {
  global $Trade_State,$TradeTypeData,$USER,$TradeLocData,$PLANYEAR;
  include_once("InvoiceLib.php");
  $Tchng = $Ychng = 0;
  $PaidSoFar = (isset($Trady['TotalPaid']) ? $Trady['TotalPaid'] : 0);
  $CurState = $NewState = (isset($Trady['BookingState']) ? $Trady['BookingState'] : 0);
  $xtra = '';

  switch ($Action) {
  case 'Create' :
    break;

  case 'Create and Submit Application':
  case 'Submit' :
    if (isset($Trady['Fee']) && $Trady['Fee']) {
      Trade_Action('Accept',$Trad,$Trady,$Mode,"$Hist $Action");
      return;
    } else {
      echo "This takes a few seconds, please be patient.<p>";
      $NewState = $Trade_State['Submitted'];
      Submit_Application($Trad,$Trady,$Mode);
    }
    break;

  case 'Accept' :
    $Dep = T_Deposit($Trad);
    $NewState = $Trade_State['Accepted'];
    if ($Dep <= $PaidSoFar) {
      Trade_Action('Dep Paid',$Trad,$Trady,$Mode,"$Hist $Action");
      Send_Trader_Email($Trad,$Trady,'Trade_AcceptNoDeposit');
      return;
    }

    $ProformaName = (($TradeTypeData[$Trad['TradeType']]['ArtisanMsgs'] && $TradeLocData[$Trady['PitchLoc0']]['ArtisanMsgs']) ? "Trade_Artisan_Accept" : "Trade_Accepted");
    $ipdf = Trade_Deposit_Invoice($Trad,$Trady);
   
    if ($ipdf) {
      $DueDate = Trade_Date_Cutoff();
      Send_Trader_Email($Trad,$Trady,$ProformaName . ($DueDate?"_FullInvoice":"_Invoice"),$ipdf);
    } else {
      Send_Trader_Email($Trad,$Trady,$ProformaName);
      Send_Trade_Finance_Email($Trad,$Trady,'Trade_RequestDeposit');
    }
    break;
    
  case 'Invoice':
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
      $NewState = $Trade_State['Invoiced'];
    }
    break;

  case 'Resend Finance':
    Send_Trade_Finance_Email($Trad,$Trady,'Trade_RequestDeposit');  // Only used when no auto invoices
    break;

  case 'Decline' :
    $NewState = $Trade_State['Declined'];
    $att = 0;
    if ($CurState == $Trade_State['Accepted']) { // Should not be here ...
      // Is there an invoice ? If so credit it and attach credit note
      $Invs = Get_Invoices(" PayDate=0 AND OurRef='" . Sage_Code($Trad) . "'"," IssueDate DESC ");
      if ($Invs) $att = Invoice_Credit_Note($Invs[0]);
    }
    Send_Trader_Email($Trad,$Trady,'Trade_Decline',$att);
    break;

  case 'Hold' :
    $NewState = $Trade_State['Wait List'];
    Send_Trader_Email($Trad,$Trady,'Trade_Hold');
    break;

  case 'Dep Paid' :
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
        if ($DueDate) Trade_Action('Invoice',$Trad,$Trady,$Mode,"$Hist $Action");
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
    if ($Trady['TotalPaid'] >= $fee) { 
      $NewState = $Trade_State['Fully Paid'];
    } else if ($Trady['TotalPaid'] >= $Dep && $CurState == $Trade_State['Accepted']) $NewState = $Trade_State['Deposit Paid'];
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
    $att = 0;

    // Is there an invoice ? If so credit it and attach credit note
    $Invs = Get_Invoices(" PayDate=0 AND OurRef='" . Sage_Code($Trad) . "'"," IssueDate DESC ");
    if ($Invs) $att = Invoice_Credit_Note($Invs[0],$data);  // TODO BUG
// var_dump($Invs);
var_dump($att);
    $NewState = $Trade_State['Cancelled'];
    Send_Trader_Email($Trad,$Trady,'Trade_Cancel',$att);
    Send_Trade_Admin_Email($Trad,$Trady,'Trade_Cancel_Admin');
    
    $xtra .= "Fee was " . $Trady['Fee'] . ", Pitch was " . $Trady['PitchLoc0'] . ", Number was " . $Trady['PitchNum0'] . "\n";
    $Trady['Fee'] = 0;
    $Trady['PitchLoc0'] = $Trady['PitchLoc1'] = $Trady['PitchLoc2'] = '';
    $Trady['PitchNum0'] = $Trady['PitchNum1'] = $Trady['PitchNum2'] = 0;
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
          $ProformaName = (($TradeTypeData[$Trad['TradeType']]['ArtisanMsgs'] && $TradeLocData[$Trady['PitchLoc0']]['ArtisanMsgs']) ? "Trade_Artisan_Final_Invoice" : "Trade_Final_Invoice");
          $InvCode = Trade_Invoice_Code($Trad,$Trady);
          $details = [["Full payment to secure your trade stand at the $PLANYEAR festival",$Fee*100]];
          if ($InvoicedTotal) $details[] = ["Less previous invoice(s)",$InvoicedTotal];
          $type = "Full";
          if ($InvoicedTotal > $Dep) $type = "Change ";

          $ipdf = New_Invoice($Trad,$details, "Trade Stand $type", $InvCode, 1, ($DueDate?$DueDate:30));
          $NewState = $Trady['BookingState'] = $Trade_State['Invoiced'];
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

  if ($Tchng) Put_Trader($Trad);
  if ($Ychng || $CurState != $NewState ) {
    $Trady['BookingState'] = $NewState;
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

function Trade_F_Action($Tid,$Action,$xtra='') { // Call from Invoicing 
  $Trad = Get_Trader($Tid);
  $Trady = Get_Trade_Year($Tid);
  Trade_Action($Action,$Trad,$Trady,1,'', $xtra);
}
?>
