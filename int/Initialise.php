<?php

// Initialise festival system
// create databases
// preload databases as needed - Master_Data and FestUsers
// create and populate directories
// No problem to run again and again
// TODO security if run on a live system

// Note this does not call fest as it must run without a db, it uses Configure.ini and 

// IFF Config.ini read it and use, else prompt for data set it up and then use 

$CONF = [];
include_once("festfm.php"); // Not db or main fest

function Check_PHP_Config() {
  if (!strstr(get_include_path(),$_SERVER['DOCUMENT_ROOT'])) {
    echo "The document Root is not part of the php include path - LOTS of things depend on this<p>";
    exit;
  }
  // Should check for open_basedir and file size eventually
}


function Get_Config() {
  global $CONF;
  if (@ !$CONF = parse_ini_file("Configuration.ini")) {
    $CONF = ['host'=>'localhost','user'=>'wmff','passwd'=>'','dbase'=>'wmff','testing'=>''];
    return 0;
  }
  return 1;
}

function Create_Config() {
  global $CONF;
  if (Get_Config()) return;
  
  if (!isset($_POST['dbase']) || !isset($_POST['user'])) {
    echo "<html><head><title>Festival System Setup</title></head><body>";
    echo "<form method=post><div class=tablecont><table border>\n";
    echo "<tr>" . fm_text("Host Name - usually localhost",$_POST,'host');
    echo "<tr>" . fm_text("Database Name - must be unique to server",$_POST,'dbase');
    echo "<tr>" . fm_text("Database User - Must be already setup",$_POST,'user');
    echo "<tr>" . fm_text("Database Password (if any)",$_POST,'passwd');
    echo "<tr>" . fm_text("Testing mode - blank for live, 1 for simple test (no emails), an email address to divert all emails too",$_POST,'testing');
    echo "<tr>" . fm_text("Title Prefix - for test/stage/dev sites only",$_POST,'TitlePrefix');
    echo "</table></div><input type=submit></form>\n";
    echo "</body></html>\n";
    exit;
  }
  echo "Now to do the setup<p>";

  $Config = "
[FF]

;;;;;;;;;;;;;;;;;;;
; About Configuration.ini 
;;;;;;;;;;;;;;;;;;;
; comments start with ;

; host - usually localhost
host=" . $_POST['host'] . "

; username for the database
user=" . $_POST['user'] . "

; password for the database
passwd=" . $_POST['passwd'] . "

; database to be used
dbase=" . $_POST['dbase'] . "

; testing - if not set the system will send emails normally
; if it contains an @ it is treated as an email address to send all emails to
; otherwise no emails are sent
testing=" . $_POST['testing'] . "

; Title Prefix - prepended to Title string - useful for test environments
TitlePrefix=" . $_POST['TitlePrefix'] . "

; everything else is configured from with the festival software itself
";
  if (!file_put_contents("Configuration.ini",$Config)) {
    echo "Could not create configuration file";
    exit;
  }

  if (Get_Config()) return;
  echo "Config file created but reading it failed";
  exit; 
}

function Create_Directories() {  // Makes all needed directories and adds .htaccess where appropriate
  global $CONF;
  $Dirs = [['int/ArchiveImages',1],  // dir name, access control
           ['int/Contracts',1],
           ['int/Insurance',1],
           ['int/Invoices',1],
           ['int/LogFiles',1],
           ['int/OldStore',1],
           ['int/PAspecs',1],
           ['int/Store',1],
           ['int/Temp',0],
          ];
  $LinkedDirs = ['js','files','cache','images'];
  foreach($Dirs as $D) {
    if (!file_exists("../" . $D[0])) {
    
      mkdir("../" . $D[0],0777,true);
      chmod("../" . $D[0],0777);
      echo "Creating " . $D[0] . "<br>";
    }
    if ($D[1] && !file_exists("../" . $D[0] . "/.htaccess")) file_put_contents("../" . $D[0] . "/.htaccess","order deny,allow\ndeny from all");
  }
  foreach($LinkedDirs as $D) {
    if (!file_exists("../" . $D)) mkdir("../" . $D,0777,true);
    if (!file_exists($D)) symlink ("../" . $D, $D);
  }
  echo "Directories Created<p>";
}

function Create_Databases() {
  global $CONF;
  //  Does the database exist?
  try {
    $db = new mysqli($CONF['Host'], $CONF['user'], $CONF['passwd']);
  } catch (\Exception $e) {
    echo $e->getMessage(), PHP_EOL;
    echo "Can't access mysql - aborting for now - you can retry once corrected<p>";
    exit;
  }
  if ($db->select_db($CONF['dbase']) === false) {
    echo "Database to be created .<p>";
 
    $res = $db->query("CREATE DATABASE IF NOT EXISTS " . $CONF['dbase']);
    if ($db->select_db($CONF['dbase']) === false) {
      echo "Database creation failed " . $db->connect_error;
      exit; 
    }
    echo "Database created<br>";
  
  } else {
    echo "Database already exists.<p>";
  }
}


// Modifys name of database for Skeema to run
function Create_Skeema_local() {
  global $CONF;
  if (!file_exists("../Schema/.skeema")) {
    $skeema = "schema=" . $CONF['dbase'] . "
default-character-set=utf8mb4
default-collation=utf8mb4_general_ci

host=127.0.0.1
port=3306 
user=" . $CONF['user'] . "\n";
    if ($CONF['passwd']) $schema .= "password=" . $CONF['passwd'] . "\n"; 
    file_put_contents("../Schema/.skeema",$skeema);
  }
  
  chdir ("..");
  system("int/skeema push"); // push for live
  chdir ("int");
  echo "Database tables created.<p>";
}

// [Table, id, [data]] 

function Preload_Data() {
  global $db;
  $Year = gmdate('Y');
  // Does not do Email Proformas - see below for them
  $Preloads = [
    ['FestUsers', 1,['Login'=>'system','password'=>'WM/boBz3JdYIA','AccessLevel'=>7,'Roll'=>'Start up']],
    ['FestUsers', 2,['Login'=>'nobody','AccessLevel'=>7,'Roll'=>'Internal Workings']],
    ['FestUsers', 3,['Login'=>'ALL','AccessLevel'=>4,'Roll'=>'Internal Workings','SN'=>'All']],
    ['FestUsers', 4,['Login'=>'dummy','AccessLevel'=>7,'Roll'=>'Dummy Contracts','SN'=>'<span class=NotSide>Dummy Staff Member</span>']],
    ['FestUsers', 5,['Login'=>'reserved']],
    ['FestUsers', 6,['Login'=>'reserved']],
    ['FestUsers', 7,['Login'=>'reserved']],
    ['FestUsers', 8,['Login'=>'reserved']],
    ['FestUsers', 9,['Login'=>'reserved']],
    ['FestUsers', 10,['Login'=>'reserved']],

    ['MasterData',1,['FestName'=>'Festival','ShortName'=>'Fest','Version'=>666,'PlanYear'=>$Year, 'ShowYear'=>$Year]],
    ['General',$Year,[]],
    
    ['MapPointTypes',1,['SN'=>'Text','Icon'=>'Text']],
    ['MapPointTypes',2,['SN'=>'Music Venue','Icon'=>'MusicIcon.png']],
    ['MapPointTypes',3,['SN'=>'Car Park','Icon'=>'carparkicon.png']],
    ['MapPointTypes',4,['SN'=>'Toilets','Icon'=>'toileticon.png']],
    ['MapPointTypes',5,['SN'=>'Information','Icon'=>'mapinfo.png']],
    ['MapPointTypes',6,['SN'=>'Dance Venue','Icon'=>'DanceIcon.png']],
    ['MapPointTypes',7,['SN'=>'Bicycle Park','Icon'=>'bicycleicom.png']],
    
    ['Directories',0,[`SN`=>'DataRoot', 'Created'=>1, 'Who'=>1, 'Parent'=>0, 'State'=>0, 'AccessLevel'=>0, 'AccessSections'=>'', 'ExtraData'=>'']],

  ];

  // Now call festdb
  include_once("festdb.php");
  foreach($Preloads as $P) {
    $indx = (isset($TableIndexes[$P[0]])? $TableIndexes[$P[0]] : 'id');
    if (db_get($P[0],"$indx=" . $P[1])) continue; // already in - skip
    $qry = "INSERT INTO " . $P[0] . " SET ";
    $bits = [];
    $bits[] = " $indx=" . $P[1];
    foreach($P[2] as $k=>$v) $bits[] = " $k='$v' ";
    $qry .= implode(", ",$bits);
    $db->query($qry);
  }

// Email proformas - lots of these read from munged sql dump
  $file = fopen('files/EmailProformas.sql','r');
  while ($line = fgets($file)) {
    $bits = explode(',',$line,2);
    $key = preg_replace('/\'/','',$bits[0]);
    if (!db_get('EmailProformas','SN=' . $bits[0])) {
      $db->query("INSERT INTO EmailProformas SET SN=" . $bits[0] . ", Body=" . trim($bits[1]));
      echo "Created Email Proforma: $key<br>\n";
    }
  }
}

// Updating code - not yet written
function BringUptoDate($oldversion) {
  
  
}

function Check_Sysadmin() {

  include_once("DocLib.php");
  include_once("UserLib.php");
  global $Access_Type;
  
  $Users = Get_AllUsers(2);
  $isasys = 0;
  
  foreach($Users as $U) if ($U['AccessLevel'] == $Access_Type['SysAdmin']) $isasys = 1;
  
  if ($isasys) return;  // There is a sysadmin setup - skip
  
  echo "<form method=post><h2>Setup a sysadmin account</h2>";
  echo "<div class=tablecont><table><tr>" . fm_text("Login",$_POST,'login');
  echo "<tr>" . fm_text("Password",$_POST,'password');
  echo "<tr>" . fm_text("Full Name",$_POST,'SN');
  echo "</table><div><p>";
  echo "<input type=submit name=SETUPSYS value=SETUP>";
  exit;
}

function Setup_Sysadmin() {
  global $Access_Type;
  
  $Users = Get_AllUsers(2);
  $isasys = 0;
  
  foreach($Users as $U) if ($U['AccessLevel'] == $Access_Type['SysAdmin']) $isasys = 1;
  if ($isasys) return;  // There is a sysadmin setup - skip

  $user = ['Login'=>$_POST['login'], 'AccessLevel'=> $Access_Type['SysAdmin'], 'password'=> crypt($_POST['password'],"WM"), 'SN'=>$_POST['SN']];
  $userid = Insert_db('FestUsers',$user,$ans);
  echo "SysAdmin setup.<p>";
  $ans['UserId'] = $userid;
  $ans['Yale'] = rand_string(40);
  $USER = $ans;
  $USERID = $userid;
  setcookie('WMFF2',$ans['Yale'], mktime(0,0,0,1,1,$YEAR+1),'/');
  Put_User($ans);
}

if (isset($_POST['SETUPSYS'])) {
  include_once("fest.php");
  Setup_Sysadmin();
} else {
  Check_PHP_Config();
  Create_Directories();
  Create_Config();
  Create_Databases();
  Create_Skeema_local();
  Preload_Data();
  include_once("fest.php");
  Check_Sysadmin();
}

echo "All done<p>";
include ("Staff.php"); // no return wanted

/* 

  
  bring_uptodate run from old version to new version
  
  run any neededscripts to mod data from old to new

  php include path $_SYSTEM['DOCUMENT_ROOT'] get_include_path
*/

?>
