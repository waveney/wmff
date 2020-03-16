<?php
// For the future

$ContractMethods = array('','By Clicking Online','By Email Confirmation');

// Additive over side helps
function Add_Act_Help() {
  static $t = [];
  Add_Help_Table($t);
}

function Add_Act_Year_Help() {
  static $t = [];
  Add_Help_Table($t);
}

function Get_Music_Types($tup) {
  global $db;
  $res = $db->query("SELECT * FROM MusicTypes ORDER BY Importance DESC");
  if ($res) {
    while ($typ = $res->fetch_assoc()) {
      $short[] = $typ['SN'];
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
  $res = $db->query("SELECT * FROM BandMembers WHERE BandId=$act ORDER BY SN"); // May need to change order
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
  $ar = array('BandId'=>$bid,'SN'=>$name);
  return Insert_db('BandMembers',$ar);
}

function UpdateBand($id) {
  $CurBand = Get_Band($id);
  $RevBand = array();
//  echo "UpdateBand Called...";
//var_dump(debug_backtrace());
//  var_dump($CurBand);
//  var_dump($_POST);
// Updates
  $bi = 0;
  if ($CurBand) foreach ($CurBand as $b) {
    if (isset($_POST["BandMember$bi:" . $b['BandMemId']]) && ($b['SN'] != $_POST["BandMember$bi:" . $b['BandMemId']])) {
      $b['SN'] = $_POST["BandMember$bi:" . $b['BandMemId']];
      if ($b['SN']) {
        Put_BandMember($b);
      } else {
        db_delete('BandMembers',$b['BandMemId']);
      }
    } else if (!strlen($b['SN'])) {
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
//    $Save = Default_AY();
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
  $res = $db->query("SELECT DISTINCT e.* FROM Events e, BigEvent b WHERE e.Year='$yr' AND ( e.Side1=$snum OR e.Side2=$snum OR e.Side3=$snum OR e.Side4=$snum " .
                " OR ( e.BigEvent=1 AND e.EventId=b.Event AND ( b.type='Side' OR b.Type='Act' OR b.Type='Other' OR b.Type='Perf' ) AND b.Identifier=$snum ) ) " .
                " ORDER BY Day, Start");
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

function Act_Name_List() {
  global $db;
  $Sides = array();
  $res = $db->query("SELECT SideId, SN FROM Sides WHERE SideStatus=0 AND IsAnAct=1 ORDER BY SN");
  if ($res) while ($row = $res->fetch_assoc()) $Sides[$row['SideId']] = $row['SN'];
  return $Sides;
}

function Other_Name_List() {
  global $db;
  $Sides = array();
  $res = $db->query("SELECT SideId, SN FROM Sides WHERE SideStatus=0 AND IsOther=1 ORDER BY SN");
  if ($res) while ($row = $res->fetch_assoc()) $Sides[$row['SideId']] = $row['SN'];
  return $Sides;
}

function Perf_Name_List($isa) {
  global $db;
  $Sides = [];
  $res = $db->query("SELECT SideId, SN FROM Sides WHERE SideStatus=0 AND $isa=1 ORDER BY SN");
  if ($res) while ($row = $res->fetch_assoc()) $Sides[$row['SideId']] = $row['SN'];
  return $Sides;
}

// TODO generalise these Name_List funcs to take para for perf type wanted

function Select_Act_Come($type=0,$extra='') {
  global $db,$YEAR;
  static $Come_Loaded = 0;
  static $Coming = array('');
  if ($Come_Loaded) return $Coming;
  $qry = "SELECT s.SideId, s.SN, s.Type FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year='$YEAR' AND s.IsAnAct=1 " . $extra . " ORDER BY s.SN";
  $res = $db->query($qry);
  if ($res) {
    while ($row = $res->fetch_assoc()) {
      $x = ($type && $row['Type'])?( " (" . trim($row['Type']) . ") ") : "";
      $Coming[$row['SideId']] = $row['SN'] . $x;
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
  $qry = "SELECT s.SideId, s.SN, s.Type FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year='$YEAR' AND s.IsOther=1 " . $extra . " ORDER BY s.SN";
  $res = $db->query($qry);
  if ($res) {
    while ($row = $res->fetch_assoc()) {
      $x = ($type && $row['Type'])?( " (" . trim($row['Type']) . ") ") : "";
      $Coming[$row['SideId']] = $row['SN'] . $x;
    }
  }
  $Come_Loaded = 1;
  return $Coming;
}

// New code

function Select_Perf_Come($Perf,$type=0,$extra='') {
  global $db,$YEAR;
  static $Coming;
  if (isset($Coming[$Perf])) return $Coming[$Perf];
  $qry = "SELECT s.SideId, s.SN, s.Type FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year='$YEAR' " . 
        " AND s.$Perf=1 AND y.YearState>=2 " . $extra . " ORDER BY s.SN";
  $res = $db->query($qry);
  if ($res) {
    while ($row = $res->fetch_assoc()) {
      $x = ($type && $row['Type'])?( " (" . trim($row['Type']) . ") ") : "";
      $Coming[$Perf][$row['SideId']] = $row['SN'] . $x;
    }
  }
  if (!isset($Coming[$Perf])) $Coming[$Perf]=[''];
  return $Coming[$Perf];
}

function Select_Perf_Come_All($Perf,$extra='') {
  global $db,$YEAR;
  static $Coming;
  if (isset($Coming[$Perf])) return $Coming[$Perf];
  $qry = "SELECT s.*, y.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year='$YEAR' " . 
        " AND s.$Perf=1 AND y.YearState>=2 " . $extra . " ORDER BY s.SN";
  $res = $db->query($qry);
  if ($res) {
    while ($row = $res->fetch_assoc()) {
      $Coming[$Perf][$row['SideId']] = $row;
    }
  }
  if (!isset($Coming[$Perf])) $Coming[$Perf]=[''];
  return $Coming[$Perf];
}



// TODO Generalise these as well

function &Select_Act_Full() {
  global $db,$YEAR;
  $Coming = [];
  $qry = "SELECT s.*, y.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year='$YEAR' AND s.IsAnAct=1 ORDER BY s.SN";
  $res = $db->query($qry);
  if ($res) while ($row = $res->fetch_assoc()) $Coming[$row['SideId']] = $row;
  return $Coming;
}

function &Select_Other_Full() {
  global $db,$YEAR;
  $Coming = [];
  $qry = "SELECT s.*, y.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year='$YEAR' AND s.IsOther=1 ORDER BY s.SN";
  $res = $db->query($qry);
  if ($res) while ($row = $res->fetch_assoc()) $Coming[$row['SideId']] = $row;
  return $Coming;
}

function &Select_Perf_Full() {
  global $db,$YEAR;
  $Perfs = [];
  $qry = "SELECT * FROM Sides s";
  $res = $db->query($qry);
  if ($res) while ($row = $res->fetch_assoc()) $Perfs[$row['SideId']] = $row;
  return $Perfs;
}

function Select_Act_Come_Day($Day,$xtr='') { // This wont work - currently unused (I hope)
  global $db,$YEAR,$Coming_Type;
  $qry = "SELECT s.*, y.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year='$YEAR' " . " AND y.$Day=1 $xtr ORDER BY s.SN";
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
  $qry = "SELECT s.*, y.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year='$YEAR' ORDER BY s.SN";
  $res = $db->query($qry);
  if ($res) while ($row = $res->fetch_assoc()) $Coming[$row['SideId']] = $row;
  return $Coming;
}

function &Select_Act_Come_Full() {
  global $db,$YEAR,$Coming_Type;
  static $Come_Loaded = 0;
  static $Coming;
  if ($Coming) return $Coming;
  $qry = "SELECT s.*, y.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND s.IsAnAct=1 AND y.Year='$YEAR' ORDER BY s.SN";
  $res = $db->query($qry);
  if ($res) while ($row = $res->fetch_assoc()) $Coming[$row['SideId']] = $row;
  return $Coming;
}

function &Select_Other_Come_Full() {
  global $db,$YEAR,$Coming_Type;
  static $Come_Loaded = 0;
  static $Coming;
  if ($Coming) return $Coming;
  $qry = "SELECT s.*, y.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND s.IsOther=1 AND y.Year='$YEAR' ORDER BY s.SN";
  $res = $db->query($qry);
  if ($res) while ($row = $res->fetch_assoc()) $Coming[$row['SideId']] = $row;
  return $Coming;
}

function &Act_All() {
  global $db;
  $All = array();
  $qry = "SELECT SideId, SN FROM Sides s WHERE IsAnAct=1 ORDER BY SN";
  $res = $db->query($qry);
  if ($res) while ($row = $res->fetch_assoc()) $All[$row['SideId']] = $row['SN'];
  return $All;
}

function &Other_All() {
  global $db;
  $All = array();
  $qry = "SELECT SideId, SN FROM Sides s WHERE IsOther=1 ORDER BY SN";
  $res = $db->query($qry);
  if ($res) while ($row = $res->fetch_assoc()) $All[$row['SideId']] = $row['SN'];
  return $All;
}

function Contract_Save($Side,$Sidey,$Reason,$exist=0) {
  global $PLANYEAR,$Book_State;
  include_once("Contract.php");
  $snum = $Side['SideId'];
  $Cont = Show_Contract($snum,$Reason);
  if (!Contract_Check($snum)) {
    $IssNum = abs($Sidey['Contracts'])+ ($exist?0:1);
    $_POST['Contracts'] = $IssNum;
    $_POST['ContractDate'] = time();
    $_POST['YearState'] = $Book_State['Contract Signed'];
    if (!file_exists("Contracts/$PLANYEAR")) mkdir("Contracts/$PLANYEAR",0775,true);
    file_put_contents("Contracts/$PLANYEAR/$snum.$IssNum.html",$Cont);
    return 1;
  }
}

function Contract_Decline($Side,$Sidey,$Reason) {
  global $PLANYEAR,$Book_State;
  $Sidey['YearState'] = $_POST['YearState'] = $Book_State['Declined'];
  $Note = ", Contract Declined " . date('d/m/Y');
  $Sidey['PrivNotes'] .= $Note;
  if (isset($_POST['PrivNotes'])) $_POST['PrivNotes'] .= $Note;
  put_SideYear($Sidey);
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
  $Vens = Get_Real_Venues(1);
  $LastEv = 0;
  if ($Evs) foreach ($Evs as $e) {
    if ($InValid == 3) $InValid = 0;
    if ($LastEv) {
      if (($e['Day'] == $LastEv['Day']) && ($e['Start'] > 0) && ($e['Venue'] >0)) {
        if ($LastEv['SubEvent'] < 0) { $End = $LastEv['SlotEnd']; } else { $End = $LastEv['End']; };
        if ($LastEv['BigEvent']) $End -=30; // Fudge for procession
        if (($End > 0) && !$LastEv['IgnoreClash'] && !$e['IgnoreClash']) {
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
    $ActY = Get_SideYear($snum);
    if ($ActY['TotalFee']) {
      $Side = Get_Side($snum);
      if ( (strlen($Side['SortCode'])<6 ) || ( strlen($Side['Account']) < 8) || (strlen($Side['AccountName']) < 6)) $InValid = 2;
    }
  }

//echo "$InValid <br>";
  if ($ret) return $InValid;  
  if ($InValid == 0) {
    $act = Get_Side($snum);
//    if (!$act['Description']) return "No Short Blurb";
    if (!$act['Photo']) return "No Photo";
  }
  return $Check_Fails[$InValid];
}

// Update Year State if appapropriate
function Contract_Changed(&$Sidey) {
  global $Book_State,$YEAR;
  $snum = $Sidey['SideId'];
  if ($Sidey['YearState'] == $Book_State['Contract Signed']) {
    $chk = Contract_Check($snum);
    $Sidey['YearState'] = ($chk == ''? $Book_State['Contract Ready'] : ($chk == 'Start Time'? $Book_State['Confirmed'] : $Book_State['Booking']));
    Put_SideYear($Sidey);
    return 1;
  } else if (!Contract_Check($snum)) {
    $Sidey['YearState'] = $Book_State['Contract Ready'];
    Put_SideYear($Sidey);
    return 1;
  } else {
    $Evs = Get_Events4Act($snum,$YEAR);
    if ($Evs) {
      $Sidey['YearState'] = $Book_State['Booking'];
      Put_SideYear($Sidey);
      return 1;
    } else {
      $Sidey['YearState'] = $Book_State['None'];
      Put_SideYear($Sidey);
      return 1;
    }
  }
}

function Contract_Changed_id($id) {
  $Sidey = Get_SideYear($snum);
  return Contract_Changed($Sidey);
}

function Contract_State_Check(&$Sidey,$chkba=1) {
  global $Book_State;
  if (!isset($Sidey['SideId'])) return 0;
  $snum = $Sidey['SideId'];
  $Evs = Get_Events4Act($snum,$Sidey['Year']);
  $Es = isset($Evs[0]);
  $Valid = (!Contract_Check($snum,$chkba));
//echo "Contract Check $snum $Valid<p>";
//var_dump($Valid);
  if (!isset($Sidey['YearState'])) $Sidey['YearState'] = $Book_State['None'];
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

    case $Book_State['Contract Signed']:
      break;
  }
  if ($ys != $Sidey['YearState']) {
    $Sidey['YearState'] = $ys;
    Put_SideYear($Sidey);
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
    Put_SideYear($Sidey);
  }
}

function MusicMail($data,$name,$id,$direct) {
  include_once("Contract.php");
  global $USER,$Book_State;

  $datay = Get_SideYear($id);
  $AddC = 0;
  $p = -1; // Draft
  $Msg = '';

  if (isset($datay['YearState']) && $datay['YearState']) {
    if ($datay['YearState'] == $Book_State['Contract Signed']) { 
      $p = 1; 
      $AddC = 1;
    } else {
      $ConAns = Contract_Check($id,1,1);
      switch ($ConAns) {
        case 0: // Ready
          // Please Sign msg
          $Msg = '<b>Please confirm your contract by following the link and clicking on the "Confirm" button on the page.</b><p>';
          $p = 0;
          $AddC = 1;
          break;
        case 2: // Ok apart from bank account
          $Msg = 'Please follow the link, fill in your bank account details (so we can pay you), then click "Save Changes".<p> ' .
                'Then you will be able to view and confirm your contract, ' .
                'by clicking on the "Confirm" button. (The button will only appear once you have input your bank account details ).<p>';
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
                "to add/correct details about " . $data['SN'] . "'s contact information, update social media links, " . 
                "and information about you that appears on the festival website.<p>  $Msg";
  $Content .= "Regards " . $USER['SN'] . "<p>\n" ;
  if ($AddC) $Content .= "<div id=SideProg$id>" . Show_Contract($id,$p) . "</div><p>\n";

  return urlencode($Content);
}
?>

