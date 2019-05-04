<?php

/* Various common code across fest con tools */

  include_once("festdb.php");
  include_once("festfm.php");

$BUTTON = 0;

if (isset($_POST{'Y'})) $YEAR = $_POST{'Y'};
if (isset($_GET{'Y'})) $YEAR = $_GET{'Y'};
if (isset($_POST{'B'})) $BUTTON = ($_POST{'B'}+1) % 4;
if (isset($_GET{'B'})) $BUTTON = ($_GET{'B'}+1) % 4;

if (isset($YEAR)) {
  if (!is_numeric($YEAR)) exit("Invalid Year");
} else {
  $YEAR = $CALYEAR;
}

$Noise_Levels = array("Middling","Quiet","Noisy");
$Noise_Colours = ['lightgreen','yellow','Orange'];
$Coming_States = array('','Received','Coming','Not coming','Possibly','Not coming, please ask next year');
$Coming_Colours = ['white','Yellow','Lime','salmon','lightblue','Orange'];
$Coming_idx = array('','R','Y','N','P','NY');
$Coming_Type = array_flip($Coming_idx);
$Invite_States = array('','Yes','YES!','No','Maybe');
$Invite_Type = array_flip($Invite_States);
$Dance_Comp = ['Don\'t Know','Yes','No'];
$Dance_Comp_Colours = ['white','lime','salmon'];
$Surfaces = ['','Tarmac','Flagstones','Grass','Stage','Brick','Wood','Carpet','Astroturf'];// Last 3 not used yet
$Surface_Colours = ['','grey','Khaki','lightgreen','Peru','salmon','Peru','Teal','lime'];
$Side_Statuses = array("Alive","Dead");
$Share_Spots = array('Prefered','Always','Never','Sometimes');
$Share_Type = array_flip($Share_Spots);
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
$TicketStates = array('Not Yet','Open','Closed');
$ArticleFormats = ['Large Image','Small Image','Text','Banner Image','Banner Text','Fixed','Left/Right Pairs'];
$PerfTypes = ['Dance Side'=>['IsASide','Dance','Dance','Dance','D'],
              'Musical Act'=>['IsAnAct','Music','Music','Music','M'],
              'Comedy'=>['IsFunny','Comedy','Comedy','Comedy','C'],
              'Child Ent'=>['IsFamily','Children','Family','Youth','Y'],
              'Other'=>['IsOther','Info','Other','','O']];
$PerfIdx = ['Side'=>0,'Act'=>1,'Comic'=>2,'ChEnt'=>3,'Other'=>4];
// Perfname => [field to test, email address for,Capability name,budget,shortCode]


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
      if ($USERID == 35) {
        $logf = fopen("LogFiles/U35.txt",'a+');
        if ($logf) {
          fwrite($logf,date('d/m H:j:s '));
          fwrite($logf,$_SERVER['PHP_SELF']);
          fwrite($logf,json_encode($_REQUEST));
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
  if (!$USER) return 0;
  if (preg_match("/$pref\:(.*)\n/",$USER['Prefs'],$rslt)) return trim($rslt[1]);
  return 0;
}


function rand_string($len) {
  $ans= '';
  while($len--) $ans .= chr(rand(65,90));
  return $ans;
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
  else { $name = $data['SN']; }
  if ($type="Side") { $id = $data['SideId']; }
  else { $id = $data = $data['ActId']; };

  $ProgInfo = Show_Prog($type,$id,1);

  $lnk = "<a href=mailto:$email?from=" . $USER['Email'] .
         "&subject=" . urlencode("Wimborne Minster Folk Festival $YEAR and " . $data['SN']) . 
         "&body=" . urlencode("$name,\n\n" .
                 "You can check your programme times and update your side details at any time by visiting " .
                 "<a href=https://" . $_SERVER['HTTP_HOST'] . "/int/Direct.php?t=$type&id=$id&key=$key>this link</a>.  " .
                $ProgInfo . "\n\n" .
                 "\n\nRegards " . $USER['SN'] . "\n\n") .
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
  if (!Access('Staff')) return 0;
  include_once("DanceLib.php");
  $Label = '';
  if (isset($data['HasAgent']) && ($data['HasAgent'])) {
    if ($xtr == '') {
      if (!isset($data["AgentEmail"])) return "";
      $email = $data['AgentEmail'];
      $xtr = 'Agent';
      if (isset($data['AgentName'])) { $name = firstword($data['AgentName']); }
      else { $name = $data['SN']; }
    } else if ($xtr == '!!') {
      if (!isset($data["Email"])) return "";
      $email = $data['Email'];
      $xtr = '';
      $Label = 'Direct ';
      if (isset($data[$xtr .'Contact'])) { $name = firstword($data[$xtr .'Contact']); }
      else { $name = $data['SN']; }
    } else {
      if (!isset($data[$xtr . "Email"])) return "";
      $email = $data[$xtr . 'Email'];
      $Label = $xtr;
      if (isset($data[$xtr .'Contact'])) { $name = firstword($data[$xtr .'Contact']); }
      else { $name = $data['SN']; }
    }
  } else {
    if ($xtr == '!!') $xtr = '';
    if (!isset($data[$xtr . "Email"])) return "";
    $email = $data[$xtr . 'Email'];
    $Label = $xtr;
    if (isset($data[$xtr .'Contact'])) { $name = firstword($data[$xtr .'Contact']); }
    else { $name = $data['SN']; }
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
         "&subject=" . urlencode("Wimborne Minster Folk Festival $YEAR and " . $data['SN']) . "'";
  $direct = "<a href=https://" . $_SERVER['HTTP_HOST'] . "/int/Direct.php?t=$type&id=$id&key=$key&Y=$YEAR>this link</a>  " ;

  if (isset($data['SideId'])) {
    if ($data['IsASide'] && !$data['TotalFee']) {
      $ProgInfo = Show_Prog($type,$id,1);
      $Content = urlencode("$name,<p>" .
              "<div id=SideLink$id>" .
              "Please add/correct details about your side's contact information and your preferences in " .
              "terms of days coming, number of dance spots, etc. by visiting $direct.</div><p>" .
              "You can update information at any time, until the programme goes to print. " .
              "(You'll also be able to view your programme times, once we've done the programme)<p>" .
              "<div id=SideProg$id>$ProgInfo</div><p>" .
              "Regards " . $USER['SN'] . "<p>"); 
    } else {
      include_once("MusicLib.php");
      $Content = MusicMail($data,$name,$id,$direct);
    }
  } else { // Trade/Invoicing (I think gets here)
    $Content = urlencode("$name,<p>" . "Regards " . $USER['SN'] . "<p>"); 
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

$MASTER = Get_General();

function First_Sent($stuff) {
  $onefifty=substr($stuff,0,150);
  return (preg_match('/^(.*?[.!?])\s/s',$onefifty,$m) ? $m[1] : $onefifty);
}

function munge_array(&$thing) {
  if (isset($thing) && is_array($thing)) return $thing;
  return [];
}

function Show_Prog($type,$id,$all=0,$price=0) { //mode 0 = html, 1 = text for email
    global $DayList,$db;
    $str = '';
    include_once("ProgLib.php");
    include_once("DanceLib.php");
    $Evs = Get_All_Events_For($type,$id,$all);
    $ETs = Get_Event_Types(1);
    $side = Get_Side($id);
//echo "Type: $type, $id<p>";
//var_dump($Evs);
    $evc=0;
    $Worst= 99;
    $EventLink = ($all?'EventAdd.php':'EventShow.php');
    $VenueLink = ($all?'AddVenue.php':'VenueShow.php');
    $Venues = Get_Real_Venues(1);
    if ($Evs) { // Show IF all or EType state > 1 or (==1 && participant)
      $With = 0;
      $Price = 0;
      foreach ($Evs as $e) {
        if ($e["BigEvent"] || ($e['IsConcert'] && $e['SubEvent']>0)) { $With = 1;  }
        for ($i = 1; $i<5;$i++) if ($e["Side$i"] && $e["Side$i"] != $id) { $With = 1; break; }
        if ($price && ( $e['Price1'] || ($e['IsConcert'] && $e['SubEvent']>0))) $Price = 1; // Maybe slightly too likely to set Price, but it probably does not matter
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
              case 'Perf':
                if ($O['Identifier'] == $id) {
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
            $str .= "<tr><td $cls>" . FestDate($e['Day'],'M') . "<td $cls>" . timecolon($e['Start']) . "-" . timecolon(($e['SubEvent'] < 0 ? $e['SlotEnd'] : $e['End'] )) .
                        "<td $cls><a href=/int/$EventLink?e=" . $e['EventId'] . ">" . $e['SN'] . "</a><td $cls>";
            if ($VenC) $str .= " starting from ";
            $str .= "<a href=/int/$VenueLink?v=" . $e['Venue'] . ">" . VenName($Venues[$e['Venue']]) ;
            $str .= "</a><td $cls>";
            if ($e['NoOrder']==0) {
              if ( $PrevI || $NextI) $str .= "In position $Position";
              if ($PrevI) { $str .= ", After " . SAO_Report($PrevI); };
              if ($NextI) { $str .= ", Before " . SAO_Report($NextI); };
            }
            if ($Price) $str .= "<td>" . Price_Show($e,1);
            $str .= "\n";
          } else if ($e['IsConcert'] && $e['SubEvent'] > 0) {
            // Need all other perfs, concert start & end
            $Parent = $e['SubEvent'];
            $pe = Get_Event($Parent);
            $res=$db->query("SELECT * FROM Events WHERE SubEvent=$Parent ORDER BY Day, Start");
            $with = [];
            while ($ev = $res->fetch_assoc()) {
              for ($i=1;$i<5;$i++) {
                if ($ev["Side$i"] > 0 && $ev["Side$i"] != $id) { 
                  $with[] = SAO_Report($ev["Side$i"]);
                }
              }
            }
            $str .= "<tr><td $cls>" . FestDate($e['Day'],'M') . "<td $cls>" . timecolon($pe['Start']) . "-" . timecolon($pe['End'] ) .
                        "<td $cls><a href=/int/$EventLink?e=$Parent>" . $pe['SN'] . 
                        "</a><td $cls><a href=/int/$VenueLink?v=" . $pe['Venue'] . ">" . VenName($Venues[$pe['Venue']]) . "</a>" .
                        "<td>" . implode(', ',$with) . "<br>" . $side['SN'] . " will be performing from " . timecolon($e['Start']) . " to " . timecolon($e['End']);
            if ($Price) $str .= "<td>" . Price_Show($pe,1);
          
          } else { // Normal Event
            $str .= "<tr><td $cls>" . FestDate($e['Day'],'M') . "<td $cls>" . timecolon($e['Start']) . "-" . timecolon(($e['SubEvent'] < 0 ? $e['SlotEnd'] : $e['End'] )) .
                        "<td $cls><a href=/int/$EventLink?e=" . $e['EventId'] . ">" . $e['SN'] . 
                        "</a><td $cls><a href=/int/$VenueLink?v=" . $e['Venue'] . ">" . VenName($Venues[$e['Venue']]) . "</a>";
            if ($With) {
              $str .= "<td $cls>";
              $withc=0;
              for ($i=1;$i<5;$i++) {
                if ($e["Side$i"] > 0 && $e["Side$i"] != $id) { 
                  if ($withc++) $str .= ", "; 
                  $str .= SAO_Report($e["Side$i"]);
                }
              }
            }
            if ($Price) $str .= "<td>" . Price_Show($e,1);
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
        $str = "<h2>$Desc Programme for " . $Thing['SN'] . ":</h2>\n" . ($UsedNotPub?"<span class=NotCSide>These are not currently public<p>\n</span>":"") .
                "<table border class=PerfProg><tr><td>Day<td>time<td>Event<td>Venue" . ($With?'<td>With':'') . ($Price?'<td>':'') . $str;
      }
    }
    if ($evc) {
      $str .= "</table>\n";    
    }

//var_dump($str);

  return $str;
}

function Send_SysAdmin_Email($Subject,&$data=0) {
  include_once("Email.php");
  $dat = json_encode($data);
  NewSendEmail('richard@wavwebs.com',$Subject,$dat);  
}

$head_done = 0;

function doextras($extras) {
  global $MASTER_DATA;
  $V=$MASTER_DATA['V'];
  foreach ($extras as $e) {
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
  global $head_done,$MASTER_DATA,$CONF;
  if ($head_done) return;
  $V=$MASTER_DATA['V'];
  $pfx="";
  if (isset($CONF['TitlePrefix'])) $pfx = $CONF['TitlePrefix'];
  echo "<html><head>";
  echo "<title>$pfx " . $MASTER_DATA['FestName'] . " | $title</title>\n";
  include_once("files/header.php");
  if ($extras) doextras($extras);
  echo "</head><body>\n";

  if (Feature('NewStyle')) {

    echo "<div class=contentlim>";  
    include_once("files/Newnavigation.php");

    if ($Banner) {
      if ($Banner == 1) {
        echo "<div class=WMFFBanner400><img src=" . $MASTER_DATA['DefaultPageBanner'] . " class=WMFFBannerDefault>";
        if (!strchr('T',$BannerOptions)) echo "<img src=/images/icons/torn-top.png class=TornTopEdge>";
        echo "<div class=WMFFBannerText>$title</div>";

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
  } else {
    echo "<div id=HeadRow>";
    if ($MASTER_DATA['AdvertImgLeft']) { 
      echo "<a href=" . $MASTER_DATA['AdvertLinkLeft'] . "><img src=" . $MASTER_DATA['AdvertImgLeft'] . " id=leftspon hidden></a>";
    } else echo "<center>";
    echo "<a href=/><img id=HeadBan src=" . $MASTER_DATA['WebSiteBanner'] . "?V=$V ></a></center>";
    if ($MASTER_DATA['AdvertImgRight']) { 
      echo "<a href=" . $MASTER_DATA['AdvertLinkRight'] . "><img src=" . $MASTER_DATA['AdvertImgRight'] . " id=rightspon hidden></a>";
    } else if ($MASTER_DATA['AdvertImgLeft']) echo "<a href=" . $MASTER_DATA['AdvertLinkLeft'] . "><img src=" . $MASTER_DATA['AdvertImgLeft'] . " id=rightspon hidden></a>";
    echo "</div>\n";
    echo "<script src=/js/WmffAds.js?V=$V></script>";

    include_once("files/navigation.php"); 
    echo "<div class=mainwrapper><div class=contentlim>";
  }

  $head_done = 1;
}

//  No Banner 
function doheadpart($title,$extras=[]) {
  global $head_done,$MASTER_DATA,$CONF;
  if ($head_done) return;
  $V=$MASTER_DATA['V'];
  $pfx="";
  if (isset($CONF['TitlePrefix'])) $pfx = $CONF['TitlePrefix'];
  echo "<html><head>";
  echo "<title>$pfx " . $MASTER_DATA['FestName'] . " | $title</title>\n";
  include_once("files/header.php");
  if ($extras) doextras($extras);
  $head_done = 1;
}

// No Banner
function dostaffhead($title,$extras=[]) {
  global $head_done,$MASTER_DATA,$CONF;
  if ($head_done) return;
  $V=$MASTER_DATA['V'];
  $pfx="";
  if (isset($CONF['TitlePrefix'])) $pfx = $CONF['TitlePrefix'];
  echo "<html><head>";
  echo "<title>$pfx " . $MASTER_DATA['ShortName'] . " | $title</title>\n";
  include_once("files/header.php");
  include_once("festcon.php");
  if ($extras) doextras($extras);
  echo "<meta http-equiv='cache-control' content=no-cache>";
  echo "</head><body>\n";
  if (Feature('NewStyle')) {
    include_once("files/Newnavigation.php");
    echo "<div class=content>";  
  } else {
    include_once("files/navigation.php"); 
    echo "<div class=content>";
  }

  $head_done = 1;
}

// No Banner
function dominimalhead($title,$extras=[]) { 
  global $head_done,$MASTER_DATA,$CONF;
  $V=$MASTER_DATA['V'];
  $pfx="";
  if (isset($CONF['TitlePrefix'])) $pfx = $CONF['TitlePrefix'];
  echo "<html><head>";
  echo "<title>$pfx " . $MASTER_DATA['ShortName'] . " | $title</title>\n";
  echo "<link href=files/style.css?V=$V type=text/css rel=stylesheet>";
  echo "<script src=/js/jquery-3.2.1.min.js></script>";
  if ($extras) doextras($extras);
  echo "<script>" . $MASTER_DATA['Analytics'] . "</script>";
  echo "</head><body>\n";
  $head_done = 2;
}

function dotail() {
  global $head_done;

  if (Feature('NewStyle')) {
    echo "</div>";
    if ($head_done == 1) include_once("files/Newfooter.php");  
  } else {
    echo "</div></div>";
    if ($head_done == 1) include_once("files/footer.php");
  }
  echo "</body></html>\n";
  exit;
}
 

?>
