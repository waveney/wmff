<?php

// If table's index is 'id' it does not need to be listed here
$TableIndexes = array(  'Sides'=>'SideId', 'SideYear'=>'syId', 'FestUsers'=>'UserId', 'Venues'=>'VenueId', 'Events'=>'EventId', 
                        'Bugs'=>'BugId', 'BigEvent'=>'BigEid', 'DanceTypes'=>'TypeId', 
                        'Directory'=>'DirId', 'Documents'=>'DocId', 'EventTypes'=>'ETypeNo',
                        'MusicTypes'=>'TypeId','TimeLine'=>'TLid', 'BandMembers'=>'BandMemId', 'ActYear'=>'ActId',
                        'TradeLocs'=>'TLocId','Trade'=>'Tid','TradeYear'=>'TYid','VolYear'=>'VYid'
                        );

function db_open () {
  global $db,$CONF;
  if (@ $CONF = parse_ini_file("Configuration.ini")) {
    @ $db = new mysqli($CONF['host'],$CONF['user'],$CONF['passwd'],$CONF['dbase']);
  } else {
    @ $db = new mysqli('localhost','wmff','','wmff');
    $CONF = ['dbase'=>'wmff'];
  }
  if (!$db || $db->connect_error ) die ('Could not connect: ' .  $db->connect_error);
}

db_open();

function Logg($what) {
  global $db,$USERID;
  $qry = "INSERT INTO LogFile SET Who='$USERID', changed='" . date('d/m/y H:i:s') . "', What='" . addslashes($what) . "'";
  $db->query($qry);
}

function table_fields($table) {
  global $db,$CONF;
  static $tables = array();
  if (isset($tables[$table])) return $tables[$table];

  $qry = "SELECT Column_Name, Data_type FROM information_schema.columns WHERE table_schema='" . $CONF['dbase'] ."' AND table_name='" . $table . "'";
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
  global $Access_Type,$USER,$USERID,$UpdateLog,$FESTSYS;
  if ($UpdateLog) {
    if ($USER{'AccessLevel'} == $Access_Type['Participant']) {
      switch ($USER{'Subtype'}) {
      case 'Side':
        $Side = Get_Side($USERID);
        $who = $Side['SN'];
        $Src = 1;
        $SrcId = $USERID;
        break;
        
      case 'Trade':
        $Trad = Get_Trader($USERID);
        $who = $Trad['SN'];
        $Src = 2;
        $SrcId = $USERID;
        break;
        
      default :
        $Src = 0;
        $SrcId = 0;        
        return;
      }
    } else {
      $who = $USER['Login'];
      $Src = 0;
    }

    $emails = Get_Emails($roll);
    if ($emails) {
      NewSendEmail($Src,$SrcId, $emails,$FESTSYS['ShortName'] . " update by $who",$UpdateLog);
    }
    Logg($FESTSYS['ShortName'] . " update by $who\n" . $UpdateLog);
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

//var_dump( $Flds);
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
      if (!isset($old[$fname]) || $dbform != $old[$fname]) {
        $old[$fname] = $dbform;
        if ($fcnt++ > 0) { $newrec .= " , "; }
        $newrec .= " $fname=" . '"' . $dbform . '"';
      }
    } else {
      if ($ftype == 'tinyint' || $ftype == 'smallint' ) {
//      if ($fname == 'InUse') debug_print_backtrace();
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
//echo "Fields: "; var_dump($from);
  foreach ($Flds as $fname=>$ftype) {
    if (isset($from[$fname]) && $from[$fname] != '' && $indxname!=$fname ) { 
      if ($fcnt++ > 0) { $newrec .= " , "; }
      if ($ftype == 'text') {
        $dbform = addslashes($from[$fname]);
        if ($data) $data[$fname] = $dbform;
        $newrec .= " $fname=" . '"' . $dbform . '"';
      } elseif ($ftype == "tinyint" || $ftype == 'smallint') {
        $dbform = 0;
        if ($from{$fname}) {
          if ((string)(int)$from[$fname] = $from[$fname]) { $dbform = $from[$fname]; } else { $dbform = 1; };
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

// Read YEARDATA Data - this is NOT year specific - Get fest name, short name, version everything else is for future

function Get_FESTSYS() {
  global $db,$FESTSYS;
  $res = $db->query("SELECT * FROM MasterData");
  if ($res) return $res->fetch_assoc();
}

$FESTSYS = Get_FESTSYS();
$CALYEAR = gmdate('Y');
$SHOWYEAR = $FESTSYS['ShowYear'];
$YEAR = $PLANYEAR = $FESTSYS['PlanYear'];  //$YEAR can be overridden
include_once("Version.php");
$FESTSYS['V'] = $CALYEAR . ".$VERSION";

function Feature($Name,$default='') {  // Return value of feature if set from FESTSYS
  static $Features;
  global $FESTSYS;
  if (!$Features) {
    $Features = [];
    foreach (explode("\n",$FESTSYS['Features']) as $i=>$feat) {
      $Dat = explode(":",$feat,4);
      if ($Dat[0] && isset($Dat[1])) {
        $Features[$Dat[0]] = trim($Dat[1]);
      } elseif ($Dat[0] && isset($Dat[4])) {
        $Features[$Dat[0]] = trim($Dat[4]);
      }
    }
  }
  if (isset($Features[$Name])) return $Features[$Name];
  return $default;
}

function FestFeature($Name,$default='') {  // Return value of feature if set from FESTSYS
  static $Features;
  global $YEARDATA;
  if (!$Features) {
    $Features = [];
    foreach (explode("\n",$YEARDATA['FestFeatures']) as $i=>$feat) {
      $Dat = explode(":",$feat,4);
      if ($Dat[0] && isset($Dat[1])) {
        $Features[$Dat[0]] = trim($Dat[1]);
      } elseif ($Dat[0] && isset($Dat[4])) {
        $Features[$Dat[0]] = trim($Dat[4]);
      }
    }
  }
  if (isset($Features[$Name])) return $Features[$Name];
  return $default;
}

function Capability($Name,$default='') {  // Return value of Capability if set from FESTSYS
  static $Capabilities;
  global $FESTSYS;
  if (!$Capabilities) {
    $Capabilities = [];
    foreach (explode("\n",$FESTSYS['Capabilities']) as $i=>$Cape) {
      $Dat = explode(":",$Cape,3);
      if ($Dat[0])$Capabilities[$Dat[0]] = trim($Dat[1]);
    }
  }
  if (isset($Capabilities[$Name])) return $Capabilities[$Name];
  return $default;
}

function set_ShowYear($last=0) { // Overrides default above if not set by a Y argument
  global $YEAR,$SHOWYEAR,$YEARDATA,$NEXTYEARDATA;
  if ($last == 0 && !isset($_REQUEST['Y'])) {
    $YEAR = $SHOWYEAR;
    $YEARDATA = Get_General($YEAR);
  } else if (!isset($_POST['Y']) && !isset($_GET['Y'])) {
    $YEAR = $last;
    $YEARDATA = Get_General($YEAR);
  }
  if ($YEARDATA['Years2Show'] > 0) {
    $NEXTYEARDATA = Get_General($YEARDATA['NextFest']);
  }
}

// Works for simple tables
// Deletes = 0 none, 1=one, 2=many
function UpdateMany($table,$Putfn,&$data,$Deletes=1,$Dateflds='',$Timeflds='',$Mstr='SN',$MstrNot='') {
  global $TableIndexes;
  include_once("DateTime.php");
  $Flds = table_fields($table);
  $DateFlds = explode(',',$Dateflds);
  $TimeFlds = explode(',',$Timeflds);
  $indxname = (isset($TableIndexes[$table])?$TableIndexes[$table]:'id');

//var_dump($_POST);
//return;
  if (isset($_POST{'Update'})) {
    if ($data) foreach($data as $t) {
      $i = $t[$indxname];
      if ($i) {
        if (isset($_POST["$Mstr$i"]) && $_POST["$Mstr$i"] == $MstrNot) {
          if ($Deletes) {
//          echo "Would delete " . $t[$indxname] . "<br>";
              db_delete($table,$t[$indxname]);
            if ($Deletes == 1) return 1;
          }
          continue;
        } else {
          $recpres = 0;
          foreach ($Flds as $fld=>$ftyp) {
            if ($fld == $indxname) continue;
            if (in_array($fld,$DateFlds)) {
              $t[$fld] = Date_BestGuess($_POST["$fld$i"]);
              $recpres = 1;
            } else if (in_array($fld,$TimeFlds)) {
              $t[$fld] = Time_BestGuess($_POST["$fld$i"]);
              $recpres = 1;
            } else if (isset($_POST["$fld$i"])) {
              $t[$fld] = $_POST["$fld$i"];
              $recpres = 1;
            } else {
              $t[$fld] = 0;
            }
          }
//          var_dump($t);
//          return;
          if ($recpres) $Putfn($t);
        }
      }
    }
    if ($_POST[$Mstr . "0"] != $MstrNot) {
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

