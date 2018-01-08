<?php

/* Various common code across fest con tools */

$YEAR = $THISYEAR = 2018;

if (isset($_POST{'Y'})) $YEAR = $_POST{'Y'};
if (isset($_GET{'Y'})) $YEAR = $_GET{'Y'};

$Noise_Levels = array("Middling","Quiet","Noisy");
$Coming_States = array('','Recieved','Coming','Not coming','Possibly','Not coming, please ask next year');
$Coming_idx = array('','R','Y','N','P','NY');
$Coming_Type = array_flip($Coming_idx);
$Invite_States = array('','Yes','YES!','No','Maybe');
$Invite_Type = array_flip($Invite_States);
$Surfaces = array ('','Tarmac','Flagstones','Grass','Stage','Astroturf','Wood');
$Side_Statuses = array("Alive","Dead");
$Share_Spots = array('Prefered','Always','Never','Sometimes');
$Share_Type = array_flip($Share_Spots);
$Access_Levels = array('','Participant','Upload','Steward','Staff','Committee','SysAdmin','Internal');
$Access_Type = array_flip($Access_Levels);
$Area_Levels = array( 'No','Edit','Edit and Report');
$Area_Type = array_flip($Area_Levels);
$Sections = array( 'Docs','Dance','Stalls','Users','Venues','Music','Sponsors','Comedy','Craft','Other','OldAdmin','Bugs','Photos');
$Importance = array('None','Some','High','Very High','Even Higher','Highest','The Queen');
$ProgLevels = array('None','Early Draft','Draft','Provisional','','Final');
$OverlapStates = array('','Major Musician','Minor Musician','Major Dancer','Minor Dancer','Major Other','Minor Other');
$Overlap_Type = array_flip($OverlapStates);
$OverlapDays = array('','Fri Only','Sat Only','Sun Only');
$Part_Cats = array('Dance'=>'Side','Music'=>'Act','Other'=>'Other');
$Cat_Parts = array_flip($Part_Cats);
$Part_Types = array_values($Part_Cats);
$Cat_Types = array_flip($Part_Types);
$Cat_Stages = array('Started','Invites','Provisional','Details','Programme','History');
$Cat_Stage = array_flip($Cat_Stages);
$Book_States = array('None','Declined','Booking','Contract Ready','Booked');
$Book_State = array_flip($Book_States);
$InsuranceStates = array('None','Uploaded','Checked');
$Book_Actions = array('None'=>'Book','Declined'=>'Book,Contract','Booking'=>'Contract,Decline,Cancel','Contract Ready'=>'Confirm,Decline,Cancel',
		'Booked'=>'Cancel,Decline');
$Book_Colour = array('None'=>'White','Declined'=>'pink','Booking'=>'yellow','Contract Ready'=>'orange','Booked'=>'green');


// If table's index is 'id' it does not need to be listed here
$TableIndexes = array(	'Sides'=>'SideId', 'SideYear'=>'syId', 'FestUsers'=>'UserId', 'Venues'=>'VenueId', 'Events'=>'EventId', 
			'General'=>'Year', 'Bugs'=>'BugId', 'BigEvent'=>'BigEid', 'DanceTypes'=>'TypeId', 
			'Directory'=>'DirId', 'Documents'=>'DocId', 'EventTypes'=>'ETypeNo',
			'MusicTypes'=>'TypeId','TimeLine'=>'TLid', 'BandMembers'=>'BandMemId', 'ActYear'=>'ActId',
			'TradeLocs'=>'TLocId','Trade'=>'Tid','TradeYear'=>'TYid'
			);

date_default_timezone_set('GMT');

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

function Set_User() {
  global $db,$USER,$USERID,$AccessType,$YEAR,$THISYEAR;
  if (isset($USER)) return;
  $USER = array();
  $USERID = 0;
  if (isset($_COOKIE{'WMFFD'})) {
    $biscuit = $_COOKIE{'WMFFD'};
    $Cake = openssl_decrypt($biscuit,'aes-128-ctr','Quarterjack',0,'BrianMBispHarris');
    $crumbs = split(':',$Cake);
    $USER{'Subtype'} = $crumbs[0];
    $USER{'AccessLevel'} = $crumbs[1];
    $USERID = $USER{'UserId'} = $crumbs[2];
    if ($USERID) return;
    $USER = array();
    $USERID = 0;
  }
  if (isset($_COOKIE{'WMFF2'})) {
    $res=$db->query("SELECT * FROM FestUsers WHERE Yale='" . $_COOKIE{'WMFF2'} . "'");
    if ($res) {
      $USER = $res->fetch_assoc();
      $USERID = $USER['UserId'];
      $db->query("UPDATE FestUsers SET LastAccess='" . time() . "' WHERE UserId=$USERID" );
    }
  } 
  if (isset($_COOKIE{'WMFF'})) {
    $res=$db->query("SELECT * FROM FestUsers WHERE Yale='" . $_COOKIE{'WMFF'} . "'");
    if ($res) {
      $USER = $res->fetch_assoc();
      $USERID = $USER['UserId'];
      $db->query("UPDATE FestUsers SET LastAccess='" . time() . "' WHERE UserId=$USERID" );
      setcookie('WMFF2',$USER['Yale'], mktime(0,0,0,1,1,$THISYEAR+1) ,'/' );
      setcookie('WMFF','',-1);
      $_COOKIE{'WMFF2'} = $ans['Yale'];
    }
  } 
}

function Access($level,$subtype=0,$thing=0) {
  global $Access_Type,$USER,$USERID;
  $want = $Access_Type[$level];
  Set_User();
  if (!isset($USER{'AccessLevel'})) return 0;
  if ($USER{'AccessLevel'} > $want) return 1;
  if ($USER{'AccessLevel'} < $want) return 0;
  switch  ($USER{'AccessLevel'}) {

  case $Access_Type['Participant'] : 
    if ($USER{'Subtype'} != $subtype) return 0;
    return $thing == $USERID;

  case $Access_Type['Upload'] :
  case $Access_Type['Staff'] :
  case $Access_Type['Steward'] :
    if ($USER{'AccessLevel'} > $want) return 1;
    if ($USER{'AccessLevel'} == $want) {
      if (!$subtype) return 1;
      if (isset($USER[$subtype]) && $USER[$subtype]) return 1;
    }
    return 0; // For now


  case $Access_Type['Committee'] :
    if (!$subtype) return 1;
    if (isset($USER[$subtype]) && $USER[$subtype]) return 1;
    return 0;

  case $Access_Type['SysAdmin'] : 
    return 1;

  case $Access_Type['Internal'] : 
    return 1;

  default:
    return 0;
  }
}

/*
  If not in session 
    If Yale then
      Find User from Yale, start session
      if not found - Login page
    else 
      Login page
  endif
  if AccessOK return
  else isuf priv page
*/

function A_Check($level,$subtype=0,$thing=0) {
  global $Access_Type,$USER,$USERID;
  global $db;
  Set_User();
  if (!$USERID) {
    include("int/Login.php");
    Login();
  }
  if (Access($level,$subtype,$thing)) return;

//echo "Failed checking...";
//exit;
  Error_Page("Insufficient Privilages");
}

function rand_string($len) {
  $ans= '';
  while($len--) $ans .= chr(rand(65,90));
  return $ans;
}

$HelpTable = 0;

function Set_Help_Table(&$table) {
  global $HelpTable;
  $HelpTable = $table;
}

function Add_Help_Table(&$table) {
  global $HelpTable;
  $HelpTable = array_merge($HelpTable,$table);
}

function help($fld) {
  global $HelpTable;
  if (!isset($HelpTable[$fld])) return;
  return " <img src=/images/icons/help.png id=Help4$fld title='" . $HelpTable[$fld] . "'> ";
}

function htmlspec($data) {
  return utf8_decode(htmlspecialchars(utf8_encode(stripslashes($data)), ENT_COMPAT|ENT_SUBSTITUTE));
}

$ADDALL = '';

function fm_addall($txt) {
  global $ADDALL;
  $ADDALL = $txt;
}

function fm_textinput($field,$value='',$extra='') {
  global $ADDALL;
  $str = "<input type=text name=$field $extra size=16 $ADDALL";
  if ($value) $str .= " value=\"" . htmlspec($value) . '"';
  return $str  .">";
}

function fm_smalltext($Name,$field,$value,$chars=4,$extra='') {
  global $ADDALL;
  $str = "$Name " . help($field) . "<input type=text name=$field $extra size=$chars $ADDALL";
  $str .= " value=\"" . htmlspec($value) . '"';
  return $str  .">";
}

function fm_text($Name,&$data,$field,$cols=1,$extra1='',$extra2='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  $str = "<td $extra1>$Name" . ($Name?':':'') . help($field) . "<td colspan=$cols $extra1><input type=text name=$field2 $extra2 size=" . $cols*16; 
  if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) ."\"";
  return $str . " $ADDALL>";
}

function fm_text1($Name,&$data,$field,$cols=1,$extra1='',$extra2='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  $str = "<td colspan=$cols $extra1>$Name" . ($Name?':':'') . help($field) . "<input type=text name=$field2 $extra2 size=" . $cols*16; 
  if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) ."\"";
  return $str . " $ADDALL>";
}

function fm_simpletext($Name,&$data=0,$field,$extra='') {
  global $ADDALL;
  $str = "$Name: " . help($field) . "<input type=text name=$field $extra";
  if ($data) if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) . "\"";
  return $str . " $ADDALL>\n";
}

function fm_number1($Name,&$data=0,$field,$extra1='',$extra2='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  $str = "<td $extra1>";
  if ($Name) $str .= "$Name: ";
  $str .= help($field) . "<input type=number name=$field2 $extra2";
  if ($data) if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) . "\"";
  return $str . " $ADDALL>\n";
}

function fm_number($Name,&$data=0,$field,$extra1='',$extra2='') {
  global $ADDALL;
  $str = "<td $extra1>$Name: " . help($field) . "<td $extra1><input type=number name=$field $extra2";
  if ($data) if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) . "\"";
  return $str . " $ADDALL>\n";
}

function fm_nontext($Name,&$data,$field,$cols=1,$extra='') {
  global $ADDALL;
  $str = "<td $extra>$Name:" . help($field) . "<td colspan=$cols $extra>";
  return $str . (isset($data[$field]) ? htmlspec($data[$field]) : '');
}

function fm_time($Name,&$data,$field,$cols=1,$extra='') {
  global $ADDALL;
  return "<td>$Name:" . help($field) . "<td colspan=$cols><input type=time name=$field $extra size=" . $cols*16 .
	" value=\"" . $data[$field] ."\" $ADDALL>";
}

function fm_hidden($field,$value,$extra='') {
  global $ADDALL;
  return "<input type=hidden name=$field $extra value=\"" . htmlspec($value) ."\">";
}

function fm_textarea($Name,&$data,$field,$cols=1,$rows=1,$extra1='',$extra2='') {
  global $ADDALL;
  $str = "<td $extra1>$Name:" . help($field) . "<td colspan=$cols $extra1><textarea name=$field $ADDALL $extra2 rows=$rows cols=" .$cols*20 . ">" ;
  return $str . (isset($data[$field])?	htmlspec($data[$field]) : '' ) . "</textarea>\n";
}

function fm_basictextarea(&$data,$field,$cols=1,$rows=1,$extra1='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  $str = "<textarea name=$field2 $ADDALL $extra1 rows=$rows cols=" .$cols*20 . ">" ;
  return $str . (isset($data[$field])?	htmlspec($data[$field]) : '' ) . "</textarea>\n";
}

function fm_checkbox($Desc,&$data,$field,$extra='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  if (isset($data[$field])) if ($data[$field]) return ($Desc?"$Desc:":'') . help($field) . "<input type=checkbox $ADDALL Name=$field2 $extra checked>";
  return ($Desc?"$Desc:":'') . help($field) . "<input type=checkbox $ADDALL Name=$field2 $extra>";
}

function fm_select2(&$Options,$Curr,$field,$blank=0,$selopt='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  $str = "<select name=$field2 $ADDALL $selopt>";
  if ($blank) {
    $str .= "<option value=''";
    if ($Curr == 0) $str .= " selected";
    $str .= "></option>";
  }
  foreach ($Options as $key => $val) {
    $str .= "<option value=$key";
    if ($Curr == $key) $str .= " selected";
    $str .= ">" . htmlspec($val) . "</option>";
  }
  $str .= "</select>" . help($field) . "\n";
  return $str;
}

function fm_select(&$Options,$data,$field,$blank=0,$selopt='',$field2='') {
  if (isset($data[$field])) return fm_select2($Options,$data[$field],$field,$blank,$selopt,$field2);
  return fm_select2($Options,'@@@@@@',$field,$blank,$selopt,$field2);
}

function fm_radio($Desc,&$defn,&$data,$field,$extra='',$tabs=1,$extra2='') {
  global $ADDALL;
  $str = "";
  if ($tabs) $str .= "<td $extra>"; 
  if ($Desc) $str .= "$Desc:";
  $str .= help($field) . " ";
  if ($tabs) $str .= "<td $extra2>"; 
  $done = 0;
  foreach($defn as $i=>$d) {
    if (!$d) continue;
    if ($done && $tabs == 2) $str.= "<br>";
    $done = 1;
    $str .= "$d:";
    $str .= "<input type=radio name=$field $ADDALL $extra value='$i'";
    if ($data[$field] == $i) $str .= " checked";
    $str .= ">\n";
  }
  return $str;
}

function fm_date($Name,&$data,$field,$extra1='',$extra2='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  $str = "<td $extra1>$Name" . ($Name?':':'') . help($field) . "<td $extra1><input type=text name=$field2 $extra2 size=16"; 
  if (isset($data[$field]) && $data[$field]) $str .= " value=\"" . date('j M Y H:i',$data[$field]) . "\"";
  return $str . " $ADDALL>";
}

function fm_date1($Name,&$data,$field,$extra1='',$extra2='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  $str = "<td $extra1>$Name" . ($Name?':':'') . help($field) . "<input type=text name=$field2 $extra2 size=16"; 
  if (isset($data[$field]) && $data[$field]) $str .= " value=\"" . date('j M Y H:i',$data[$field]) ."\"";
  return $str . " $ADDALL>";
}

function SendEmail($to,$sub,$letter,$headopt='') {
//  $url = 'http://www.wimbornefolk.org/RemoteEmail.php';
  if (file_exists('testing')) return;
  $url = 'http://moonblink.info/RemoteEmail.php';
  $data = array('TO' => $to, 'SUBJECT' => $sub, 'CONTENT'=>$letter, 'KEY' => 'UGgugue2eun23@', 'HEADER' => $headopt);

  // use key 'http' even if you send the request to https://...
  $options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
  );
  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  if ($result === FALSE) { /* Handle error */ }
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

function Disp_CB($what) {
  echo "<td>" . ($what?'Y':'');
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
  global $Access_Type,$USER,$USERID,$UpdateLog;
  if ($UpdateLog) {
    if ($USER{'AccessLevel'} == $Access_Type['Participant']) {
      switch ($USER{'Subtype'}) {
      case 'Side':
        $Side = Get_Side($USERID);
        $who = $Side['Name'];
        break;
      default :
        return;
      }
    }

    $emails = Get_Emails($roll);
    if ($emails) {
      SendEmail($emails,"WMFF update by $who",$UpdateLog);
    }
    Logg("WMFF update by $who\n" . $UpdateLog);
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
  
  if ($proced && $fcnt) {
    $newrec .= " WHERE $indxname=" . $old[$indxname];
if ($table == 'ActYear') var_dump($newrec);
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
      } elseif ($ftype == "tinyint" || sftype == 'smallint') {
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

function weblink($dest,$text='Website',$alink='',$all=0) {
  $dest = stripslashes($dest);
  $sites = split(' ',$dest);
  if (count($sites) > 1) {
    $ans = '';
    foreach($sites as $site) {
      $ans .= "<a $alink target=_blank href='";
      if (!preg_match("/^https?/",$site)) $ans .= 'http://';
      $ans .= "$site'>";
      preg_match("/^(https?:\/\/)?(.*?)(\/|$)/",$site,$m);
      $ans .= $m[2];
      $ans .= "</a> ";
      if ($all==0) break;
    }
    return $ans;      
  } else {
    if (preg_match("/^http/",$dest)) return "<a href='$dest' $alink target=_blank>$text</a>";
    return "<a href='http://$dest' $alink target=_blank>$text</a>";
  }
}

function weblinksimple($dest) {
  $dest = stripslashes($dest);
  $ans = "<a target=_blank href='";
  if (!preg_match("/^https?/",$dest)) $ans .= 'http://';
  $ans .= "$dest'>";
  return $ans;      
}

function videolink($dest) {
  $dest = stripslashes($dest);
  if (preg_match("/^http/",$dest)) return "'" . $dest ."'";
  if (preg_match('/watch\?v=',$dest)) {
    return preg_replace("/.*watch\?v=/", 'youtu.be/', $dest);
  } else if (preg_match('/src="(.*?)" /i',$dest,$match)) {
    return preg_replace("/www.youtube.com\/embed/", 'youtu.be', $match[1]);
  }
  return "'http://" . $dest ."'";
}

function embedvideo($dest) {
  $dest = stripslashes($dest);
  if (preg_match("/<iframe.*src/i",$dest)) return $dest;
  if (preg_match('/.*watch\?v=(.*)/',$dest,$mtch)) {
    $dest = $mtch[1];
  } else {
    $dest = preg_replace("/.*tu.be/i",'',$dest);
  }
  return "<iframe width=560 height=315 src='https://www.youtube.com/embed/" . $dest . "' frameborder=0 allowfullscreen></iframe>";
}

function Clean_Email(&$addr) {
  if (preg_match('/<([^>]*)>?/',$addr,$a)) return $addr=trim($a[1]);
  if (preg_match('/([^>]*)>?/',$addr,$a)) return $addr=trim($a[1]);
  $addr = preg_replace('/ */','',$addr);
  return $addr = trim($addr);;
}

/*
function linkemail(&$data,$type="Side",$xtr='') {
  global $YEAR,$USER;
  include_once("DanceLib.php");
  if (!isset($data[$xtr .'Email'])) return "";
  $email = $data[$xtr .'Email'];
  if ($email == '') return "";
  $email = Clean_Email($email);
  $key = $data['AccessKey'];
  if (isset($data['Contact'])) { $name = firstword($data['Contact']); }
  else { $name = $data['Name']; }
  if ($type="Side") { $id = $data['SideId']; }
  else { $id = $data = $data['ActId']; };

  $ProgInfo = Show_Prog($type,$id,1);

  $lnk = "<a href=mailto:$email?from=" . $USER['Email'] .
	 "&subject=" . urlencode("Wimborne Minster Folk Festival $YEAR and " . $data['Name']) . 
         "&body=" . urlencode("$name,\n\n" .
	 	"You can check your programme times and update your side details at any time by visiting " .
	 	"<a href=http://wimbornefolk.co.uk/int/Direct.php?t=$type&id=$id&key=$key>this link</a>.  " .
		$ProgInfo . "\n\n" .
		"PUT MESSAGE HERE\n\n" .
	 	"\n\nRegards " . $USER['Name'] . "\n\n") .
	 ">Email</a>";
  return $lnk;
}

function newlinkemailhtml(&$d,$type="Side",$xtr='') { // does notwork yet
  $id = $d['SideId'];
  return "<button onclick=emailclk($id,'$type','$xtr') target='_blank' type=button>$xtr Email</button>"; 
}
*/

function linkemailhtml(&$data,$type="Side",$xtr='',$ButtonExtra='') {
  global $YEAR,$USER;
  include_once("DanceLib.php");
  $Label = '';
  if (isset($data['HasAgent']) && ($data['HasAgent'])) {
    if ($xtr == '') {
      if (!isset($data["AgentEmail"])) return "";
      $email = $data['AgentEmail'];
      $xtr = 'Agent';
    } else if ($xtr == '!!') {
      if (!isset($data["Email"])) return "";
      $email = $data['Email'];
      $xtr = '';
      $Label = 'Direct ';
    } else {
      if (!isset($data[$xtr . "Email"])) return "";
      $email = $data[$xtr . 'Email'];
      $Label = $xtr;
    }
  } else {
    if ($xtr == '!!') $xtr = '';
    if (!isset($data[$xtr . "Email"])) return "";
    $email = $data[$xtr . 'Email'];
    $Label = $xtr;
  }
  if ($email == '') return "";
  $email = Clean_Email($email);
  $key = $data['AccessKey'];
  if (isset($data[$xtr .'Contact'])) { $name = firstword($data[$xtr .'Contact']); }
  else { $name = $data['Name']; }
  if (isset($data['SideId'])) {
    $id = $data['SideId'];
  } else if (isset($data['Tid'])) {
    $id = $data['Tid'];
  }

  $link = "'mailto:$email?from=" . $USER['Email'] .
	 "&subject=" . urlencode("Wimborne Minster Folk Festival $YEAR and " . $data['Name']) . "'";

// ONLY DANCE AT THE MOMENT...
  switch ($type) {
    case 'Side':
    case 'Dance':
      $ProgInfo = Show_Prog($type,$id);
      $Content = urlencode("$name,<p>" .
	 	"<div id=SideLink$id>" .
		"Please add/correct details about your side's contact information and your preferences in " .
		"terms of days coming, number of dance spots, etc. by visiting " .
	 	"<a href=http://wimbornefolk.co.uk/int/Direct.php?t=$type&id=$id&key=$key>this link</a>.</div>  " .
		"You can update information at any time, until the programme goes to print. " .
		"(You'll also be able to view your programme times, once we've done the programme)<p>" .
		"<div id=SideProg$id>$ProgInfo</div><p>" .
		"PUT MESSAGE HERE<p>" .
	 	"Regards " . $USER['Name'] . "<p>"); 
      break;

    case 'Act':
    case 'Music':
      include_once("Contract.php");
      $p = -1;
      if ($data['YearState'] == $Book_State['Booked']) { $p = 1; }
      else if ($data['YearState'] == $Book_State['Contract Ready']) { $p = 0; }
      $Cont = Show_Contract($id,$p);
      $Content = urlencode("$name,<p>" .
	 	"<div id=SideLink$id>" .
		"Please add/correct details about your Act's contact information" . 
		($data['YearState'] == $Book_State['Contract Ready']?", confirm your contract":", confirm your contract when it is complete") . 
		" and update your preferences and listing etc. by visiting " .
	 	"<a href=http://wimbornefolk.co.uk/int/Direct.php?t=$type&id=$id&key=$key>this link</a>.</div><p>  " .
		"PUT MESSAGE HERE<p>" .
	 	"Regards " . $USER['Name'] . "<p>" .
		"<div id=SideProg$id>$Cont</div><p>" 
		); 
      break;

    case 'Trade':
    case 'trade':
      $Content = urlencode("$name,<p>" .
	 	"<div id=SideLink$id>" .
		"Please add/correct details about your business, contact information, your product descriptions, pitch and power requirements, " . 
		"update your Insurance and Risc Assessment etc. by visiting " .
	 	"<a href=http://wimbornefolk.co.uk/int/Direct.php?t=trade&id=$id&key=$key>this link</a>.</div><p>  " .
		"Details of your pitch location, general trader information and particulars of setup and cleardown information will also appear there.<p>" .
		"PUT MESSAGE HERE<p>" .
	 	"Regards " . $USER['Name'] . "<p>" 
		); 
      break;

// For OTHER at present
    default:
      $Content = urlencode("$name,<p>" .
		"PUT MESSAGE HERE<p>" .
	 	"Regards " . $USER['Name'] . "<p>"); 
      break;
  }

  $lnk = "<button onclick=\"emailclk($link,'Email$id'); $ButtonExtra\" id=Em$id target='_blank' type=button>$Label Email</button>" .
         "<div hidden><div id=Email$id>$Content</div></div>";
  return $lnk;
}


function Get_User($who,&$newdata=0) {
  global $db;
  static $Save_User;

  if (isset($Save_User[$who])) {
    $ret = $Save_User[$who];
    if ($newdata) $Save_User[$who] = $newdata;
    return $ret;
  }
  $qry = "SELECT * FROM FestUsers WHERE Login='$who' OR Email='$who' OR UserId='$who'";
  $res = $db->query($qry);

  if (!$res || $res->num_rows == 0) return 0;
  $data = $res->fetch_assoc();
  $Save_User[$who] = ($newdata ? $newdata : $data);
  return $data;
}

function Put_User(&$data,$Save_User=0) {
  if (!$Save_User) $Save_User = Get_User($data['UserId'],$data);
  Update_db('FestUsers',$Save_User,$data);
}

function Error_Page ($message) {
  global $Access_Type,$USER,$USERID;
  if (isset($USER{'AccessLevel'})) { $type = $USER{'AccessLevel'}; } else { $type = 0; }
  switch ($type) {
  case $Access_Type['Participant'] :
    switch ( $USER{'Subtype'}) {
    case 'Side' :
    case 'Act' :
      include_once('int/MusicLib.php');
      include_once('int/DanceLib.php');
      Show_Side($USERID);
      exit;
    case 'Stall' :
    case 'Sponsor' :
    case 'Other' :
    default:
      include("index.php");
      exit;
    }

  case $Access_Type['Committee'] :
  case $Access_Type['Steward'] :
  case $Access_Type['Staff'] :
  case $Access_Type['Upload'] :
    $ErrorMessage = $message;
    include_once('int/Staff.php');  // Should be good
    exit;			// Just in case
  case $Access_Type['SysAdmin'] :
  case $Access_Type['Internal'] :
    $ErrorMessage = "Something went very wrong... - $message";
    include_once('int/Staff.php');  // Should be good
    exit;			// Just in case
    
  default:
    include("index.php"); 
  }

}

function formatBytes($size, $precision = 2) {
  if ($size==0) return 0;
  $base = log($size, 1024);
  $suffixes = array('', 'K', 'M', 'G', 'T', 'P');   
  return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}

function Get_General($y=0) {
  global $db,$YEAR;
  if (!$y) $y=$YEAR;
  $res = $db->query("SELECT * FROM General WHERE Year=$y");
  if ($res) return $res->fetch_assoc();
}

function Get_Years() {
  global $db;
  $res = $db->query("SELECT * FROM General ORDER BY Year");
  $Gens = array();
  if ($res) {
    while ($stuff = $res->fetch_assoc()) { $Gens[$stuff['Year']] = $stuff; };
  }
  return $Gens;
}

$MASTER = Get_General();
$MASTER['V'] = gmdate('Y') . "." . $MASTER['Version'];

function First_Sent($stuff) {
  $onefifty=substr($stuff,0,150);
  return (preg_match('/^(.*?[.!?])\s/s',$onefifty,$m) ? $m[1] : $onefifty);
}

function firstword($stuff) {
  if (preg_match('/(\S*?)\s/',$stuff,$s)) return $s[1];
  return $stuff;
}

function SAO_Report($i) {
  $OSide = Get_Side( $i ); 
  $str = $OSide['Name'];
  if ($OSide['Type']) $str .= " ( " . trim($OSide['Type']) . " )";
  return $str;
}

function SName(&$What) {
  if (isset($What['ShortName'])) if ($What['ShortName']) return $What['ShortName'];
  return $What['Name'];
}

function Social_Link(&$data,$site,$mode=0) { // mode:0 Return Site as text, mode 1: return blank/icon
  if (! isset($data[$site]) || strlen($data[$site]) < 5) return ($mode? '' :$site);
  $link = $data[$site];
  if (preg_match("/$site/i",$link)) {
    return weblink($link,($mode? ( "<img src=/images/icons/$site.jpg>") : $site));
  }
  return "<a href=http://$site.com/$link>" . ($mode? ( "<img src=/images/icons/$site.jpg>") : $site) . "</a>";
}

function Show_Prog($type,$id,$mode=0,$all=0) { //mode 0 = html, 1 = text for email
    global $DayList,$ProgLevels,$MASTER,$Cat_Stages,$Cat_Type,$Cat_Stage,$Cat_Parts;
    if ($MASTER[$Cat_Parts[$type] . 'State'] < $Cat_Stage['Programme']) return;
//var_dump($Cat_Parts[$type],$Cat_Stage['Programme']);
    $str = '';
    include_once("ProgLib.php");
    include_once("DanceLib.php");
    $Evs = Get_All_Events_For($type,$id);
//var_dump($Evs);
    $evc=0;
    $Venues = Get_Venues(1);
    if ($Evs) {
      foreach ($Evs as $e) {
        if ($e['Public'] == 1 || $all ||
	    ($e['Public'] == 0 && $MASTER[$type . 'ProgLevel']>2 || ($MASTER[$type . 'ProgLevel']>0 && Access('Participant',$type,$id)))) {
	  if ($evc++ == 0) {
	    $Thing = Get_Side($id);
	    if ($mode) {
	      $str .= $ProgLevels[$MASTER[$type . 'ProgLevel']] . " Programme for " . $Thing['Name'] . ":\n\n";
	    } else {
	      $str .= "<h2>" . $ProgLevels[$MASTER[$type . 'ProgLevel']] . " Programme for " . $Thing['Name'] . ":</h2>\n";
	      $str .= "<table border><tr><td>Day<td>time<td>Event<td>Venue<td>With\n";
	    }
	  };
	  if ($e['BigEvent']) { // Big Event
	    $Others = Get_Other_Things_For($e['EventId']);
	    $VenC=0;
	    $PrevI=0;
	    $NextI=0;
	    $PrevT=0;
	    $NextT=0;
	    $Found=0;
	    $Position=1;
	    foreach ($Others as $O) {
	      switch ($O['Type']) {
	      case 'Side':
	      case 'Act':
	      case 'Other':
		if ($O['Identifier'] == $id && $O['Type'] == $type) { 
		  $Found = 1; 
		} else {
		  if ($Found && $NextI==0) { $NextI=$O['Identifier']; $NextT=$O['Type']; }
		  if (!$Found) { $PrevI=$O['Identifier']; $PrevT=$O['Type']; $Position++; }
		}
		break;
	      case 'Venue':
		$VenC++;
	      default:
		break;
	      }
	    }
	    if ($mode) {
	      $str .= $DayList[$e['Day']] . " Starting at: " . $e['Start'] . " Event: " . $e['Name'] ;
	      if ($VenC) {
		$str .= " Starting location: " . VenName($Venues[$e['Venue']]) ;
	      } else {
		$str .= " Location: " . VenName($Venues[$e['Venue']]) ;
	      }
	      $str .= " In position $Position ";
	    } else {
	      $str .= "<tr><td>" . $DayList[$e['Day']] . "<td>" . $e['Start'] . "-" . ($e['SubEvent'] < 0 ? $e['SlotEnd'] : $e['End'] ) .
			"<td>" . $e['Name'] . "<td>";
	      if ($VenC) $str .= " starting from ";
	      $str .= VenName($Venues[$e['Venue']]) ;
	      $str .= "<td>In position $Position";
	    }
	    if ($PrevI) { $str .= ", After " . SAO_Report($PrevI); };
	    if ($NextI) { $str .= ", Before " . SAO_Report($NextI); };
	    $str .= "\n";
	  } else { // Normal Event
	    if ($mode) {
	      $str .= $DayList[$e['Day']] . " Starting at:" . $e['Start'] . " Event: " . $e['Name'] . " Location: " . VenName($Venues[$e['Venue']]) ;
	    } else {
	      $str .= "<tr><td>" . $DayList[$e['Day']] . "<td>" . $e['Start'] . "-" . ($e['SubEvent'] < 0 ? $e['SlotEnd'] : $e['End'] ) .
			"<td>" . $e['Name'] . "<td>" . VenName($Venues[$e['Venue']]) . "<td>";
	    }
	    $withc=0;
	    for ($i=1;$i<5;$i++) {
	      if ($e["Side$i"] > 0 && $e["Side$i"] != $id && $type == 'Side') { 
	        if ($withc++) { $str .= ", "; } else if ($mode==1) { $str .= " With: "; }
		$str .= SAO_Report($e["Side$i"]);
              }
	      if ($e["Act$i"] > 0 && $e["Act$i"] != $id && $type == 'Act') { 
	        if ($withc++) { $str .= ", "; } else if ($mode==1) { $str .= " With: "; }
		$str .= SAO_Report($e["Act$i"]);
              }
	      if ($e["Other$i"] > 0 && $e["Other$i"] != $id && $type == 'Other') { 
	        if ($withc++) { $str .= ", "; } else if ($mode==1) { $str .= " With: "; }
		$str .= SAO_Report($e["Other$i"]);
              }
	    }
	    $str .= "\n";
	  }
	}
      }
    }
    if ($evc && $mode == 0) {
      $str .= "</table>\n";    
    }
  return $str;
}

$head_done = 0;

function doextras($extra1,$extra2,$extra3,$extra4,$extra5) {
  global $MASTER;
  $V=$MASTER['V'];
  for ($i=1;$i<6;$i++) {
    if (${"extra$i"}) {
      $e = ${"extra$i"};
      $suffix=pathinfo($e,PATHINFO_EXTENSION);
      if ($suffix == "js") {
        echo "<script src=$e?V=$V></script>\n";
      } else if ($suffix == "css") {
	echo "<link href=$e?V=$V type=text/css rel=stylesheet>\n";
      }
    }
  }
}

function dohead($title,$extra1='',$extra2='',$extra3='',$extra4='',$extra5='') {
  global $head_done,$MASTER;
  if ($head_done) return;
  $V=$MASTER['V'];
  echo "<html><head>";
  echo "<title>Wimborne Minster Folk Festival | $title</title>\n";
  include_once("files/header.php");
  echo "<script src=/js/tablesort.js?V=$V></script>\n";
  echo "<script src=/js/Tools.js?V=$V></script>\n";
  if ($extra1) doextras($extra1,$extra2,$extra3,$extra4,$extra5);
  echo "</head><body>\n";
  echo "<h1>Wimborne Minster Folk Festival | $title</h1>\n";
  include_once("files/navigation.php"); 
  echo "<div class=content>";
  $head_done = 1;
}

function doheadpart($title,$extra1='',$extra2='',$extra3='',$extra4='',$extra5='') {
  global $head_done,$MASTER;
  if ($head_done) return;
  $V=$MASTER['V'];
  echo "<html><head>";
  echo "<title>Wimborne Minster Folk Festival | $title</title>\n";
  include_once("files/header.php");
  echo "<script src=/js/tablesort.js?V=$V></script>\n";
  echo "<script src=/js/Tools.js?V=$V></script>\n";
  if ($extra1) doextras($extra1,$extra2,$extra3,$extra4,$extra5);
  $head_done = 1;
}

function dostaffhead($title,$extra1='',$extra2='',$extra3='',$extra4='',$extra5='') {
  global $head_done,$MASTER;
  if ($head_done) return;
  $V=$MASTER['V'];
  echo "<html><head>";
  echo "<title>WMFF Staff | $title</title>\n";
  include_once("files/header.php");
  include("festcon.php");
  echo "<script src=/js/tablesort.js?V=$V></script>\n";
  echo "<script src=/js/Tools.js?V=$V></script>\n";
  if ($extra1) doextras($extra1,$extra2,$extra3,$extra4,$extra5);
  echo "</head><body>\n";
  include_once("files/navigation.php"); 
  echo "<div class=content>";
  $head_done = 1;
}

function dotail() {
  echo "</div>";
  include_once("files/footer.php");
  echo "</body></html>\n";
}
 
function NoBreak($t) {
  return preg_replace('/ /','&nbsp;',$t);
}

function FormatList(&$l) {
  $res = implode(', ',$l);
  $res = preg_replace('/, ([^,]*$)/'," and $1",$res);
  return $res;
}

function AlphaNumeric($txt) {
  return preg_replace('/[^a-zA-Z0-9]/','',$txt);
}
?>
