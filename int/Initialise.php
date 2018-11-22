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

function Get_Config() {
  if (@ !$CONF = parse_ini_file("Configuration.ini")) {
    $CONF = ['host'=>'localhost','user'=>'wmff','passwd'=>'','dbase'=>'wmff','testing'=>''];
    return 0;
  }
  return 1;
}

function Create_Config() {
  if (Get_Config()) return;
  
  if (!isset($_POST['dbase']) || !isset($_POST['user'])) {
    echo "<html><head><title>Festival System Setup</title></head><body>";
    echo "<form method=post><table border>\n";
    echo "<tr>" . fm_text("Host Name - usually localhost",$_POST,'host');
    echo "<tr>" . fm_text("Database Name - must be unique to server",$_POST,'dbase');
    echo "<tr>" . fm_text("Database User - Must be already setup",$_POST,'user');
    echo "<tr>" . fm_text("Database Password (if any)",$_POST,'passwd');
    echo "<tr>" . fm_text("Testing mode - blank for live, 1 for simple test, an email address to divert all emails too",$_POST,'testing');
    echo "</table><input type=submit></form>\n";
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
host = " . $_POST['host'] . "

; username for the database
user = " . $_POST['user'] . "

; password for the database
passwd = " . $_POST['passwd'] . "

; database to be used
dbase = " . $_POST['dbase'] . "

; testing - if not set the system will send emails normally
; if it contains an @ it is treated as an email address to send all emails to
; otherwise no emails are sent
testing = " . $_POST['testing'] . "

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
  foreach($Dirs as $D) {
    if (!file_exists("../" . $D[0])) {
    
      mkdir("../" . $D[0],0777,true);
      chmod("../" . $D[0],0777);
      echo "Creating " . $D[0] . "<br>";
    }
    if ($D[1] && !file_exists("../" . $D[0] . "/.htaccess")) file_put_contents("../" . $D[0] . "/.htaccess","order deny,allow\ndeny from all");
  }
  echo "Directories Created<p>";
}

function Create_Databases() {
  //  Does the database exist?
  try {
    $db = new mysqli($CONF['Host'], $CONF['user'], $CONF['passwd']);
  } catch (\Exception $e) {
    echo $e->getMessage(), PHP_EOL;
    echo "Can't access mysql - aborting for now - you can retry once corrected<p>";
    exit;
  }
  if ($db->select_db($CONF['dbase']) === false) {
    echo "Database to be created - if it fails during creation, fix, drop the database and retry.  There is not yet the capability to fix a partially built system<p>";
 
    $res = $db->query("CREATE DATABASE IF NOT EXISTS " . $CONF['dbase']);
    if ($db->select_db($CONF['dbase']) === false) {
      echo "Database creation failed " . $db->connect_error;
      exit; 
    }
    echo "Database created<br>";
  
  } else {
    echo "Database already exists - table creation skipped - if it fails during creation, fix, drop the database and retry.  There is not yet the capability to fix a partially built system<p>";
  }
}


// Modifys name of database for Skeema to run
function Create_Skeema_local() {
  if (!file_exists("../Schema/.skeema")) {
    $skeema = "schema=" . $CONF['dbase'] . "
default-character-set=utf8mb4
default-collation=utf8mb4_general_ci

host=127.0.0.1
port=3306 
user=" . $CONF['user'] . "\n";
    if ($CONF['passwd']) $schema .= "password=" . $CONF['passwd'] . "\n"; 
  }
  
  system("skeema diff"); // push for live
}

// [Table, [data]] - may contain id
function Preload_Data() {
  $Year = gmdate('Y');
  $Preloads = [
    ['FestUsers', ['UserId'=>1,'login'=>'system','password'=>'WMFh5W42eE2.E','AccessLevel'=>7,'Roll'=>'Start up','SN'=>'System']],
    ['FestUsers', ['UserId'=>2,'login'=>'nobody','AccessLevel'=>7,'Roll'=>'Internal Workings','SN'=>'nobody']],
    ['FestUsers', ['UserId'=>3,'login'=>'ALL','AccessLevel'=>4,'Roll'=>'Internal Workings','SN'=>'All']],
    ['FestUsers', ['UserId'=>4,'login'=>'dummy','AccessLevel'=>7,'Roll'=>'Dummy Contracts','SN'=>'<span class=NotSide>Dummy Staff Member</span>']],
    ['MasterData',['id'=>1,'FestName'=>'Festival','ShortName'=>'Fest','Version'=>666,'PlanYear'=>$Year, 'ShowYear'=>$Year]],
    ['General',['Year'=>$Year]],
  ];

  foreach($Preloads as $P) {
    
  }


// Initial MASTER_DATA

// A Year

// A User, internal users


}

function BringUptoDate($oldversion) {
  
  
}



Create_Directories();
Create_Config();
Create_Databases();
Create_Skeema_local();
Preload_Data();


echo "All done<p>";

/* 

  get contributions from github - save to update master_data
  
  operate skeema - create/mod data
  
  bring_uptodate run from old version to new version
  
  run any neededscripts to mod data from old to new

  Need to embed skeema in project - done


*/

?>
