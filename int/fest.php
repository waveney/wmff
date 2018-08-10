<?php

/* Various common code across fest con tools */

  include_once("festdb.php");
  include_once("festfm.php");

$BUTTON = 0;

if (isset($_POST{'Y'})) $YEAR = $_POST{'Y'};
if (isset($_GET{'Y'})) $YEAR = $_GET{'Y'};
if (isset($_POST{'B'})) $BUTTON = ($_POST{'B'}+1) % 4;
if (isset($_GET{'B'})) $BUTTON = ($_GET{'B'}+1) % 4;

if (!is_numeric($YEAR)) exit("Invalid Year");

$Noise_Levels = array("Middling","Quiet","Noisy");
$Noise_Colours = ['lightgreen','yellow','Orange'];
$Coming_States = array('','Received','Coming','Not coming','Possibly','Not coming, please ask next year');
$Coming_Colours = ['white','Yellow','Lime','salmon','lightblue','Orange'];
$Coming_idx = array('','R','Y','N','P','NY');
$Coming_Type = array_flip($Coming_idx);
$Invite_States = array('','Yes','YES!','No','Maybe');
$Invite_Type = array_flip($Invite_States);
$Dance_Comp = ['No Opionion','Yes','No'];
$Dance_Comp_Colours = ['white','lime','salmon'];
$Surfaces = array ('','Tarmac','Flagstones','Grass','Stage','Brick','Wood','Carpet','Astroturf');// Last 3 not used yet
$Surface_Colours = ['','grey','Khaki','lightgreen','Peru','salmon','Peru','Teal','lime'];
$Side_Statuses = array("Alive","Dead");
$Share_Spots = array('Prefered','Always','Never','Sometimes');
$Share_Type = array_flip($Share_Spots);
$Access_Levels = array('','Participant','Upload','Steward','Staff','Committee','SysAdmin','Internal');// Sound Engineers will be Stewards, Upload not used yet
$Access_Type = array_flip($Access_Levels);
$Area_Levels = array( 'No','Edit','Edit and Report');
$Area_Type = array_flip($Area_Levels);
$Sections = array( 'Docs','Dance','Stalls','Users','Venues','Music','Sponsors','Finance','Craft','Other','TLine','Bugs','Photos'); // Note fest_users fields must match
$Importance = array('None','Some','High','Very High','Even Higher','Highest','The Queen');
$Book_States = array('None','Declined','Booking','Contract Ready','Booked');
$Book_Colours = ['white','salmon','yellow','orange','lime'];
$Book_State = array_flip($Book_States);
$InsuranceStates = array('None','Uploaded','Checked');
$Book_Actions = array('None'=>'Book','Declined'=>'Book,Contract','Booking'=>'Contract,Decline,Cancel','Contract Ready'=>'Confirm,Decline,Cancel',
                'Booked'=>'Cancel,Decline');
$Book_ActionExtras = array('Book'=>'', 'Contract'=>'', 'Decline'=>'', 'Cancel'=>'', 'Confirm'=>'');
$EType_States = array('Very Early','Draft','Partial','Provisional','Complete');
$TicketStates = array('Not Yet','Open','Closed');
$OlapTypes = array('Dancer','Musician');
$OlapDays = array('All','Sat Only','Sun Only','None');
$OlapCats = array('Side','Act','Other');


date_default_timezone_set('GMT');

function Set_User() {
  global $db,$USER,$USERID,$AccessType,$YEAR,$CALYEAR;
  if (isset($USER)) return;
  $USER = array();
  $USERID = 0;
  if (isset($_COOKIE{'WMFFD'})) {
    $biscuit = $_COOKIE{'WMFFD'};
    $Cake = openssl_decrypt($biscuit,'aes-128-ctr','Quarterjack',0,'BrianMBispHarris');
    $crumbs = explode(':',$Cake);
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
      setcookie('WMFF2',$USER['Yale'], mktime(0,0,0,1,1,$CALYEAR+1) ,'/' );
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
    include_once("int/Login.php");
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

// Needs making work with SMTP so DKIM works
function SendEmail($to,$sub,&$letter,$headopt='') {
  if (file_exists("testing")) {
    echo "<p>Would send email to $to with subject: $sub<p>Content:<p>$letter<p>\n";
  } else {
    mail($to,$sub,$letter,$headopt);
  }
  return;

// Old via Clint's server code - retain incase...
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
  else { $name = $data['SName']; }
  if ($type="Side") { $id = $data['SideId']; }
  else { $id = $data = $data['ActId']; };

  $ProgInfo = Show_Prog($type,$id,1);

  $lnk = "<a href=mailto:$email?from=" . $USER['Email'] .
         "&subject=" . urlencode("Wimborne Minster Folk Festival $YEAR and " . $data['SName']) . 
         "&body=" . urlencode("$name,\n\n" .
                 "You can check your programme times and update your side details at any time by visiting " .
                 "<a href=https://" . $_SERVER['HTTP_HOST'] . "/int/Direct.php?t=$type&id=$id&key=$key>this link</a>.  " .
                $ProgInfo . "\n\n" .
                 "\n\nRegards " . $USER['SName'] . "\n\n") .
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
      if (isset($data['AgentName'])) { $name = firstword($data['AgentName']); }
      else { $name = $data['SName']; }
    } else if ($xtr == '!!') {
      if (!isset($data["Email"])) return "";
      $email = $data['Email'];
      $xtr = '';
      $Label = 'Direct ';
      if (isset($data[$xtr .'Contact'])) { $name = firstword($data[$xtr .'Contact']); }
      else { $name = $data['SName']; }
    } else {
      if (!isset($data[$xtr . "Email"])) return "";
      $email = $data[$xtr . 'Email'];
      $Label = $xtr;
      if (isset($data[$xtr .'Contact'])) { $name = firstword($data[$xtr .'Contact']); }
      else { $name = $data['SName']; }
    }
  } else {
    if ($xtr == '!!') $xtr = '';
    if (!isset($data[$xtr . "Email"])) return "";
    $email = $data[$xtr . 'Email'];
    $Label = $xtr;
    if (isset($data[$xtr .'Contact'])) { $name = firstword($data[$xtr .'Contact']); }
    else { $name = $data['SName']; }
  }
  if ($email == '') return "";
  $email = Clean_Email($email);
  $key = $data['AccessKey'];
  if (isset($data['SideId'])) {
    $id = $data['SideId'];
  } else if (isset($data['Tid'])) {
    $id = $data['Tid'];
  }
  if (!isset($id)) return "";

  $link = "'mailto:$email?from=" . $USER['Email'] .
         "&subject=" . urlencode("Wimborne Minster Folk Festival $YEAR and " . $data['SName']) . "'";
  $direct = "<a href=https://" . $_SERVER['HTTP_HOST'] . "/int/Direct.php?t=$type&id=$id&key=$key&Y=$YEAR>this link</a>  " ;

// ONLY DANCE AT THE MOMENT...
  switch ($type) {
    case 'Side':
    case 'Dance':
      $ProgInfo = Show_Prog($type,$id,1);
      $Content = urlencode("$name,<p>" .
                 "<div id=SideLink$id>" .
                "Please add/correct details about your side's contact information and your preferences in " .
                "terms of days coming, number of dance spots, etc. by visiting $direct.</div><p>" .
                "You can update information at any time, until the programme goes to print. " .
                "(You'll also be able to view your programme times, once we've done the programme)<p>" .
                "<div id=SideProg$id>$ProgInfo</div><p>" .
                 "Regards " . $USER['SName'] . "<p>"); 
      break;

    case 'Act':
    case 'Music':

      include_once("MusicLib.php");
      $Content = MusicMail($data,$name,$id,$direct);
      break;

    case 'Trade':
    case 'trade': // Not used I think (hope)
      $Content = urlencode("$name,<p>" .
                 "<div id=SideLink$id>" .
                "Please add/correct details about your business, contact information, your product descriptions, pitch and power requirements, " . 
                "update your Insurance and Risc Assessment etc. by visiting $direct.</div><p>" .
                "Details of your pitch location, general trader information and particulars of setup and cleardown information will also appear there.<p>" .
                 "Regards " . $USER['SName'] . "<p>" 
                ); 
      break;

// For OTHER at present
    default:
      $Content = urlencode("$name,<p>" .
                 "Regards " . $USER['SName'] . "<p>"); 
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
      include_once("index.php");
      exit;
    }

  case $Access_Type['Committee'] :
  case $Access_Type['Steward'] :
  case $Access_Type['Staff'] :
  case $Access_Type['Upload'] :
    $ErrorMessage = $message;
    include_once('int/Staff.php');  // Should be good
    exit;                        // Just in case
  case $Access_Type['SysAdmin'] :
  case $Access_Type['Internal'] :
    $ErrorMessage = "Something went very wrong... - $message";
    include_once('int/Staff.php');  // Should be good
    exit;                        // Just in case
    
  default:
    include_once("index.php"); 
  }

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

function First_Sent($stuff) {
  $onefifty=substr($stuff,0,150);
  return (preg_match('/^(.*?[.!?])\s/s',$onefifty,$m) ? $m[1] : $onefifty);
}

function munge_array(&$thing) {
  if (is_array($thing)) return $thing;
  return [];
}

function Show_Prog($type,$id,$all=0) { //mode 0 = html, 1 = text for email
    global $DayList;
    $str = '';
    include_once("ProgLib.php");
    include_once("DanceLib.php");
    $Evs = Get_All_Events_For($type,$id,$all);
    $ETs = Get_Event_Types(1);
//echo "Type: $type, $id<p>";
//var_dump($Evs);
    $evc=0;
    $Worst= 99;
    $EventLink = ($all?'EventAdd.php':'EventShow.php');
    $VenueLink = ($all?'AddVenue.php':'VenueShow.php');
    $host = "https://" . $_SERVER{'HTTP_HOST'};
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
            $str .= "<tr><td $cls>" . $DayList[$e['Day']] . "<td $cls>" . timecolon($e['Start']) . "-" . timecolon(($e['SubEvent'] < 0 ? $e['SlotEnd'] : $e['End'] )) .
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
        $str = "<h2>$Desc Programme for " . $Thing['SName'] . ":</h2>\n" . ($UsedNotPub?"<span class=NotCSide>These are not currently public<p>\n</span>":"") .
                "<table border><tr><td>Day<td>time<td>Event<td>Venue" . $str;
      }
    }
    if ($evc) {
      $str .= "</table>\n";    
    }

//var_dump($str);

  return $str;
}

$head_done = 0;

function doextras($extra1,$extra2,$extra3,$extra4,$extra5) {
  global $MASTER_DATA;
  $V=$MASTER_DATA['V'];
  for ($i=1;$i<6;$i++) {
    if (${"extra$i"}) {
      $e = ${"extra$i"};
      $suffix=pathinfo($e,PATHINFO_EXTENSION);
      if ($suffix == "js") {
        echo "<script src=$e?V=$V></script>\n";
      } else if ($suffix == 'jsdefer') {
        $e = preg_replace('/jsdefer$/','js',$e);
        echo "<script defer src=$e?V=$V></script>\n";
      } else if ($suffix == "css") {
        echo "<link href=$e?V=$V type=text/css rel=stylesheet>\n";
      }
    }
  }
}

function dohead($title,$extra1='',$extra2='',$extra3='',$extra4='',$extra5='') {
  global $head_done,$MASTER_DATA;
  if ($head_done) return;
  $V=$MASTER_DATA['V'];
  $pfx="";
  if (file_exists("files/TitlePrefix")) $pfx = file_get_contents("files/TitlePrefix");
  echo "<html><head>";
  echo "<title>$pfx " . $MASTER_DATA['FestName'] . " | $title</title>\n";
  include_once("files/header.php");
  echo "<script src=/js/tablesort.js?V=$V></script>\n";
  echo "<script src=/js/Tools.js?V=$V></script>\n";
  if ($extra1) doextras($extra1,$extra2,$extra3,$extra4,$extra5);
  echo "</head><body>\n";

  echo "<div id=HeadRow>";
  echo "<a href=/InfoBuses.php><img src=/images/icons/leftspon.jpg id=leftspon hidden></a>";
  echo "<a href=/><img id=HeadBan src=/images/icons/WimborneFolkV3Banner-2019.png ></a>";
  echo "<a href=http://www.hall-woodhouse.co.uk/find-your-perfect-pub/oddfellows-arms target=_blank><img src=/images/icons/rightspon.jpg id=rightspon hidden></a>";
  echo "</div>\n";
  echo "<script src=/js/WmffAds.js?V=$V></script>";

  include_once("files/navigation.php"); 
  echo "<div class=content>";
  $head_done = 1;
}

function doheadpart($title,$extra1='',$extra2='',$extra3='',$extra4='',$extra5='') {
  global $head_done,$MASTER_DATA;
  if ($head_done) return;
  $V=$MASTER_DATA['V'];
  $pfx="";
  if (file_exists("files/TitlePrefix")) $pfx = file_get_contents("files/TitlePrefix");
  echo "<html><head>";
  echo "<title>$pfx " . $MASTER_DATA['FestName'] . " | $title</title>\n";
  include_once("files/header.php");
  echo "<script src=/js/tablesort.js?V=$V></script>\n";
  echo "<script src=/js/Tools.js?V=$V></script>\n";
  if ($extra1) doextras($extra1,$extra2,$extra3,$extra4,$extra5);
  $head_done = 1;
}

function dostaffhead($title,$extra1='',$extra2='',$extra3='',$extra4='',$extra5='') {
  global $head_done,$MASTER_DATA;
  if ($head_done) return;
  $V=$MASTER_DATA['V'];
  $pfx="";
  if (file_exists("files/TitlePrefix")) $pfx = file_get_contents("files/TitlePrefix");
  echo "<html><head>";
  echo "<title>$pfx " . $MASTER_DATA['ShortName'] . " | $title</title>\n";
  include_once("files/header.php");
  include_once("festcon.php");
  echo "<script src=/js/tablesort.js?V=$V></script>\n";
  echo "<script src=/js/Tools.js?V=$V></script>\n";
  if ($extra1) doextras($extra1,$extra2,$extra3,$extra4,$extra5);
  echo "<meta http-equiv='cache-control' content=no-cache>";
  echo "</head><body>\n";
  include_once("files/navigation.php"); 
  echo "<div class=content>";
  $head_done = 1;
}

function dominimalhead($title,$extra1='',$extra2='',$extra3='',$extra4='',$extra5='') { 
  global $head_done,$MASTER_DATA;
  $V=$MASTER_DATA['V'];
  $pfx="";
  if (file_exists("files/TitlePrefix")) $pfx = file_get_contents("files/TitlePrefix");
  echo "<html><head>";
  echo "<title>$pfx " . $MASTER_DATA['ShortName'] . " | $title</title>\n";
  echo "<link href=files/style.css?V=$V type=text/css rel=stylesheet>";
  echo "<script src=/js/jquery-3.2.1.min.js></script>";
  if ($extra1) doextras($extra1,$extra2,$extra3,$extra4,$extra5);
  echo "</head><body>\n";
  include_once("int/analyticstracking.php");
  $head_done = 2;
}

function dotail() {
  global $head_done;
  echo "</div>";
  if ($head_done == 1) include_once("files/footer.php");
  echo "</body></html>\n";
  exit;
}
 

?>
