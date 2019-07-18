<?php

/* Various common code across fest con tools */

  include_once("festdb.php");
  include_once("festfm.php");

$BUTTON = 0;

if (isset($_REQUEST{'Y'})) $YEAR = $_REQUEST{'Y'};
if (isset($_REQUEST{'B'})) $BUTTON = ($_REQUEST{'B'}+1) % 4;

if (isset($YEAR)) {
  if (!is_numeric($YEAR)) exit("Invalid Year");
} else {
  $YEAR = $CALYEAR;
}

$Access_Levels = ['','Participant','Upload','Steward','Staff','Committee','SysAdmin','Internal'];// Sound Engineers will be Stewards, Upload not used yet
$Access_Type = array_flip($Access_Levels);
$Area_Levels = array( 'No','Edit','Edit and Report');
$Area_Type = array_flip($Area_Levels);
$Sections = [ 'Docs','Dance','Trade','Users','Venues','Music','Sponsors','Finance','Craft','Other','TLine','Bugs','Photos','Comedy','Family','News']; // Note fest_users fields must match
$Importance = array('None','Some','High','Very High','Even Higher','Highest','The Queen');
$Book_States = array('None','Declined','Booking','Contract Ready','Contract Signed');
$Book_Colours = ['white','salmon','yellow','orange','lime'];
$Book_State = array_flip($Book_States);
$InsuranceStates = array('None','Uploaded','Checked');
$Book_Actions = array('None'=>'Book','Declined'=>'Book,Contract','Booking'=>'Contract,Decline,Cancel','Contract Ready'=>'Confirm,Decline,Cancel',
                'Contract Signed'=>'Cancel,Decline');
$Book_ActionExtras = array('Book'=>'', 'Contract'=>'', 'Decline'=>'', 'Cancel'=>'', 'Confirm'=>'');
$EType_States = array('Very Early','Draft','Partial','Provisional','Complete');
$TicketStates = array('Not Yet','Open','Closed','Remove');
$ArticleFormats = ['Large Image','Small Image','Text','Banner Image','Banner Text','Fixed','Left/Right Pairs'];
$PerfTypes = ['Dance Side'=>['IsASide','Dance','Dance','Dance','D'],
              'Musical Act'=>['IsAnAct','Music','Music','Music','M'],
              'Comedy'=>['IsFunny','Comedy','Comedy','Comedy','C'],
              'Child Ent'=>['IsFamily','Children','Family','Youth','Y'],
              'Other'=>['IsOther','Info','Other','','O']];
$PerfIdx = ['Side'=>0,'Act'=>1,'Comic'=>2,'ChEnt'=>3,'Other'=>4];

// Perfname => [field to test, email address for,Capability name,budget,shortCode]
$Months = ['','Jan','Feb','Mar','Apr','May','June','July','Aug','Sep','Oct','Nov','Dec'];

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
// Track suspicious things
      if (($USER['LogUse']) && (file_exists("LogFiles"))) {
        $logf = fopen("LogFiles/U$USERID.txt",'a+');
        if ($logf) {
          fwrite($logf,date('d/m H:j:s '));
          fwrite($logf,$_SERVER['PHP_SELF']);
          fwrite($logf,json_encode($_REQUEST));
          if ($_COOKIE) fwrite($logf,json_encode($_COOKIE));
          if ($_FILES) fwrite($logf,json_encode($_FILES));
          fwrite($logf,"\n\n");
        }
      }
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
  if ($USER{'AccessLevel'} < $want) return 0;
  
  if ($USER{'AccessLevel'} > $want+1) return 1;

  switch  ($USER{'AccessLevel'}) {

  case $Access_Type['Participant'] : 
    if ($USER['Subtype'] == 'Other' && $subtype == 'Act') {}
    elseif ($USER{'Subtype'} != $subtype) return 0;
    return $thing == $USERID;

  case $Access_Type['Upload'] :
  case $Access_Type['Staff'] :
  case $Access_Type['Steward'] :
    if (!$subtype) return $USER{'AccessLevel'} >= $want;
    if (isset($USER[$subtype]) && $USER[$subtype]) return 1;
    return 0; 


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

function UserSetPref($pref,$val) {
  global $USER,$USERID;
  Set_User();
  if (!$USERID) return; // No user
  $Prefs = $USER['Prefs'];
  if (!($NewPrefs = preg_replace("/$pref\:.*\n/","$pref:$val\n",$Prefs))) $NewPrefs = $Prefs .  "$pref:$val\n";
  $USER['Prefs'] = $NewPrefs;
  Put_User($USER);
}

function UserGetPref($pref) {
  global $USER,$USERID;
  if (!$USER || !isset($USER['Prefs'])) return 0;
  if (preg_match("/$pref\:(.*)\n/",$USER['Prefs'],$rslt)) return trim($rslt[1]);
  return 0;
}


function rand_string($len) {
  $ans= '';
  while($len--) $ans .= chr(rand(65,90));
  return $ans;
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
  set_user();
  if (isset($USER{'AccessLevel'})) { $type = $USER{'AccessLevel'}; } else { $type = 0; }
//  var_dump($USER);
//  echo "$type<p>";
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
//    $ErrorMessage = $message;
//    var_dump($message);
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

$YEARDATA = Get_General();
if ($YEARDATA['Years2Show'] > 0) $NEXTYEARDATA = Get_General($YEAR+1);

function First_Sent($stuff) {
  $onefifty=substr($stuff,0,150);
  return (preg_match('/^(.*?[.!?])\s/s',$onefifty,$m) ? $m[1] : $onefifty);
}

function munge_array(&$thing) {
  if (isset($thing) && is_array($thing)) return $thing;
  return [];
}

function Send_SysAdmin_Email($Subject,&$data=0) {
  include_once("Email.php");
  $dat = json_encode($data);
  NewSendEmail('richard@wavwebs.com',$Subject,$dat);  
}

$head_done = 0;

function doextras($extras) {
  global $FESTSYS;
  $V=$FESTSYS['V'];
  if ($extras) foreach ($extras as $e) {
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

// If Banner is a simple image then treated as a basic banner image with title overlaid otherwise what is passed is used as is
function dohead($title,$extras=[],$Banner='',$BannerOptions=' ') {
  global $head_done,$FESTSYS,$CONF;
  if ($head_done) return;
  $V=$FESTSYS['V'];
  $pfx="";
  if (isset($CONF['TitlePrefix'])) $pfx = $CONF['TitlePrefix'];
  echo "<html><head>";
  echo "<title>$pfx " . $FESTSYS['FestName'] . " | $title</title>\n";
  include_once("files/Newheader.php");
  if ($extras) doextras($extras);
  echo "</head><body>\n";

    echo "<div class=contentlim>";  
    include_once("files/Newnavigation.php");

    if ($Banner) {
      if ($Banner == 1) {
        echo "<div class=WMFFBanner400><img src=" . $FESTSYS['DefaultPageBanner'] . " class=WMFFBannerDefault>";
        echo "<div class=WMFFBannerText>$title</div>";
        if (!strchr('T',$BannerOptions)) echo "<img src=/images/icons/torn-top.png class=TornTopEdge>";
        echo "</div>";
      } else if (preg_match('/^(https?:\/\/|\/?images\/)/',$Banner)) {
        echo "<div class=WMFFBanner400><img src=$Banner class=WMFFBannerDefault>";
        echo "<div class=WMFFBannerText>$title</div>";
        if (!strstr($BannerOptions,'T')) echo "<img src=/images/icons/torn-top.png class=TornTopEdge>";
        echo "</div>";
      } else {
        echo $Banner;
      }
    } else {
      echo "<div class='NullBanner'></div>";  // Not shure this is needed
    }

    echo "<div class=mainwrapper><div class=maincontent>";  
  $head_done = 1;
}

//  No Banner 
function doheadpart($title,$extras=[]) {
  global $head_done,$FESTSYS,$CONF;
  if ($head_done) return;
  $V=$FESTSYS['V'];
  $pfx="";
  if (isset($CONF['TitlePrefix'])) $pfx = $CONF['TitlePrefix'];
  echo "<html><head>";
  echo "<title>$pfx " . $FESTSYS['FestName'] . " | $title</title>\n";
  include_once("files/header.php");
  if ($extras) doextras($extras);
  $head_done = 1;
}

// No Banner
function dostaffhead($title,$extras=[]) {
  global $head_done,$FESTSYS,$CONF;
  if ($head_done) return;
  $V=$FESTSYS['V'];
  $pfx="";
  if (isset($CONF['TitlePrefix'])) $pfx = $CONF['TitlePrefix'];
  echo "<html><head>";
  echo "<title>$pfx " . $FESTSYS['ShortName'] . " | $title</title>\n";
  if (Feature('NewStyle') && ! UserGetPref('StaffOldFormat')) {
    include_once("files/Newheader.php");
    include_once("festcon.php");
    if ($extras) doextras($extras);
    echo "<meta http-equiv='cache-control' content=no-cache>";
    echo "</head><body>\n";
    include_once("files/Newnavigation.php");
    echo "<div class=content>";  
  } else {
    include_once("files/header.php");
    include_once("festcon.php");
    if ($extras) doextras($extras);
    echo "<meta http-equiv='cache-control' content=no-cache>";
    echo "</head><body>\n";
    include_once("files/navigation.php"); 
    echo "<div class=content>";
  }
  $head_done = 1;
}

// No Banner
function dominimalhead($title,$extras=[]) { 
  global $head_done,$FESTSYS,$CONF;
  $V=$FESTSYS['V'];
  $pfx="";
  if (isset($CONF['TitlePrefix'])) $pfx = $CONF['TitlePrefix'];
  echo "<html><head>";
  echo "<title>$pfx " . $FESTSYS['ShortName'] . " | $title</title>\n";
//  echo "<link href=files/Newstyle.css?V=$V type=text/css rel=stylesheet>";
  echo "<script src=/js/jquery-3.2.1.min.js></script>";
  if ($extras) doextras($extras);
  echo "<script>" . $FESTSYS['Analytics'] . "</script>";
  echo "</head><body>\n";
  $head_done = 2;
}

function dotail() {
  global $head_done;

  echo "</div>";
  if ($head_done == 1) include_once("files/Newfooter.php"); // Not minimal head
  echo "</body></html>\n";
  exit;
}
 

?>
