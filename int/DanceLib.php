<?php
// Common Dance Library

$Dance_TimeFeilds = array('SatArrive','SatDepart','SunArrive','SunDepart');

function Sides_Name_List() {
  global $db;
  $Sides = array();
  $res = $db->query("SELECT SideId, SName FROM Sides WHERE SideStatus=0 AND IsASide=1 ORDER BY SName");
  if ($res) while ($row = $res->fetch_assoc()) $Sides[$row['SideId']] = $row['SName'];
  return $Sides;
}

function Sides_All($Except=-1,$All=1,$Include1=0,$Include2=0,$Include3=0,$Include4=0) {
  global $db,$YEAR,$Coming_Type;
  static $Sides_All = array();
  static $Sides_Loaded = 0;
  if ($All) {
    if ($Sides_Loaded == $Except) return $Sides_All;
    $Sides_All = array();
    $slist = $db->query("SELECT SideId, SName FROM Sides WHERE SideStatus=0 AND IsASide=1 ORDER BY SName");
  } else {
    $Blist = Select_Come(1);
    if ($Except) unset($Blist[$Except]);
    if ($Include1 || $Include2 || $Include3 || $Include4) {
      $LongList = Sides_All();
      if ($Include1) $Blist[$Include1] = $LongList[$Include1];
      if ($Include2) $Blist[$Include2] = $LongList[$Include2];
      if ($Include3) $Blist[$Include3] = $LongList[$Include3];
      if ($Include4) $Blist[$Include4] = $LongList[$Include4];
    }
    return $Blist;
  }
  if ($slist) while ($row = $slist->fetch_assoc()) {
    if ($row['SideId'] != $Except) $Sides_All[$row['SideId']] = $row['SName'];
  }
  $sides_Loaded = $Except;
  return $Sides_All;
}

function Select_Come($type=0,$extra='') {
  global $db,$YEAR,$Coming_Type;
  static $Come_Loaded = 0;
  static $Coming = array('');
  if ($Come_Loaded) return $Coming;
  $qry = "SELECT s.SideId, s.SName, s.Type FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$YEAR AND y.Coming=" . 
        $Coming_Type['Y'] . " AND s.IsASide=1 " . $extra . " ORDER BY s.SName";
//  echo "<!-- " . var_dump($qry) . " -->\n";
  $res = $db->query($qry);
  if ($res) {
    while ($row = $res->fetch_assoc()) {
      $x = '';
      if ($type == 0 && $row['Type']) $x = " ( " . $row['Type'] . " ) "; 
      $Coming[$row['SideId']] = $row['SName'] . $x;
    }
  }
  $Come_Loaded = 1;
  return $Coming;
}

function Select_Come_Day($Day,$xtr='') {
  global $db,$YEAR,$Coming_Type;
  $qry = "SELECT s.*, y.* FROM Sides s, SideYear y " .
         "WHERE s.SideId=y.SideId AND y.Year=$YEAR AND y.Coming=" . $Coming_Type['Y'] . " AND y.$Day=1 $xtr ORDER BY s.SName";
  $res = $db->query($qry);
  if ($res) {
    while ($row = $res->fetch_assoc()) {
      $Coming[$row['SideId']] = $row;
    }
    return $Coming;
  }
}

function &Select_Come_All() {
  global $db,$YEAR,$Coming_Type;
  static $Come_Loaded = 0;
  static $Coming;
  if ($Coming) return $Coming;
  $qry = "SELECT s.*, y.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$YEAR AND y.Coming=" . $Coming_Type['Y'] .
        " ORDER BY s.SName";
  $res = $db->query($qry);
  if ($res) while ($row = $res->fetch_assoc()) $Coming[$row['SideId']] = $row;

  return $Coming;
}

function &Part_Come_All() {
  global $db,$YEAR,$Coming_Type;
  $qry = "SELECT s.*, y.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$YEAR AND y.Coming=" . $Coming_Type['Y'] ;
  $res = $db->query($qry);
  if ($res) while ($row = $res->fetch_assoc()) $Coming[$row['SideId']] = $row; // All Sides, now acts

  $qry = "SELECT s.*, a.* FROM Sides s, ActYear a WHERE s.SideId=a.SideId AND a.Year=$YEAR AND a.YearState>1 ";
  $res = $db->query($qry);
  if ($res) while ($row = $res->fetch_assoc()) {
    if ($Coming[$row['SideId']]) {
      $Coming[$row['SideId']] = array_merge($Coming[$row['SideId']],$row);
    } else {
      $Coming[$row['SideId']] = $row;
    }
  }

//var_dump($Coming);exit;
  return $Coming;
}

function Show_Side($snum,$Message='') {
  global $YEAR, $Coming_Type,$db;
  if (is_numeric($snum) && ($side = Get_Side($snum))) {
    $syear = Get_SideYear($snum,$YEAR);
    if ($Message) echo "<h2 class=ERR>$Message</h2>"; 

    $ed = 'DanceEdit';
    if (!$side['IsASide']) $ed = 'MusicEdit';
    if (Access('Participant','Side',$snum)) {
      echo "<h2><a href=$ed.php?sidenum=$snum>Click here to edit Details, Contacts, Days, Times, Requests, Upload Photos and Insurance</a></h2>";
      echo "<h2>Public Information about: " . $side['SName'] . "</h2>";
    } else {
      echo "<h2>" . $side['SName'] . "</h2>";
    }
    if ($side['IsASide'] && $side['ShortName']) echo "( Appearing in the grids as:" . $side['ShortName'] . " )<br>";

    echo "<div style='width:800px;'>";
    if ($side['Photo']) echo "<img src=" . $side['Photo'] . " width=400 style='float:left;padding-right:10px;'><p>\n";

    if ($side['Description']) echo $side['Description'] . "<p>";

    if (isset($syear)) {
      switch ($syear['Coming']) {
        case $Coming_Type['N']:
        case $Coming_Type['NY']:
          echo "Not Coming this year";
          break;
        case $Coming_Type['Y']:
          echo "Coming";
          if ($syear['Fri'] || $syear['Sat'] || $syear['Sun']) {
            echo " on ";
            $lst = array();
            if ($syear['Tue']) $lst[] = 'Tuesday';
            if ($syear['Wed']) $lst[] = 'Wednesday';
            if ($syear['Thur']) $lst[] = 'Thursday';
            if ($syear['Fri']) $lst[] = 'Friday';
            if ($syear['Sat']) $lst[] = 'Saturday';
            if ($syear['Sun']) $lst[] = 'Sunday';
            if ($syear['Mon']) $lst[] = 'Monday';
            echo FormatList($lst);
          }
          break;
        case $Coming_Type['P']:
          echo "Probably coming";
          if ($syear['Fri'] || $syear['Sat'] || $syear['Sun']) {
            echo " on ";
            $lst = array();
            if ($syear['Tue']) $lst[] = 'Tuesday';
            if ($syear['Wed']) $lst[] = 'Wednesday';
            if ($syear['Thur']) $lst[] = 'Thursday';
            if ($syear['Fri']) $lst[] = 'Friday';
            if ($syear['Sat']) $lst[] = 'Saturday';
            if ($syear['Sun']) $lst[] = 'Sunday';
            if ($syear['Mon']) $lst[] = 'Monday';
            echo FormatList($lst);
          }
          break;
        case $Coming_Type['R']:
        case $Coming_Type['']:
        default:
//          echo "Invited";
      }
      echo "<p>";
    }
    if ($side['Blurb']) echo $side['Blurb'];

    echo "</div><br clear=all><p>";

    if ( $side['Website'] ) echo weblink($side['Website'],"<b>" . $side['SName'] . " website</b>") . "<p>";

    if ( $side['Video'] )  echo embedvideo($side['Video']) . "<p>";

    echo  Social_Link($side,'Facebook',1);
    echo  Social_Link($side,'Twitter',1);
    echo  Social_Link($side,'Instagram',1);

    if ($prog = Show_Prog('Side',$snum)) {
      echo $prog;
    } else {
      echo "<h2>The programme has not yet been published yet.</h2>\n";
      echo "When it is, the programme for <b>" . $side['SName'] . "</b> will appear here.<p>";
    }

    if (Access('Participant','Side',$snum)) {
      echo "<h2><a href=$ed.php?sidenum=$snum>Click here to edit Details, Contacts, Days, Times, Requests, Upload Photos and Insurance</a></h2>";
    }

  } else {
    echo "<h2 class=ERR>Sorry side $snum has an error: " . $db->error . "</h2>\n";
  }

}

function Get_Side_Name($id) {
  global $db;
  $res = $db->query("SELECT * FROM Sides WHERE SideId='$id'");
  if (!$res || $res->num_rows == 0) return '';
  $data = $res->fetch_assoc();
  return SName($data);
}

$Save_Sides = array('');

function Get_Side($who) {
  global $db;
  global $Save_Sides;
  if (isset($Save_Sides[$who])) return $Save_Sides[$who];
  $res = $db->query("SELECT * FROM Sides WHERE SideId='$who'");
  if (!$res || $res->num_rows == 0) return 0;
  $data = $res->fetch_assoc();
  $Save_Sides[$who] = $data;
  return $data;
}

function Put_Side(&$data) {
  global $db;
  global $Save_Sides;
  if (!isset($Save_Sides[$data['SideId']])) return 0;
  $Save = &$Save_Sides[$data['SideId']];
  $fcnt = 0;
  $rec = "UPDATE Sides SET ";
  foreach ($data as $fld=>$val) {
    if ($val != $Save[$fld]) {
      if ($fcnt++) $rec .= ", ";
      $rec .= "$fld='" . $val . "'";
    }
  }
  if (!$fcnt) return 0;
  $rec .= " WHERE SideId='" . $Save['SideId'] . "'";
//var_dump($rec);
  $Save_Sides[$data['SideId']] = $data;
  return $db->query($rec);
}

$Save_SideYears = array('');

function Get_SideYear($snum,$year=0) {
  global $db;
  global $Save_SideYears,$YEAR;
  if (!$year) $year=$YEAR;
  if (isset($Save_SideYears[$snum][$year])) return $Save_SideYears[$snum][$year];
  $res = $db->query("SELECT * FROM SideYear WHERE SideId='" . $snum . "' AND Year='" . $year . "'");
  if (!$res || $res->num_rows == 0) return 0;
  $data = $res->fetch_assoc();
  $Save_SideYears[$snum][$year] = $data;
  return $data;
}

function Get_SideYears($snum) {
  global $db;
  global $Save_SideYears;
  if (isset($Save_SideYears[$snum]['ALL'])) return $Save_SideYears[$snum];
  $res = $db->query("SELECT * FROM SideYear WHERE SideId='$snum'");
  if (!$res) return 0;
  while ($yr = $res->fetch_assoc()) {
    $y = $yr['Year'];
    $Save_SideYears[$snum][$y] = $yr;
  }
  $Save_SideYears[$snum]['ALL'] = 1;
  return $Save_SideYears[$snum];
}

function Put_SideYear(&$data) {
  global $db;
  global $Save_SideYears,$YEAR;
  if (!isset($Save_SideYears[$data['SideId']][$data['Year']])) {
    $Save = &$Save_SideYears[$data['SideId']][$YEAR];
    $Save = Default_SY();
    $data = array_merge($Save,$data);
    $rec = "INSERT INTO SideYear SET ";
    $Up = 0;
  } else { 
    $Save = &$Save_SideYears[$data['SideId']][$data['Year']];
    $rec = "UPDATE SideYear SET ";
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
  if ($Up) $rec .= " WHERE syId='" . $Save['syId'] . "'";
  $Save = $data;
//var_dump($rec);
  return $db->query($rec);
}

function isknown($snum,$yr) {
  global $Save_SideYears;
  return isset($Save_SideYears[$snum][$yr]);
}

function Set_Side_Help() {
  static $t = array(
        'SName'=>'To appear on website and in the programme',
        'ShortName'=>'IF the sides name is more than 20 characters, give a short form to appear on the Grid.',
        'Type'=>'For example North West, Border',
        'Importance'=>'Only raise the importance for those that really need it.  They get front billing and bigger fonts in publicity.  Under normal circumstances at most 3 should be Very High. Higher values are for the late addition of surprise headline acts.',
        'OverlapsD'=>'Sides that share Dancers - Where possible, there will be a 30 minute gap between any spot by any of these sides',
        'OverlapsM'=>'Sides that share Musicians - These can perform at the same spot at the same time, or consecutive times',
        'Blurb'=>'Longer description, for the webpage on the festival website about the side, only seen when a user clicks a link for more info on them - OPTIONAL',
        'CostumeDesc'=>'Short description of costume and where in the country they are from, for the programme book',
        'Description'=>'Short blurb to describe the performers, for lists of performers on the website',
        'Website'=>'If more than one seperate with spaces (mainly for music acts)',
        'Facebook'=>'If more than one seperate with spaces (mainly for music acts)',
        'Twitter'=>'If more than one seperate with spaces (mainly for music acts)',
        'Instagram'=>'If more than one seperate with spaces (mainly for music acts)',
        'Video'=>'Of the side - can use a YouTube embed or share link',
        'Likes'=>'Venues prefered, sides like to share with',
        'Dislikes'=>'Venues disliked, sides do not want to share with - not in use',
        'Pre2017'=>'Previous Wimbornes/Invites etc',
        'AccessKey'=>'Allows user editing of many fields.  When you use the Email links here it is always appended to the message',
        'Photo'=>'Give URL of photo to use or upload one',
        'Mobile'=>'As an emergency contact number, this is important',
        'NoiseLevel'=>'Loud PAs are noisy, a single violin or flute is quiet',
        'Surfaces'=>'What surfaces can be danced on, if none are set all is assumed. Note the Square has flagstones and Crownmead has Bricks',
        'SideStatus'=>'If the side is disbanded mark as dead',
        'StagePA'=>'Mainly Appalachian Sides, give PA Reqs as text or upload a file',
        'DataCheck'=>'Not yet working',
        'MorrisAnimal'=>'If the side has a morris animal - what kind is it',
        'Workshops'=>'That the side could run',
        'Overlaps'=>'Do you overlap with any dance sides or other performers who might be at Wimborne, if so please describe in detail and we will try and prevent clashes',
        'Contact'=>'Main Contact',
        'AgentName'=>'Main Contact',
        'DirContact'=>'Direct Performer Contact',
        'Address'=>'Where to send performers wristbands and any tickets',
        'AltContact'=>'Alternative Contact',
        'Location'=>'Where in the country they are from',
        'PublicInfo'=>'Anything here may appear on the festival website where appropriate',
        'PrivateInfo'=>'Anything here is ONLY visible to you and the relevant members of the festival',
        'NeedBank'=>'Set this to enable bank details for dance sides (for payments)',
        'Bank'=>'If you expect to be paid, please fill your bank details in',
        'Testing'=>'Testing Only'
  );
  Set_Help_Table($t);
}

function Set_Side_Year_Help() {
  static $t = array(
        'Performers'=>'Number of Dancers and Musicians that will want wristbands, put -1 if none are wanted',
        'FriEve'=>'Would you like to have some dancing on Friday Evening?',
        'SatEve'=>'Would you like to have some dancing on Saturday Evening?',
        'FriDance'=>'Number of Dance spots requested on Friday, the default assumption is 0',
        'SatDance'=>'Number of Dance spots requested on Saturday, the default assumption is 3 plus the procession',
        'SunDance'=>'Number of Dance spots requested on Sunday, the default assumption is 4 for Morris Sides and 3 for Stage Sides',
        'Share'=>'Do you like shared or dedicated dance spots?', 
        'CarPark'=>'Number of free car park tickets for parking at QE school (10 minute walk to square)',
        'SatArrive'=>'The earliest time (eg 1000), if blank no restrictions are assumed',
        'SatDepart'=>'The start of the last spot (eg 1600).  If blank no restictions are assumed.',
        'SunArrive'=>'The earliest time (eg 1000), if blank no restrictions are assumed',
        'SunDepart'=>'The start of the last spot (eg 1600).  If blank no restictions are assumed.',
        'BudgetArea'=>'In MOST cases nothing needs setting here as Music acts will default to Music and Dance to Dance.  
                * IF you need to assign to a different budget change the area
                * IF you need part of the fee to come under a different budget, you set up to 2 areas to have parts of the Fee and the amount to assign'
  );
  Set_Help_Table($t);
}

function Default_SY($id=0) { 
  global $YEAR,$USERID;
  $ans = array('SatDance'=>3,'SunDance'=>4,'Year'=>$YEAR,'Procession'=>1,'Invited'=>'');
  if ($id) $ans['SideId'] = $id;
  return $ans;
}

function Default_AY($id=0) { 
  global $YEAR,$USERID;
  $ans = array('Year'=>$YEAR,'BookedBy'=>$USERID,'RadioWimborne'=>1);
  if ($id) $ans['SideId'] = $id;
  return $ans;
}

function Get_Dance_Types($tup) {
  global $db;
  $res = $db->query("SELECT * FROM DanceTypes ORDER BY Importance DESC");
  if ($res) {
    while ($typ = $res->fetch_assoc()) {
      $short[] = $typ['SName'];
      $full[$typ['TypeId']] = $typ;
    }
  }
  if ($tup) return $full;
  return $short;
}

function Get_Dance_Type($id) {
  global $db;
  static $Types;
  if (isset($Types[$id])) return $Types[$id];
  $res=$db->query("SELECT * FROM DanceTypes WHERE TypeId=$id");
  if ($res) {
    $ans = $res->fetch_assoc();
    $Types[$id] = $ans;
    return $ans;
  }
  return 0; 
}

function Put_Dance_Type(&$now) {
  $e=$now['TypeId'];
  $Cur = Get_Dance_Type($e);
  Update_db('DanceTypes',$Cur,$now);
}

function Has_Info(&$data) {
  $checkfor = array( 'StagePA', 'Likes', 'Notes', 'YNotes', 'PrivNotes', 'NoiseLevel');
  foreach ($checkfor as $c) if (isset($data[$c]) && $data[$c] && ($data[$c] != 'None')) return 1;
  if (Get_Overlaps_For($data['SideId'],1)) return 1;
  return 0;
} 

function Get_Overlaps_For($id,$act=0) { // if act only active
  global $db;
  $Os = [];
  $res = $db->query("SELECT * FROM Overlaps WHERE (Sid1=$id OR Sid2=$id)" . ($act?' AND Active=1':''));
  if ($res) while ($o = $res->fetch_assoc()) $Os[] = $o;
  return $Os;
}

function Get_Active_Overlaps_For($id) { // if act only active
  global $db;
  $Os = [];
  $res = $db->query("SELECT * FROM Overlaps WHERE (Sid1=$id OR Sid2=$id) AND Sid1!=0 AND Sid2!=0 AND Active=1");
  if ($res) while ($o = $res->fetch_assoc()) $Os[] = $o;
  return $Os;
}

function Get_Overlap($id) {
  global $db;
  $res = $db->query("SELECT * FROM Overlaps WHERE id=$id");
  if ($res) while ($o = $res->fetch_assoc()) return $o;
}

function Put_Overlap($now) {
  $e=$now['id'];
  $Cur = Get_Overlap($e);
  Update_db('Overlaps',$Cur,$now);
}

function Put_Overlaps(&$Ovs) {
  foreach($Ovs as $i=>$o) {
    if ($o['id']) {
      Put_Overlap($o);
    } else {
      Insert_db('Overlaps', $o);
    }
  }
}
  
function UpdateOverlaps($snum) {
  $Exist = Get_Overlaps_For($snum);
//var_dump($Exist);echo "<p>";
//var_dump($_POST);echo "<p>";

// Scan each existing and any added rules
  $Rule = 0;
  while ((isset($_POST["OlapSide$Rule"]) || isset($_POST["OlapAct$Rule"]) || isset($_POST["OlapOther$Rule"])) || 
         isset($_POST["OlapActive$Rule"]) || isset($_POST["OlapMajor$Rule"])) {
    $O = $StO = (isset($Exist[$Rule]) ? $Exist[$Rule] : ['Sid1'=>$snum,'Cat2'=>0]);
    $Other = ($O['Sid1'] == $snum)?'Sid2':'Sid1'; //???????
    $OtherCat = ($O['Sid1'] == $snum)?'Cat2':'Cat1'; //???????
    $O['OType'] = $_POST["OlapType$Rule"];
    $O['Major'] = (isset($_POST["OlapMajor$Rule"]) ? $_POST["OlapMajor$Rule"] :0);
    $O['Days'] = $_POST["OlapDays$Rule"];
    $O['Active'] = (isset($_POST["OlapActive$Rule"]) ? $_POST["OlapActive$Rule"] :0);
    $Cat = $O[$OtherCat] = $_POST["OlapCat$Rule"];
    switch ($Cat) {
    case 0: //Side
      $O[$Other] = $_POST["OlapSide$Rule"];
      break;
    case 1: //Act
      $O[$Other] = $_POST["OlapAct$Rule"];
      break;
    case 2: //Other
      $O[$Other] = $_POST["OlapOther$Rule"];
      break;
    }    
    if ((isset($O['id'])) && $O['id']) {
      Update_db('Overlaps',$StO,$O); 
    } else if ($O[$Other]) {
      Insert_db('Overlaps',$O); 
    }

    $Rule++;
  }
}
      
function Side_ShortName($si) {
  $side = Get_Side($si);
  return $side[($side['ShortName']?'ShortName':'SName')];
}

// Ignore case and -> &, ommit | add 'The'
function Find_Perf_Similar($name) {
  global $db;
  $name = strtolower(trim($name));
  $name = preg_replace('/^the /','',$name);
  $name = preg_replace('/ morris/',' ',$name);
  $name = preg_replace('/ band/',' ',$name);
  $name = preg_replace('/ and /',' ',$name);
  $name = preg_replace('/ & /',' ',$name);
  $name = trim($name);

  $res = $db->query("SELECT * FROM Sides WHERE SName LIKE '%$name%'");
  if (!$res) return [];
  $sims = [];
  while ($rec = $res->fetch_assoc()) $sims[] = $rec;
  return $sims;
}

function EventCmp($a,$b) {
  if ($a['Day'] != $b['Day'] ) return (($a['Day'] < $b['Day']) ? -1 : 1);
  if ($a['Start'] == $b['Start']) return 0;
  return (($a['Start'] < $b['Start']) ? -1 : 1);
}

/* Get Overlaps - if none return empty string, if not public return,
/  otherwise get programmes for all overlaps and merge together and list as a timetable
/ */
function Extended_Prog($type,$id,$all=0) { 
    global $DayList,$MASTER,$OlapCats;
    $Olaps = Get_Active_Overlaps_For($id,1);
    if (!$Olaps) return "";

    include_once("ProgLib.php");
    $str = '';
    $Evs = Get_All_Events_For($type,$id,$all);
    if (!$Evs) return "";
    $ETs = Get_Event_Types(1);
//echo "Type: $type, $id<p>";
//var_dump($Evs);
    $evc=0;
    $Worst= 99;
    $EventLink = ($all?'EventAdd.php':'EventShow.php');
    $VenueLink = ($all?'AddVenue.php':'VenueShow.php');
    $host = "https://" . $_SERVER{'HTTP_HOST'};

    foreach ($Evs as $ei=>$e) $Evs[$ei]['ActAs'] = $id;
    $Found = 0;
    // Go through each Olap and add events
    foreach($Olaps as $O) {
      $Oid = ($O['Sid1'] == $id ? $O['Sid2'] : $O['Sid1']);
      $Oct = ($O['Sid1'] == $id ? $O['Cat2'] : $O['Cat1']);
      $OEvs = Get_All_Events_For($OlapCats[$Oct],$Oid,$all);
      if (!$OEvs) continue;
      foreach ($OEvs as $oe=>$e) $OEvs[$oe]['ActAs'] = $Oid;
      $Evs = array_merge($Evs,$OEvs);
      $Found = 1;
    }
    if (!$Found) return ""; // No new events found

    usort($Evs,"EventCmp"); 
    
//var_dump($Evs); exit;
    $Venues = Get_Real_Venues(1);
    if ($Evs) { // Show IF all or EType state > 1 or (==1 && participant)
      $With = 0;
      foreach ($Evs as $e) {
        if ($e["BigEvent"]) { $With = 1; break; }
        for ($i = 1; $i<5;$i++) if ($e["Side$i"] && $e["Side$i"] != $id) { $With = 1; break 2; }
        for ($i = 1; $i<5;$i++) if ($e["Act$i"] && $e["Act$i"] != $id) { $With = 1; break 2; }
        for ($i = 1; $i<5;$i++) if ($e["Other$i"] && $e["Other$i"] != $id) { $With = 1; break 2; }
      }
        
      $UsedNotPub = 0;
      foreach ($Evs as $e) {
        $cls = ($e['Public']<2?'':' class=NotCSide ');
        if ($all || $ETs[$e['Type']]['State'] > 1 || ($ETs[$e['Type']]['State'] == 1 && Access('Participant',$type,$id))) {
          $evc++;
           $Worst = min($ETs[$e['Type']]['State'],$Worst);
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
                if ($O['Identifier'] == $e['ActAs']) { 
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
            $str .= "<tr><td $cls>" . $DayList[$e['Day']] . "<td $cls>" . timecolon($e['Start']) . "-" . timecolon(($e['SubEvent'] < 0 ? $e['SlotEnd'] : $e['End'] )) .
                        "<td>" . SAO_Report($e['ActAs']) .
                        "<td $cls><a href=$host/int/$EventLink?e=" . $e['EventId'] . ">" . $e['SName'] . "</a><td $cls>";
            if ($VenC) $str .= " starting from ";
            $str .= "<a href=$host/int/$VenueLink?v=" . $e['Venue'] . ">" . VenName($Venues[$e['Venue']]) ;
            $str .= "</a><td $cls>";
            if ($PrevI || $NextI) $str .= "In position $Position";
            if ($PrevI) { $str .= ", After " . SAO_Report($PrevI); };
            if ($NextI) { $str .= ", Before " . SAO_Report($NextI); };
            $str .= "\n";
          } else { // Normal Event
            $str .= "<tr><td $cls>" . $DayList[$e['Day']] . "<td $cls>" . timecolon($e['Start']) . "-" . timecolon(($e['SubEvent'] < 0 ? $e['SlotEnd'] : $e['End'] )) .
                        "<td>" . SAO_Report($e['ActAs']) .
                        "<td $cls><a href=$host/int/$EventLink?e=" . $e['EventId'] . ">" . $e['SName'] . 
                        "</a><td $cls><a href=$host/int/$VenueLink?v=" . $e['Venue'] . ">" . VenName($Venues[$e['Venue']]) . "</a>";
            if ($With) {
              $str .= "<td $cls>";
              $withc=0;
              for ($i=1;$i<5;$i++) {
                if ($e["Side$i"] > 0 && $e["Side$i"] != $id && $type == 'Side') { 
                  if ($withc++) $str .= ", "; 
                  $str .= SAO_Report($e["Side$i"]);
                }
                if ($e["Act$i"] > 0 && $e["Act$i"] != $id && $type == 'Act') { 
                  if ($withc++) $str .= ", ";
                  $str .= SAO_Report($e["Act$i"]);
                }
                if ($e["Other$i"] > 0 && $e["Other$i"] != $id && $type == 'Other') { 
                  if ($withc++) $str .= ", ";
                  $str .= SAO_Report($e["Other$i"]);
                }
              }
            }
            $str .= "\n";
          }
        } else { // Debug Code
//          echo "State: " . $ETs[$e['Type']]['State'] ."<p>";
        }
        if ($cls) $UsedNotPub = 1;
      }
      if ($evc) {
        $Thing = Get_Side($id);
        $Desc = ($Worst > 2)?"":'Current ';
        if ($With) $str = "<td>With\n" . $str;
        $str = "<h2>$Desc Programme for " . $Thing['SName'] . " including overlaps:</h2>\n" . 
                ($UsedNotPub?"<span class=NotCSide>These are not currently public<p>\n</span>":"") .
                "<table border><tr><td>Day<td>time<td>As<td>Event<td>Venue" . $str;
      }
    }
    if ($evc) {
      $str .= "</table>\n";    
    }

//var_dump($str);

  return $str;
}
?>
