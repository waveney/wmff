<?php

// If table's index is 'id' it does not need to be listed here
$TableIndexes = array(  'Sides'=>'SideId', 'SideYear'=>'syId', 'FestUsers'=>'UserId', 'Venues'=>'VenueId', 'Events'=>'EventId', 
                        'General'=>'Year', 'Bugs'=>'BugId', 'BigEvent'=>'BigEid', 'DanceTypes'=>'TypeId', 
                        'Directory'=>'DirId', 'Documents'=>'DocId', 'EventTypes'=>'ETypeNo',
                        'MusicTypes'=>'TypeId','TimeLine'=>'TLid', 'BandMembers'=>'BandMemId', 'ActYear'=>'ActId',
                        'TradeLocs'=>'TLocId','Trade'=>'Tid','TradeYear'=>'TYid'
                        );

function db_open () {
  global $db;
  @ $db = new mysqli('localhost','wmff','','wmff');
  if (!$db) die ('Could not connect: ' .  mysqli_error());
}

db_open();

function Logg($what) {
  global $db,$USERID;
  $qry = "INSERT INTO LogFile SET Who='$USERID', changed='" . date('d/m/y H:i:s') . "', What='" . addslashes($what) . "'";
  $db->query($qry);
}

function table_fields($table) {
  global $db;
  static $tables = array();
  if (isset($tables[$table])) return $tables[$table];

  $qry = "SELECT Column_Name, Data_type FROM information_schema.columns WHERE table_name='" . $table . "'";
  $Flds = $db->query($qry);
  while ($Field = $Flds->fetch_array()) {
    $tables[$table][$Field['Column_Name']] = $Field['Data_type'];
  }
  return $tables[$table];
}


function Get_Emails($roll) {
  global $db;
  global $Area_Type;
  $qry = "SELECT Email FROM FestUsers WHERE $roll=" . $Area_Type['Edit and Report'];
  $res = $db->query($qry);
  $ans = "";
  if ($res) while ($row = $res->fetch_assoc()) {
    if (strlen($ans)) $ans .= ",";
    $ans .= $row['Email'];
  }
  return $ans;
}

$UpdateLog = '';

function Report_Log($roll) {
  global $Access_Type,$USER,$USERID,$UpdateLog,$MASTER_DATA;
  if ($UpdateLog) {
    if ($USER{'AccessLevel'} == $Access_Type['Participant']) {
      switch ($USER{'Subtype'}) {
      case 'Side':
        $Side = Get_Side($USERID);
        $who = $Side['SName'];
        break;
      default :
        return;
      }
    } else {
      $who = $USER['Login'];
    }

    $emails = Get_Emails($roll);
    if ($emails) {
      SendEmail($emails,$MASTER_DATA['ShortName'] . " update by $who",$UpdateLog);
    }
    Logg($MASTER_DATA['ShortName'] . " update by $who\n" . $UpdateLog);
    $UpdateLog = '';
  }
}

function Update_db($table,&$old,&$new,$proced=1) {
  global $db;
  global $TableIndexes;
  global $UpdateLog;

  $Flds = table_fields($table);
  $indxname = (isset($TableIndexes[$table])?$TableIndexes[$table]:'id');
  $newrec = "UPDATE $table SET ";
  $fcnt = 0;

//echo "<p>$newrec<p>";

  foreach ($Flds as $fname=>$ftype) {
    if ($indxname == $fname) { // Skip
    } elseif (isset($new[$fname])) {
      if ($ftype == 'text') {
        $dbform = addslashes($new[$fname]);
      } elseif ($ftype == 'tinyint' || $ftype == 'smallint') {
        $dbform = 0;
        if ($new[$fname]) {
          if ((string)(int)$new[$fname] = $new[$fname]) { $dbform = $new[$fname]; } else { $dbform = 1; };
        }
      } else {
        $dbform = $new[$fname];
      }

//echo "$fname " . $old[$fname] . " $dbform<br>";
      if ($dbform != $old[$fname]) {
        $old[$fname] = $dbform;
        if ($fcnt++ > 0) { $newrec .= " , "; }
        $newrec .= " $fname=" . '"' . $dbform . '"';
      }
    } else {
      if ($ftype == 'tinyint' || $ftype == 'smallint' ) {
        if ($old[$fname]) {
          $old[$fname] = 0;
            if ($fcnt++ > 0) { $newrec .= " , "; }
          $newrec .= " $fname=0";
        }
      } 
    }
  }

//echo "$fcnt<p>";
  if ($proced && $fcnt) {
    $newrec .= " WHERE $indxname=" . $old[$indxname];
//var_dump($newrec);
    $update = $db->query($newrec);
    $UpdateLog .= $newrec . "\n";
    if ($update) {
//      echo "<h2>$table Updated - $newrec</h2>\n";
//      echo "<h2>$table Updated</h2>\n";
    } else {
      echo "<h2 class=ERR>An error occoured: ((($newrec))) " . $db->error . "</h2>";
    }
    return $update;
  }
}

function Update_db_post($table, &$data, $proced=1) { 
  return Update_db($table,$data,$_POST,$proced);
}

function Insert_db($table, &$from, &$data=0, $proced=1) {
  global $db;
  global $TableIndexes;
  global $UpdateLog;
  $newrec = "INSERT INTO $table SET ";
  $fcnt = 0;
  $Flds = table_fields($table);
  $indxname = (isset($TableIndexes[$table])?$TableIndexes[$table]:'id');

  foreach ($Flds as $fname=>$ftype) {
    if (isset($from{$fname}) && $from{$fname} != '' && $indxname!=$fname ) { 
      if ($fcnt++ > 0) { $newrec .= " , "; }
      if ($ftype == 'text') {
        $dbform = addslashes($from{$fname});
        if ($data) $data[$fname] = $dbform;
        $newrec .= " $fname=" . '"' . $dbform . '"';
      } elseif ($ftype == "tinyint" || $ftype == 'smallint') {
        $dbform = 0;
        if ($from{$fname}) {
          if ((string)(int)$from{$fname} = $from{$fname}) { $dbform = $from{$fname}; } else { $dbform = 1; };
        }
        if ($data) $data[$fname] = $dbform;
        $newrec .= " $fname=$dbform ";
      } else {
        if ($data) $data[$fname] = $from[$fname];
        $newrec .= " $fname=$from[$fname] ";
      }
    }
  }
//var_dump($newrec);
  if ($proced) {
    $insert = $db->query($newrec);
    if ($insert) {
      $UpdateLog .= $newrec . "\n";
      $snum = $db->insert_id;
//      echo "<h2>$table New entry - $newrec - $snum</h2>";
//      echo "<h2>$table New entry added</h2>";
      if ($data) $data[$indxname]=$snum;
      $from[$indxname]=$snum;
      return $snum;
    } else {
      echo "<h2 class=ERR>An error occoured: ((($newrec))) " . $db->error . "</h2>";
    }
  }
  return 0;
}

function Insert_db_post($table,&$data,$proced=1) {
  $data['Dummy'] = 1;
  return Insert_db($table,$_POST,$data,$proced);  
}

function db_delete($table,$entry) {
  global $db,$TableIndexes;
  $indxname = (isset($TableIndexes[$table])?$TableIndexes[$table]:'id');
//echo "DELETE FROM $table WHERE $indxname='$entry'<p>";
  return $db->query("DELETE FROM $table WHERE $indxname='$entry'");
}

function db_delete_cond($table,$cond) {
  global $db;
  return $db->query("DELETE FROM $table WHERE $cond");
}

function db_update($table,$what,$where) {
  global $db;
  return $db->query("UPDATE $table SET $what WHERE $where");
}

function db_get($table,$cond) {
  global $db;
  $res = $db->query("SELECT * FROM $table WHERE $cond");
  if ($res) return $res->fetch_assoc();
  return 0;
}

// Read Master Data - this is NOT year specific - Get fest name, short name, version everything else is for future

function Get_Master_Data() {
  global $db,$MASTER_DATA;
  $res = $db->query("SELECT * FROM MasterData");
  if ($res) return $res->fetch_assoc();
}

$MASTER_DATA = Get_Master_Data();
$CALYEAR = gmdate('Y');
$SHOWYEAR = $MASTER_DATA['ShowYear'];
$YEAR = $PLANYEAR = $MASTER_DATA['PlanYear'];  //$YEAR can be overridden
$MASTER_DATA['V'] = $CALYEAR . "." . $MASTER_DATA['Version'];

function Feature($Name) {  // Return value of feature if set from Master_Data
  static $Features;
  global $MASTER_DATA;
  if (!$Features) {
    $Features = [];
    foreach (explode("\n",$MASTER_DATA['Features']) as $i=>$feat) {
      $Dat = explode(":",$feat,3);
      if ($Dat[0])$Features[$Dat[0]] = $Dat[1];
    }
  }
  if (isset($Features[$Name])) return $Features[$Name];
  return 0;
}

function set_ShowYear() { // Overrides default above if not set by a Y argument
  global $YEAR,$SHOWYEAR,$MASTER;
  if (!isset($_POST['Y']) && !isset($_GET['Y'])) {
    $YEAR = $SHOWYEAR;
    $MASTER = Get_General($YEAR);
  }
}

// Works for simple tables
// Deletes = 0 none, 1=one, 2=many
function UpdateMany($table,$Putfn,&$data,$Deletes=1,$Dateflds='',$Timeflds='',$Mstr='SName') {
  global $TableIndexes;
  include_once("DateTime.php");
  $Flds = table_fields($table);
  $DateFlds = explode(',',$Dateflds);
  $TimeFlds = explode(',',$Timeflds);
  $indxname = (isset($TableIndexes[$table])?$TableIndexes[$table]:'id');
  if (isset($_POST{'Update'})) {
    if ($data) foreach($data as $t) {
      $i = $t[$indxname];
      if (isset($_POST["$Mstr$i"]) && $_POST["$Mstr$i"] == '') {
        if ($Deletes) {
            db_delete($table,$t[$indxname]);
          if ($Deletes == 1) return 1;
        }
        continue;
      } else {
        foreach ($Flds as $fld=>$ftyp) {
          if ($fld == $indxname) continue;
          if (in_array($fld,$DateFlds)) {
            $t[$fld] = Date_BestGuess($_POST["$fld$i"]);
          } else if (in_array($fld,$TimeFlds)) {
            $t[$fld] = Time_BestGuess($_POST["$fld$i"]);
          } else if (isset($_POST["$fld$i"])) {
            $t[$fld] = $_POST["$fld$i"];
          }
        }
        $Putfn($t);
      }
    }
    if ($_POST[$Mstr . "0"] != '') {
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


?>

