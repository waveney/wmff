<?php
// For the future

$ContractMethods = array('','By Clicking Online','By Email Confirmation');

// Additive over side helps
function Add_Act_Help() {
  static $t = array(
	'ShortName'=>'IF the acts name is more than 20 characters, give a short form to appear on the Grid.',
	'Type'=>'No examples yet',
	'Blurb'=>'Longer description of the act, for the webpage on the festival website about the act - OPTIONAL',
	'Description'=>'Short description for programme and lists on website',
	'Video'=>'You can use a YouTube embed or share link',
	'Likes'=>'',
	'SideStatus'=>'If the act is disbanded mark as dead',
	'StagePA'=>'Give PA Requirments as simple text, or upload a file',
	'Bank'=>'Bank details so any fees can be paid.'
  );
  Add_Help_Table($t);
}

function Add_Act_Year_Help() {
  static $t = array( // No detail yet
	'YearState'=>'This is generally set by your and the Acts actions.  
Declined - Will leave this state after any change that would affect the contract.
Booking - negotiations in place. 
Contract Ready - For the Act to confirm it.
Booked - Enables listing to public.',
	'Rider'=>'Additional text to be added to the Contract',
	'RadioWimborne'=>'Permission given for performances to be recorded by Radio Wimborne, and broadcast live or after the festival'
  );
  Add_Help_Table($t);
}

function Get_Music_Types($tup) {
  global $db;
  $res = $db->query("SELECT * FROM MusicTypes ORDER BY Importance DESC");
  if ($res) {
    while ($typ = $res->fetch_assoc()) {
      $short[] = $typ['Name'];
      $full[$typ['TypeId']] = $typ;
    }
  }
  if ($tup) return $full;
  return $short;
}

function Get_Music_Type($id) {
  global $db;
  static $Types;
  if (isset($Types[$id])) return $Types[$id];
  $res=$db->query("SELECT * FROM MusicTypes WHERE TypeId=$id");
  if ($res) {
    $ans = $res->fetch_assoc();
    $Types[$id] = $ans;
    return $ans;
  }
  return 0; 
}

function Put_Music_Type(&$now) {
  $e=$now['TypeId'];
  $Cur = Get_Music_Type($e);
  Update_db('MusicTypes',$Cur,$now);
}

function Get_Band($act) {
  global $db;
  $res = $db->query("SELECT * FROM BandMembers WHERE BandId=$act ORDER BY Name"); // May need to change order
  if ($res) {
    while($ev = $res->fetch_assoc()) $evs[] = $ev;
    if (isset($evs)) return $evs;
  }
  return 0;
}

function Get_BandMember($mid) {
  global $db;
  $res = $db->query("SELECT * FROM BandMembers WHERE BandMemId=$mid"); 
  return $res->fetch_assoc();
}

function Put_BandMember($memb) {
  global $db;
  $cur = Get_BandMember($memb['BandMemId']);
  Update_db('BandMembers',$cur,$memb);
}

function Add_BandMember($bid,$name) {
  $ar = array('BandId'=>$bid,'Name'=>$name);
  Insert_db('BandMembers',$ar);
}

function UpdateBand($id) {
  $CurBand = Get_Band($id);
  $RevBand = array();
// Updates
  $bi = 0;
  if ($CurBand) foreach ($CurBand as $b) {
    if ($CurBand['Name'] != $_POST["BandMember$bi:" . $b['BandMemId']]) {
      $b['Name'] = $_POST["BandMember$bi:" . $b['BandMemId']];
      if ($b['Name']) {
        Put_BandMember($b);
      } else {
	db_delete('BandMembers',$b['BandMemId']);
      }
    } else if (!strlen($CurBand['Name'])) {
	db_delete('BandMembers',$b['BandMemId']);
    }
    $bi++;
  }
// New Entries
  foreach(array_keys($_POST) as $idx) {
    if (preg_match('/BandMember\d+:0/',$idx)) {
      if (strlen($_POST[$idx])) Add_BandMember($id,$_POST[$idx]);
    }
  }

}


$Save_ActYears = array('');

function Get_ActYear($snum,$year=0) {
  global $db;
  global $Save_ActYears,$YEAR;
  if (!$year) $year=$YEAR;
  if (isset($Save_ActYears[$snum][$year])) return $Save_ActYears[$snum][$year];
  $res = $db->query("SELECT * FROM ActYear WHERE SideId='" . $snum . "' AND Year='" . $year . "'");
  if (!$res || $res->num_rows == 0) return 0;
  $data = $res->fetch_assoc();
  $Save_ActYears[$snum][$year] = $data;
  return $data;
}

function Get_ActYears($snum) {
  global $db;
  global $Save_ActYears;
  if (isset($Save_ActYears[$snum]['ALL'])) return $Save_ActYears[$snum];
  $res = $db->query("SELECT * FROM ActYear WHERE SideId='$snum'");
  if (!$res) return 0;
  while ($yr = $res->fetch_assoc()) {
    $y = $yr['Year'];
    $Save_ActYears[$snum][$y] = $yr;
  }
  $Save_ActYears[$snum]['ALL'] = 1;
  return $Save_ActYears[$snum];
}

function Put_ActYear(&$data) {
  global $db;
  global $Save_ActYears,$YEAR;
  if (!isset($Save_ActYears[$data['SideId']][$data['Year']])) {
    $Save = &$Save_ActYears[$data['SideId']][$YEAR];
    $Save = Default_AY();
    $data = array_merge($Save,$data);
    $rec = "INSERT INTO ActYear SET ";
    $Up = 0;
  } else { 
    $Save = &$Save_ActYears[$data['SideId']][$data['Year']];
    $rec = "UPDATE ActYear SET ";
    $Up = 1;
  }

  $fcnt = 0;
  foreach ($data as $fld=>$val) {
    if ($Up == 0 || (isset($Save[$fld]) && $val != $Save[$fld])) {
      if ($fcnt++) $rec .= ", ";
      $rec .= "$fld='" . $val . "'";
    }
  }
  if (!$fcnt) return 0;
  if ($Up) $rec .= " WHERE ActId='" . $Save['ActId'] . "'";
  $Save = $data;
//var_dump($rec);
  return $db->query($rec);
}

function Actisknown($snum,$yr) {
  global $Save_ActYears;
  return isset($Save_ActYears[$snum][$yr]);
}

function Get_Events4Act($snum,$yr=0) {
  global $db,$YEAR;
  if ($yr==0) $yr=$YEAR;
  $res = $db->query("SELECT * FROM Events WHERE Year=$yr AND Act1=$snum ORDER BY Day, Start");
  $evs = array();
  if (!$res) return 0;
  while ($ev = $res->fetch_assoc()) $evs[] = $ev;
  return $evs; 
}

function Get_Event4Act($Eid) {
  global $db,$YEAR;
  if ($yr==0) $yr=$YEAR;
  $res = $db->query("SELECT * FROM Events WHERE EventId=$Eid");
  $evs = array();
  if (!$res) return 0;
  while ($ev = $res->fetch_assoc()) $evs[] = $ev;
  return $evs; 
}

function Select_Act_Come($type=0,$extra='') {
  global $db,$YEAR;
  static $Come_Loaded = 0;
  static $Coming = array('');
  if ($Come_Loaded) return $Coming;
  $qry = "SELECT s.SideId, s.Name, s.Type FROM Sides s, ActYear y WHERE s.SideId=y.SideId AND y.Year=$YEAR " . 
	" AND s.IsAnAct=1 " . $extra . " ORDER BY s.Name";
  $res = $db->query($qry);
  if ($res) {
    while ($row = $res->fetch_assoc()) {
      if ($type && $tow['Type']) { $x = ""; } else { $x = " ( " . $row['Type'] . " ) "; }
      $Coming[$row['SideId']] = $row['Name'] . $x;
    }
  }
  $Come_Loaded = 1;
  return $Coming;
}

function Select_Other_Come($type=0,$extra='') {
  global $db,$YEAR;
  static $Come_Loaded = 0;
  static $Coming = array('');
  if ($Come_Loaded) return $Coming;
  $qry = "SELECT s.SideId, s.Name, s.Type FROM Sides s, ActYear y WHERE s.SideId=y.SideId AND y.Year=$YEAR " . 
	" AND s.IsOther=1 " . $extra . " ORDER BY s.Name";
  $res = $db->query($qry);
  if ($res) {
    while ($row = $res->fetch_assoc()) {
      if ($type && $tow['Type']) { $x = ""; } else { $x = " ( " . $row['Type'] . " ) "; }
      $Coming[$row['SideId']] = $row['Name'] . $x;
    }
  }
  $Come_Loaded = 1;
  return $Coming;
}

function Select_Act_Come_Day($Day,$xtr='') { // This wont work - currently unused (I hope)
  global $db,$YEAR,$Coming_Type;
  $qry = "SELECT s.*, y.* FROM Sides s, ActYear y " .
	 "WHERE s.SideId=y.SideId AND y.Year=$YEAR " . " AND y.$Day=1 $xtr ORDER BY s.Name";
  $res = $db->query($qry);
  if ($res) {
    while ($row = $res->fetch_assoc()) {
      $Coming[$row['SideId']] = $row;
    }
    return $Coming;
  }
}

function &Select_Act_Come_All() {
  global $db,$YEAR,$Coming_Type;
  static $Come_Loaded = 0;
  static $Coming;
  if ($Coming) return $Coming;
  $qry = "SELECT s.*, y.* FROM Sides s, ActYear y WHERE s.SideId=y.SideId AND y.Year=$YEAR ORDER BY s.Name";
  $res = $db->query($qry);
  if ($res) while ($row = $res->fetch_assoc()) $Coming[$row['SideId']] = $row;
  return $Coming;
}

function Contract_Save($Side,$Sidey,$Reason) {
  global $THISYEAR,$Book_State;
  include_once("Contract.php");
  $snum = $Side['SideId'];
  $Cont = Show_Contract($snum,$Reason);
  if (!Contract_Check($snum)) {
    $IssNum = abs($Sidey['Contracts'])+1;
    $_POST['Contracts'] = $IssNum;
    $_POST['ContractDate'] = time();
    $_POST['YearState'] = $Book_State['Booked'];
    file_put_contents("Contracts/$THISYEAR/$snum.$IssNum.html",$Cont);
    return 1;
  }
}

function Contract_Decline($Side,$Sidey,$Reason) {
  global $THISYEAR,$Book_State;
  $_POST['YearState'] = $Book_State['Declined'];
  $_POST['PrivNotes'] .= ", Contract Declined " . date('d/m/Y');
  Put_ActYear($Sidey);
  return 1;
}

function Contract_Check($snum,$chkba=1,$ret=0) { // if ret=1 returns result number, otherwise string
  global $YEAR;
//echo "check $snum $YEAR<br>";
  $Check_Fails = array('',"Start Time","Bank Details missing","No Events","Venue Unknown","Duration not yet known","Events Clash"); // Least to most critical
// 0=ok, 1 - lack times, 2 - no bank details, 3 - no events, 4 - no Ven, 5 - no dur, 6 - clash
  include_once('ProgLib.php');
// All Events have - Venue, Start, Duration, Type - Start & End/Duration can be TBD if event-type has a not critical flag set
  $InValid = 3;
  $Evs = Get_Events4Act($snum,$YEAR);
  $types = Get_Event_Types(1);
  $Vens = Get_Venues(1);
  $LastEv = 0;
  if ($Evs) foreach ($Evs as $e) {
    if ($InValid == 3) $InValid = 0;
    if ($LastEv) {
      if (($e['Day'] == $LastEv['Day']) && ($e['Start'] > 0) && ($e['Venue'] >0)) {
        if ($LastEv['SubEvent'] < 0) { $End = $LastEv['SlotEnd']; } else { $End = $LastEv['End']; };
	if ($End > 0) {
	  if ($End > $e['Start']) $InValid = 6;
	  if ($InValid < 5 && $End == $e['Start'] && $LastEv['Venue'] != $e['Venue']) $InValid = 6;
	}
      }
    }
        
    $et = $types[$e['Type']];
    if ($InValid < 4 && ($e['Venue']==0) || !isset($Vens[$e['Venue']])) $InValid = 4;
    if (!$et['NotCrit']) {
      if ($e['SubEvent'] < 0) { $End = $e['SlotEnd']; } else { $End = $e['End']; };
      if ($InValid == 0 && $e['Start'] == 0) $InValid = 1;
      if (($e['Start'] != 0) && ($End != 0) && ($e['Duration'] == 0)) $e['Duration'] = timeadd2($End, - $e['Start']);
      if ($InValid < 5 && ($End == 0) && ($e['Duration'] == 0)) $InValid = 5; 
    }    
    $LastEv = $e;
  }  

  if ($InValid == 0 && $chkba) { // Check Bank Account if fee
    $ActY = Get_ActYear($snum);
    if ($ActY['TotalFee']) {
      $Side = Get_Side($snum);
      if ( (strlen($Side['SortCode'])<6 ) || ( strlen($Side['Account']) < 8) || (strlen($Side['AccountName']) < 8)) $InValid = 2;
    }
  }

//echo "$InValid <br>";
  if ($ret) return $InValid;  
  return $Check_Fails[$InValid];
}

// Update Year State if appapropriate
function Contract_Changed(&$Sidey) {
  global $Book_State;
  $snum = $Sidey['SideId'];
  if ($Sidey['YearState'] == $Book_State['Booked']) {
    $chk = Contract_Check($snum);
    $Sidey['YearState'] = ($chk == ''? $Book_State['Contract Ready'] : ($chk == 'Start Time'? $Book_State['Confirmed'] : $Book_State['Booking']));
    Put_ActYear($Sidey);
    return 1;
  } else if (!Contract_Check($snum)) {
    $Sidey['YearState'] = $Book_State['Contract Ready'];
    Put_ActYear($Sidey);
    return 1;
  } else {
    $Evs = Get_Events4Act($snum,$YEAR);
    if ($Evs) {
      $Sidey['YearState'] = $Book_State['Booking'];
      Put_ActYear($Sidey);
      return 1;
    } else {
      $Sidey['YearState'] = $Book_State['None'];
      Put_ActYear($Sidey);
      return 1;
    }
  }
}

function Contract_Changed_id($id) {
  $Sidey = Get_ActYear($id);
  return Contract_Changed($Sidey);
}

function Contract_State_Check(&$Sidey,$chkba=1) {
  global $Book_State;
  $snum = $Sidey['SideId'];
  $Evs = Get_Events4Act($snum,$Sidey['Year']);
  $Es = isset($Evs[0]);
  $Valid = (!Contract_Check($snum,$chkba));
  $ys = $Sidey['YearState'];
  switch ($ys) {

    case $Book_State['None']:
    default:
      if ($Valid) { $ys = $Book_State['Contract Ready']; }
      else if ($Es) { $ys = $Book_State['Booking']; }
      break;

    case $Book_State['Declined']:
      break;

    case $Book_State['Booking']:
      if ($Valid) { $ys = $Book_State['Contract Ready']; }
      break;

    case $Book_State['Contract Ready']:
      if (!$Valid)  $ys = $Book_State[$Es?'Booking':'None']; 
      break;

    case $Book_State['Booked']:
      break;
  }
  if ($ys != $Sidey['YearState']) {
    $Sidey['YearState'] = $ys;
    Put_ActYear($Sidey);
    return 1;
  }
}

function ActYear_Check4_Change(&$Cur,&$now) {
  if ($Cur['TotalFee'] != $now['TotalFee'] || $Cur['OtherPayment'] != $now['OtherPayment'] || $Cur['Rider'] != $now['Rider'] ) return Contract_Changed($now);
}

function Music_Actions($Act,&$side,&$Sidey) { // Note Sidey MAY have other records in it >= Side
  global $Book_State,$Book_States;
  $NewState = $OldState = $Sidey['YearState'];
  if (!isset($NewState)) $NewState = 0;

  switch ($Act) {
    case 'Book':
      $NewState = $Book_State['Booking'];
      break;

    case 'Cancel':
      $NewState = $Book_State['None'];
      break;

    case 'Decline':
      $NewState = $Book_State['Declined'];
      break;

    case 'Accept':
// Handle contract acceptance
      break;

    case 'Contract':
      $Valid = (!Contract_Check($side['SideId'],0));
      if ($Valid) $NewState = $Book_State['Contract Ready'];
      break;

    default:
      break;

  }

  if ($OldState != $NewState) {
//echo "Newstate $NewState<p>";
    $Sidey['YearState'] = $NewState;
    Put_ActYear($Sidey);
  }
}

function MusicMail($data,$name,$id,$direct) {
  include_once("Contract.php");
  global $USER,$Book_State;

  $AddC = 0;
  $p = -1; // Draft
  $Msg = '';

  if ($data['YearState']) {
    if ($data['YearState'] == $Book_State['Booked']) { 
      $p = 1; 
      $AddC = 1;
    } else {
      $ConAns = Contract_Check($id,1,1);
      switch ($ConAns) {
	case 0: // Ready
	  // Please Sign msg
	  $Msg = 'Please confirm your contract by following the link and clicking on the "Confirm" button on the page.<p>';
	  $p = 0;
	  $AddC = 1;
	  break;
	case 2: // Ok apart from bank account
	  $Msg = 'Please follow the link, fill in your bank account details (so we can pay you), then click "Save Changes".<p> ' .
		'Then you will be able to view and confirm your contract, ' .
		'by clicking on the "Confirm" button. (The button is only there once Bank account is).<p>';
	  $p = 0;
	  $AddC = 1;
	  break;
	case 3: // No Cont
	  break;
	default: // Add draft for info
	  $AddC = 1;
      }
    }
  }
  $Content = "$name,<p>";
  $Content .= "<span id=SideLink$id>Please use $direct</span> " .
		"to add/correct details about " . $data['Name'] . "'s contact information, update social media links, " . 
		"and information about you that appears on the festival website.<p>  $Msg";
  $Content .= "Regards " . $USER['Name'] . "<p>\n" ;
  if ($AddC) $Content .= "<div id=SideProg$id>" . Show_Contract($id,$p) . "</div><p>\n";

  return urlencode($Content);
}
?>

