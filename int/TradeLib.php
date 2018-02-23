<?php

// For Book -> Confirm -> Deposit ->Pay , if class begins with a - then not used/don't list
$Trade_States = array('Not Submitted','Declined','Refunded','Cancelled','Submitted','Quoted','Accepted','Deposit Paid','Invoiced','Fully Paid','Wait List');
$Trade_State = array_flip($Trade_States);
$Trade_StateClasses = array('TSNotSub','TSDecline','-TSRefunded','TSCancel','TSSubmit','TSInvite','TSConf','TSDeposit','-TSInvoice','TSPaid','TSWaitList');
$TS_Actions = array('Submit,Invite,Invite Better',
		'Resend',
		'Resend',
		'Resend',
		'Resend,Quote,Accept,Decline,Hold,Cancel',
		'Resend,Quote,Invite,Accept,Decline',
		'Resend,Dep Paid,Cancel',
		'Resend,Paid,Cancel',
		'Resend,Paid,Cancel',
		'Resend,Cancel',
		'Resend,Accept,Decline,Cancel');

$Trader_Status = array('Alive','Banned','Not trading');
$Trader_State = array_flip($Trader_Status);
$ButExtra = array('Accept'=>'','Decline'=>'','Submit'=>'','Hold'=>'title="Hold for space available"','Dep Paid','Paid',
	'Quote'=>'title="Send or repeat Quote email"','Invite'=>'title="Send or repeat the Invitation Email"',
	'Cancel'=>'onClick="javascript:return confirm(\'are you sure you want to cancel this?\');"',
	'Resend'=>''
	); 
$Trade_Email = array('','Trade_Decline','Trade_Refunded','Trade_Cancel','Trade_Submit', 'Trade_Quoted',
		'Trade_Accepted','Trade_Status','Trade_Status','Trade_Status','Trade_Hold');
$ButTrader = array('Submit','Accept','Decline','Cancel'); // Actions Traders can do
$Trade_Days = array('Both','Saturday Only','Sunday Only');
$Prefixes = array ('in','in the','by the');

function Get_Email_Proformas() { 
  global $db;
  $res = $db->query("SELECT * FROM EmailProformas ORDER BY SName ");
  if ($res) {
    while ($typ = $res->fetch_assoc()) $full[$typ['id']] = $typ;
  }
  return $full;
}

function Get_Email_Proforma($id) {
  global $db;
  if (is_numeric($id)) {
    $res=$db->query("SELECT * FROM EmailProformas WHERE id=$id");
  } else {
    $res=$db->query("SELECT * FROM EmailProformas WHERE SName='$id'");
  }
  if ($res) {
    $ans = $res->fetch_assoc();
    return $ans;
  }
  return 0; 
}

function Put_Email_Proforma(&$now) {
  $e=$now['id'];
  $Cur = Get_Email_Proforma($e);
  return Update_db('EmailProformas',$Cur,$now);
}

function Get_Trade_Locs($tup=0) { // 0 just names, 1 all data
  global $db;
  $res = $db->query("SELECT * FROM TradeLocs ORDER BY SName ");
  if ($res) {
    while ($typ = $res->fetch_assoc()) {
      $short[$typ['TLocId']] = $typ['SName'];
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
  global $db,$THISYEAR;
  $full = array();
  if ($tup) {
    $res = $db->query("SELECT * FROM TradePrices ORDER BY ListOrder");
    if ($res) while ($tt = $res->fetch_assoc()) $full[$tt['id']] = $tt;
  } else {
    $res = $db->query("SELECT * FROM TradePrices WHERE Addition=0 ORDER BY ListOrder");
    if ($res) while ($tt = $res->fetch_assoc()) $full[$tt['id']] = $tt['SName'];
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
  global $db,$THISYEAR;
  $full = array();
  $yr = ($tup ?"" :" WHERE Year=$THISYEAR ");
  $res = $db->query("SELECT * FROM Sponsors $yr ORDER BY SName ");
  if ($res) while ($spon = $res->fetch_assoc()) $full[] = $spon;
  if ($tup==0 && empty($full)) {
    $yr = " WHERE Year=" . ($THISYEAR-1);
    $res = $db->query("SELECT * FROM Sponsors $yr ORDER BY SName ");
    if ($res) while ($spon = $res->fetch_assoc()) $full[] = $spon;
  }
  return $full;
}

function Get_Sponsor_Names() {
  $data = Get_Sponsors();
  foreach ($data as $i=>$sp) $ans[$sp['id']]=$sp['SName'];
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

// Works for simple tables
function UpdateMany($table,$Putfn,&$data,$Deletes=1,$Dateflds='',$Timeflds='') {
  global $TableIndexes;
  include_once("DateTime.php");
  $Flds = table_fields($table);
  $DateFlds = explode(',',$Dateflds);
  $TimeFlds = explode(',',$Timeflds);
  $indxname = (isset($TableIndexes[$table])?$TableIndexes[$table]:'id');
  if (isset($_POST{'Update'})) {
    if ($data) foreach($data as $t) {
      $i = $t[$indxname];
      if (isset($_POST["SName$i"]) && $_POST["SName$i"] == '') {
	if ($Deletes) {
  	  db_delete($table,$t[$indxname]);
	  return 1;
        }
	continue;
      } else {
	foreach ($Flds as $fld=>$ftyp) {
	  if ($fld == $indxname) continue;
	  if (in_array($fld,$DateFlds)) {
	    $t[$fld] = Date_BestGuess($_POST["$fld$i"]);
	  } else if (in_array($fld,$TimeFlds)) {
	    $t[$fld] = Time_BestGuess($_POST["$fld$i"]);
	  } else {
            $t[$fld] = $_POST["$fld$i"];
	  }
        }
	$Putfn($t);
      }
    }
    if ($_POST["Name0"] != '') {
      $t = array();
      foreach ($Flds as $fld=>$ftyp) {
	if ($fld == $indxname) continue;
	if (isset($_POST[$fld . "0"])) {
	  if (in_array($fld,$DateFlds)) {
	    $t[$fld] = Date_BestGuess($_POST[$fld . "0"]);
	  } else if (in_array($fld,$TimeFlds)) {
	    $t[$fld] = Time_BestGuess($_POST[$fld . "0"]);
	  } else {
	    $t[$fld] = $_POST[$fld . "0"];
	  }
	}
      }
      Insert_db($table,$t);
    }
    return 1;
  } 
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
		" ORDER BY SName";
  $res = $db->query($qry);
  if (!$res || $res->num_rows == 0) return 0;
  while ($tr=$res->fetch_assoc()) {
    $data[$tr['Tid']] = ($type?$tr:$tr['SName']);
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
  return Update_db('TradeYear',$Cur,$now);
}

function Set_Trade_Help() {
  static $t = array(
	'Website'=>'If you would like to be listed on the Folk Festival Website, please supply your website (if you have one) and an Image and tick the box (Note traders will appear on the public website shortly)',
	'GoodsDesc'=>'Describe your goods and buisness.  At least 20 words please.  This is used both to decide whether to accept your booking and as words
to accompany your Image on the festival website',
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
  return array('Year'=>$YEAR,$Tid=>$id,'PitchSize0'=>'3Mx3M','Power0'=>0);

}

function Show_Trader($Tid,&$Trad,$Form='Trade.php',$Mode=0) { // Mode 1 = Ctte
  global $YEAR,$ADDALL,$Mess,$Action,$Trader_Status,$TradeTypeData,$TradeLocData;
  Set_Trade_Help();

  if ($Trad['Photo']) echo "<img class=floatright src=" . $Trad['Photo'] . " height=80>\n";
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
  echo "<table width=90% border class=SideTable>\n";
    echo "<tr><th colspan=8><b>Public Information</b>" . Help('PublicInfo');
    echo "<tr>" . fm_text('Business Name', $Trad,'SName',2,'','autocomplete=off onchange=nameedit(event) oninput=nameedit(event) id=Name');
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
    echo "<tr>" . fm_textarea('Products Sold',$Trad,'GoodsDesc',7,1);

//********* PRIVATE

    echo "<tr><th colspan=8><b>Private Information</b>" . Help('PrivateInfo');
    echo "<tr>";
      echo "<td>Trade Type:" . help('TradeType') . "<td colspan=7>";
      foreach ($TradeTypeData as $i=>$d) {
	if ($d['Addition']) continue;
	echo " <div class=KeepTogether style='background:" . $d['Colour'] . ";'>" . $d['SName'] . ": ";
	echo " <input type=radio name=TradeType $ADDALL value=$i ";
	if ($Trad['TradeType'] == $i) echo " checked";
        echo " onclick='SetTradeType(" . $d['NeedPublicHealth'] . "," . $d['NeedCharityNum'] . "," .
					$d['NeedInsurance'] . "," . $d['NeedRiskAssess'] . ',"' . $d['Description'] . '","' . 
					$d['Colour'] . "\")'"; // not fm-Radio because of this line
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
      echo "<td class=NotSide><button name=Action value=Delete>Delete</button>\n";
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
  global $YEAR,$THISYEAR,$MASTER,$Trade_States,$Mess,$Action,$ADDALL,$Trade_StateClasses,$InsuranceStates,$Trade_State,$Trade_Days;
  if ($year==0) $year=$YEAR;
  $CurYear = date("Y");
  if ($year < $THISYEAR) { // Then it is historical - no changes allowed
    fm_addall('disabled readonly');
  }

  $Self = $_SERVER{'PHP_SELF'};
  if ($year > $CurYear) {
    if ($Mode && Get_Trade_Year($Tid,$CurYear)) 
      echo "<div class=floatright><h2><a href=$Self?id=$Tid&Y=$CurYear>$CurYear</a></h2></div>";  
    echo "<h2>Trading in $year</h2>";
  } else if ($year == $THISYEAR) {
    if ($Mode && Get_Trade_Year($Tid,$CurYear-1)) 
      echo "<div class=floatright><h2><a href=$Self?id=$Tid&Y=" . ($CurYear-1) . ">" . ($CurYear-1) . "</a></h2></div>";  
    echo "<h2>Trading in $year</h2>";
  } else {
    if ($Mode) echo "<div class=floatright><h2><a href=$Self?id=$Tid>$THISYEAR</a></h2></div>"; 
    echo "<h2>Details for $year</h2>";
  }
  echo fm_hidden('Year',$year);
  if ($Trady['TYid']) echo fm_hidden('TYid',$Trady['TYid']);

  $TradeLocs = Get_Trade_Locs();

  echo "<table width=90% border class=SideTable>\n";
  echo fm_hidden('Year',$year) . fm_hidden('TYid',$Trady['TYid']);

  if ($Mode) {
    echo "<td class=NotCSide>Booking State:" . help('BookingState') . "<td colspan=2 class=NotCSide>";
      foreach ($Trade_States as $i=>$ts) {
	$cls = $Trade_StateClasses[$i];
	if( preg_match('/^-/',$cls)) continue;
	echo " <div class='KeepTogether $cls'>$ts: ";
	echo " <input type=radio name=BookingState $ADDALL value=$i ";
	if ($Trady['BookingState'] == $i) echo " checked";
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
  if ($Trady['PitchLoc0']) {
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
        if ($Trady['Insurance']) {
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

  echo "</table>\n";
}

function Email_Body(&$Trad,&$Trady,$messcat='Link',$simple=0) {
  global $THISYEAR,$USER,$MASTER;

  $Prof = Get_Email_Proforma($messcat);
  $Mess = ($Prof? $Prof['Body'] : "Unknown message $messcat");
  $Tid = $Trad['Tid'];

  $Key = $Trad['AccessKey'];
  $Link = "<a href=https://" . $_SERVER['HTTP_HOST'] . "/int/Direct.php?t=Trade&id=$Tid&key=$Key><b>link</b></a>";
  $WmffLink = "<a href=http://wimbornefolk.co.uk/int/Trade.php?id=$Tid><b>link</b></a>";
  $Remove = "<a href=https://" . $_SERVER['HTTP_HOST'] . "/int/Remove.php?t=Trade&id=$Tid&key=$Key><b>here</b></a>";
  $Contact = $Trad['Contact']? firstword($Trad['Contact']) : $Trad['SName'];
  $Sender = $USER['SName'];

  if (!$simple) {
    $Locs = Get_Trade_Locs(1);
    $Location = '';
    if ($Trady['PitchLoc0']) $Location = $Locs[$Trady['PitchLoc0']]['SName'];
    if ($Trady['PitchLoc1']) {
      if ($Trady['PitchLoc2']) { $Location .= ", " . $Locs[$Trady['PitchLoc1']]['SName']; }
      else { $Location .= " and " . $Locs[$Trady['PitchLoc1']]['SName']; }
    };
    if ($Trady['PitchLoc2']) { $Location .= " and " . $Locs[$Trady['PitchLoc2']]['SName']; }

    $Details = Get_Trade_Details($Trad,$Trady);
    $Dates = ($MASTER['DateFri']+1) . "," . ($MASTER['DateFri']+2) ."th June $THISYEAR";
  
    $Price = $Trady['Fee'];
    if ($Price < 0) { $Price = "Free"; }
    elseif ($Price==0) { $Price = "Not Known"; }
    else { $Price = "&pound;" . $Price; }
    $Dep = T_Deposit($Trad);

//echo "DETAILS: $Details<p><p>\n";
  }

  $Mess = preg_replace('/\*WHO\*/',$Contact,$Mess);
  $Mess = preg_replace('/\*LINK\*/',$Link,$Mess);
  $Mess = preg_replace('/\*WMFFLINK\*/',$WmffLink,$Mess);

  if (!$simple) {
    $Mess = preg_replace('/\*HERE\*/',$Remove,$Mess);
    $Mess = preg_replace('/\*LOCATION\*/',$Location,$Mess);
    $Mess = preg_replace('/\*PRICE\*/',$Price,$Mess);
    $Mess = preg_replace('/\*DEPOSIT\*/',$Dep,$Mess);
    $Mess = preg_replace('/\*BALANCE\*/',($Trady['Fee'] - $Dep),$Mess);
    $Mess = preg_replace('/\*DETAILS\*/',$Details,$Mess);
    $Mess = preg_replace('/\*THISYEAR\*/',$THISYEAR,$Mess);
    $Mess = preg_replace('/\*DATES\*/',$Dates,$Mess);
  }

  $Mess .= "<p>Regards, Wimborne Minster Folk Festival\n";

  return $Mess;
}

function Send_Trader_Email(&$Trad,&$Trady,$messcat='Link',$cont='') {
//  Get EMail template
//  Edit in all standard expansions
//  Send
//  Send Copy to Mandy
//  Log for Weekly Email

  $Mess = Email_Body($Trad,$Trady,$messcat);
  if (file_exists("testing")) {
    SendEmail("Richard@wavwebs.com","Wimborne Minster Folk Festival $THISYEAR and " . $Trad['SName'],$Mess);
  } else {
    SendEmail($Trad['Email'],"Wimborne Minster Folk Festival $THISYEAR and " . $Trad['SName'],$Mess);
  }

  $logf = fopen("LogFiles/TradeLog.txt","a");
  fwrite($logf,"\n\nEmail to : " . $Trad['Email'] . "\n\n" . $Mess);
  fclose($logf);
}

// Not logged
function Send_Trader_Simple_Email(&$Trad,$messcat='Trade_Link') {
  $Mess = Email_Body($Trad,$Trad,$messcat,1);
  
  if (file_exists("testing")) {
    SendEmail("Richard@wavwebs.com","Wimborne Minster Folk Festival $THISYEAR and " . $Trad['SName'],$Mess);
  } else {
    SendEmail($Trad['Email'],"Wimborne Minster Folk Festival $THISYEAR and " . $Trad['SName'],$Mess);
  }
}

function Old_Send_Trader_Email(&$Trad,$messcat='Link',$cont='') {

  $letter = '';

  if (isset($data[$xtr .'Contact'])) { $name = firstword($Trad[$xtr .'Contact']); }
  else { $name = $Trad['SName']; }
  $id = $Trad['Tid'];
  $key = $Trad['AccessKey'];
  $to = $Trad[$xtr . 'Email'];
  
  $letter .= "$name,<p>\n";
  
  switch ($messcat) {
  case 'Link':
    $letter .= $cont;
    $letter .= "Please use this " .  "<a href=http://wimbornefolk.co.uk/int/Direct.php?t=trade&id=$id&key=$key>this Wimborne Minster Folk Festival link</a>.<p>  " .
    		"To add and/or correct details about your business, contact information, your product descriptions, book pitch(es) and give power requirements, " . 
		"update your Insurance and Risc Assessment etc.<p>" .
		"Details of your pitch location, general trader information and particulars of setup and cleardown information will also appear there.<p>" . 
		"Save the link for future use.<p>";
    break;
  case 'Submit':
    $letter .= "Thankyou for submitting your application for Trading at the Wimborne Minster Folk Festival.\n" .
    		"Please use this " .  "<a href=http://wimbornefolk.co.uk/int/Direct.php?t=trade&id=$id&key=$key>this Wimborne Minster Folk Festival link</a>,  " .
    		"to add and/or correct details about your business, contact information, your product descriptions, book or change your pitch(es) and give " .
		" power requirements, update your Insurance and Risc Assessment etc.<p>" .
		"Details of your pitch location, general trader information and particulars of setup and cleardown information will also appear there.<p>" . 
		"Save the link for future use.<p>" .
		"If you have any queries that are not answered by the <a href=http://wimbornefolk.co.uk/int/TradeFAQ.php>Trade FAQ</a>, " .
		"please contact <a href=mailto:trade@wimbornefolk.co.uk>trade@wimbornefolk.co.uk</a>\n\n";
    $cont = preg_replace("/\n/","<br>\n",$cont);
    $letter .= $cont;
    break;
  }
  $letter .= "Regards, The Wimborne Minster Folk Festival booking system.<p>"; 

  if (file_exists('testing')) {
    echo "<h3>The following Email would be sent to $to:</h3><p><div class=DevEmail>$letter</div><p>\n";

    echo "Use the <a href=Direct.php?t=trade&id=$id&key=$key>link</a>.<p>  ";
  } else {
//   SendEmail($to,'Wimborne Minster Folk Festival Trader Link',$letter);
  }
}

function Get_Trade_Details(&$Trad,&$Trady) {
  global $Trade_Days,$TradeLocData;

//  $Body  = "\nWimborne Minster Folk festival Trading application\n";
  $Body = "\nFrom: " . $Trad['SName'] . "\n";
  $Body .= "Goods: " . $Trad['GoodsDesc'] . "\n\n";
  if ($Trad['Website']) $Body .= "Website: " . $Trad['Website'] . "\n\n";
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
  if ($Trady['PitchLoc0']) $Body .= " at " . $TradeLocData[$Trady['PitchLoc0']]['SName'];
  if ($Trady['Power0']) $Body .= " with " . ($Trady["Power0"]> 0 ? $Trady['Power0'] . " Amps\n" : " own Euro 4 silent generator\n");
  if ($Trady['PitchSize1']) {
    $Body .= "\nPitch 2:" . $Trady['PitchSize1'];
    if ($Trady['PitchLoc1']) $Body .= " at " . $TradeLocData[$Trady['PitchLoc1']]['SName'];
    if ($Trady['Power1']) $Body .= " with " . $Trady['Power1'] . " Amps\n";
  }
  if ($Trady['PitchSize2']) {
    $Body .= "\nPitch 3:" . $Trady['PitchSize2'];
    if ($Trady['PitchLoc2']) $Body .= " at " . $TradeLocData[$Trady['PitchLoc2']]['SName'];
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


//  Mark as submitted, email fest and trader, record data of submission
function Submit_Application(&$Trad,&$Trady,$Mode=0) {  
  global $Trade_State,$THISYEAR,$USER,$Trade_Days;
  $Trady['Date'] = time();
  $Trady['History'] .= "Action: Submit on " . date('j M Y H:i') . " by " . ($Mode?$USER['Login']:'Trader') . ".\n";
  if ($Trady['TYid']) {
    Put_Trade_Year($Trady);
  } else { // Its new...
    $Trady['Year'] = $THISYEAR;
    $TYid = Insert_db_post('TradeYear',$Trady);
  }

  Send_Trader_Email($Trad,$Trady,'Trade_Submit');
  Send_Trade_Admin_Email($Trad,$Trady,'Trade_NewSubmit');

  echo "<h3>Your application has been submitted</h3>\nAn email has been sent to you with a summary of your submission and a link to enable you to update it.\n<p>";
}

function Validate_Trade($Mode=0) { // Mode 1 for Staff Submit, less stringent
  global $TradeTypeData;
      $proc = 1;
      if (!isset($_POST['SName']) || strlen($_POST['SName']) < 3 ) {
	echo "<h2 class=ERR>No Business Name Given</h2>\n";
	$proc = 0;
      }
      if (!isset($_POST['Contact']) || strlen($_POST['Contact']) < 8 ) {
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
  return $Trad['SName'];
}

function T_Deposit(&$Trad) {
  global $TradeTypeData;
  return $TradeTypeData[$Trad['TradeType']]['Deposit'];
}

function Validate_Pitches(&$CurDat) {
  global $db,$THISYEAR,$TradeLocsData;
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
	  $qry = "SELECT * FROM TradeYear WHERE Year=$THISYEAR AND (( PitchLoc0=$pl AND PitchNum0=$ln ) || (PitchLoc1=$pl AND PitchNum1=$ln) " .
	         " || (PitchLoc2=$pl AND PitchNum2=$ln)) $Daytest";
	  $res = $db->query($qry);
	  if ($res->num_rows != 0) {
	    $dat = $res->fetch_assoc();
	    return "Pitch " . ($pn+1) . " already in use by " . Trader_Name($dat['Tid']);
	  }
	  if ($ln > $TradeLocsData[$pl]['Pitches']) return "Pitch Number " . ($pn+1) . " Out of range (1-" . $TradeLocsData[$pl]['Pitches'] . ")";
	}
      }
    }
  }
  return 0;   
}


function Trade_Main($Mode,$Program,$iddd=0) {
// Mode 0 = Traders, 1 = ctte, Program = Trade/Trader$iddd if set starts it up, with that Tid

  global $YEAR,$THISYEAR,$Mess,$Action,$Trade_State,$Trade_States,$USER,$TS_Actions,$ButExtra,$ButTrader;
  global $TradeTypeData,$TradeLocData;
  include_once("DateTime.php"); 
  echo '<div class="content"><h2>Add/Edit Trade Stall Booking</h2>';

  $Action = 0; 
  $Mess = '';
  if (isset($_POST{'Action'})) {
    include("Uploading.php");
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
        include("Staff.php");  // No return
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
    if ($Tid > 0) { 				// existing Trader 
      $Trad = Get_Trader($Tid);
      if ($Trad) {
        $Tradyrs = Get_Trade_Years($Tid);
        if (isset($Tradyrs[$THISYEAR])) $Trady = $Tradyrs[$THISYEAR];
      } else {
        echo "<h2 class=ERR>Could not find Trader $Tid</h2>\n";
      }

      if (isset($_POST{'NewAccessKey'})) $_POST{'AccessKey'} = rand_string(40);

      Update_db_post('Trade',$Trad);
      if ($_POST{'Year'} == $THISYEAR) {
        if ($Trady) {
	  $OldFee = $Trady['Fee'];
	  if ($Mode) {
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
	  } else {
	    $Check_Changed = array("PitchSize0","PitchSize1","PitchSize2","Power0","Power1","Power2","Days");
	    $same=1;
	    foreach($Check_Changed as $cc) if ($Trady[$cc] != $_POST[$cc]) $same = 0;
	    if ($Trad['TradeType'] != $_POST['TradeType']) $same = 0;
	  }
          if (!$Mess) Update_db_post('TradeYear',$Trady);
          if ($same == 0 && $Trady['BookingState'] >= $Trade_State['Submitted']) Send_Trade_Admin_Email($Trad,$Trady,'Trade_Changes');
          if ($Trady['Fee'] >=0 && $OldFee != $Trady['Fee'] && $Trady['BookingState'] >= $Trade_State['Accepted']) 
		Send_Trade_Finance_Email($Trad,$Trady,'Trade_UpdateBalance');
        } else {
	  if ($_POST['Insurance'] || $_POST['RiskAssessment'] || $_POST['PitchSize0'] != '3Mx3M' || $_POST['PitchSize1'] || $_POST['PitchSize2'] ||
		$_POST['Power0'] || $_POST['Power1'] || $_POST['Power2'] || $_POST['YNotes'] || $_POST{'BookingState'} || isset($_POST['Submit']) ||
		$_POST['Days'] || $_POST['Fee'] || $_POST['PitchLoc0'] || $_POST['PitchLoc1'] || $_POST['PitchLoc2']) {
	    if (isset($_POST['Fee']) && ($_POST['Fee'] < 0) && ($_POST['BookingState'] >= $Trade_State['Accepted'])) $_POST['BookingState'] = $Trade_State['Fully Paid'];
 	    $_POST['Year'] = $THISYEAR;
	    $TYid = Insert_db_post('TradeYear',$Trady);
	    $Trady['TYid'] = $TYid;
	  };
	};
      }
      if (isset($_POST['ACTION'])) Trade_Action($_POST['ACTION'],$Trad,$Trady,$Mode);
    } else { // New trader 
      $_POST['AccessKey'] = rand_string(40);
      $Tid = Insert_db_post('Trade',$Trad,$proc);
      if ($Tid) Insert_db_post('TradeYear',$Trady,$proc);
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
    if (Access('SysAdmin')) {
      echo "<div class=floatright>";
      echo "<input type=Submit id=smallsubmit name='NewAccessKey' value='New Access Key'>";
      if ($Trady['BookingState'] >= $Trade_State['Accepted']) echo "<input type=Submit id=smallsubmit name='ACTION' value='Resend Finance'>";
      echo "</div>\n";
    }
    echo "<Center>";
    echo "<input type=Submit name='Update' value='Save Changes'>\n";
//    if (!isset($Trady['BookingState']) || $Trady['BookingState']== 0) echo "<input type=Submit name=Submit value='Save Changes and Submit Application'>";
    if (!isset($Trady['BookingState'])) $Trady['BookingState'] = 0;
    $Act = $TS_Actions[$Trady['BookingState']];
    if ($Act ) {
      $Acts = preg_split('/,/',$Act); 
      if ($TradeTypeData[$Trad['TradeType']]['ArtisanMsgs']) {
	if ($TradeLocData[$Trady['PitchLoc0']]['ArtisanMsgs']) $dummy=1;
      }
      if ($TradeTypeData[$Trad['TradeType']]['ArtisanMsgs'] && $TradeLocData[$Trady['PitchLoc0']]['ArtisanMsgs']) $Acts[] = 'Artisan Invite';
      foreach($Acts as $ac) {
        if ($Mode==0 && !in_array($ac,$ButTrader)) continue;
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
	    if ($Trady['Fee'] == 0) continue 2;
	    break;
	  case 'Paid':
	    if ($Trady['Fee'] == 0) continue 2;
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

  if ($Mode) echo "<h2><a href=ListCTrade.php>List Traders Coming</a></h2>";
}

// Send confirmation email and deposit invoice
function Trade_Confirm(&$Trad,&$Trady) {
  return;
  global $THISYEAR;
  $Dep = T_Deposit($Trad);
  $letter = "This is to confirm your booking for Trading at the $THISYEAR Wimborne Minster Folk Festival.<p>";
  if ($Dep) {
    $letter .= "Please now pay the deposit of &pound;$Dep quoting reference " . (1000000 +$Trady['TYid']) . "<br>" .
	"Account Name: Wimborne Minster Folk Festival Ltd<br>" .
	"Sort Code: 77 50 27<br>" .
	"Account Number: 22719960<p>";
  }

  $letter .= "Later, your location has been assigned and the fees calculated, you will recieve details and the invoice for the remainder.<p>";
	
  Send_Trader_Email($Trad,$Trady,'Link',$letter);
}

function Send_Trade_Admin_Email(&$Trad,&$Trady,$messcat) {
  $Mess = Email_Body($Trad,$Trady,$messcat);
  if ($Trad['Status'] == 1) $Mess = "THIS IS FROM A BANNED TRADER<P>" . $Mess;

  if ($Trad['Notes']) $Mess .= "<p>PRIVATE NOTES:<br>" . $Trad['Notes'] . "<p>";

  if ($Trady['PNotes']) $Mess .= "<p>PRIVATE NOTES:<br>" . $Trady['PNotes'] . "<p>";

  if (file_exists("testing")) {
    SendEmail("Richard@wavwebs.com", "WMFF Trade Admin Message",$Mess);
  } else {
    SendEmail("trade@wimbornefolk.co.uk", "WMFF Trade Admin Message",$Mess);
  }

  $logf = fopen("LogFiles/TradeLog.txt","a");
  fwrite($logf,"\n\nEmail to : " . $Trad['Email'] . "\n\n" . $Mess);
  fclose($logf);
}

function Send_Trade_Finance_Email(&$Trad,&$Trady,$messcat) {
  $Mess = Email_Body($Trad,$Trady,$messcat);
  if (file_exists("testing")) {
    SendEmail("Richard@wavwebs.com", "WMFF Finance Message",$Mess);
  } else {
    SendEmail("mandy.dorset@gmail.com", "WMFF Finance Message",$Mess);
  }

  $logf = fopen("LogFiles/TradeLog.txt","a");
  fwrite($logf,"\n\nEmail to : " . $Trad['Email'] . "\n\n" . $Mess);
  fclose($logf);
}

// Highly recursive set of actions - some trigger others 
function Trade_Action($Action,&$Trad,&$Trady,$Mode=0,$Hist='') {
  global $Trade_State,$TradeTypeData,$Trade_Email;
  $Tchng = $Ychng = 0;
  $PaidSoFar = $Trady['TotalPaid'];
  $CurState = $NewState = $Trady['BookingState'];

  switch ($Action) {
  case 'Create' :
    break;

  case 'Create and Submit Application':
  case 'Submit' :
    if ($Trady['Fee']) {
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
    } else {
      if ($TradeTypeData[$Trad['TradeType']]['ArtisanMsgs'] && $TradeLocData[$Trady['PitchLoc0']]['ArtisanMsgs']) {
        Send_Trader_Email($Trad,$Trady,'Trade_Artisan_Accept');
      } else {
        Send_Trader_Email($Trad,$Trady,'Trade_Accepted');
      }
      Send_Trade_Finance_Email($Trad,$Trady,'Trade_RequestDeposit');
    }
    break;

  case 'Resend Finance':
    Send_Trade_Finance_Email($Trad,$Trady,'Trade_RequestDeposit');
    break;

  case 'Decline' :
    $NewState = $Trade_State['Declined'];
    Send_Trader_Email($Trad,$Trady,'Trade_Decline');
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
      $Trady['TotalPaid'] += $Dep;
      $xtra = " of $Dep ";
      $NewState = $Trade_State['Deposit Paid'];
    }
    break;

  case 'Paid' : // Should not need anything
    $Dep = T_Deposit($Trad);
    $fee = $Trady['Fee'];
    if (($fee > 0) && ($fee > $PaidSoFar)) {
      $Trady['TotalPaid'] += $fee - $Dep;
      $xtra = " " . ($fee - $Dep) . " ";
    }
    $NewState = $Trade_State['Fully Paid'];
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

  case 'Cancel' :
    $NewState = $Trade_State['Cancelled'];
    Send_Trader_Email($Trad,$Trady,'Trade_Cancel');
    break;

  case 'Change' :
    $NewState = $Trade_State['Submitted'];
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
    if ($Trady['Fee']) {
      $NewState = $Trade_State['Quoted'];
      Send_Trader_Email($Trad,$Trady,'Trade_Quote');
    }
    break;

  case 'Resend' :
    Send_Trader_Email($Trad,$Trady,$Trade_Email[$CurState]);

    break;
  default:
    break;
  }

// var_dump($Ychng,$CurState,$NewState);

  if ($Tchng) Put_Trader($Trad);
  if ($Ychng || $CurState != $NewState ) {
    $Trady['BookingState'] = $NewState;
    $By = (isset($USER['Login'])) ? $USER['Login'] : 'Trader';
    $Trady['History'] .= "Action: $Hist $Action $xtra on " . date('j M Y H:i') . " by $By.\n";
    Put_Trade_Year($Trady);
  }
}

?>
