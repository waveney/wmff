<?php

// Initialise festival system
// create databases
// preload databases as needed - Master_Data and FestUsers
// create and populate directories
// No problem to run again and again
// TODO security if run on a live system

// Note this does not call fest as it must run without a db, it uses Configure.ini and 

$CONF = [];

function Get_Config() {
  if (@ !$CONF = parse_ini_file("Configuration.ini")) {
    $CONF = ['host'=>'localhost','user'=>'wmff','passwd'=>'','dbase'=>'wmff','testing'=>''];
  }
}

function Create_Directories() {  // Makes all needed directories and adds .htaccess where appropriate
  $Dirs = [['int/ArchiveImages',1],  // dir name, access control
           ['int/Contracts',1],
           ['int/Insurance',1],
           ['int/Invoices',1],
           ['int/LogFiles',1],
           ['int/OldStore',1],
           ['int/PASpecs',1],
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
    // Go through Skeema make all tables
    $Dir = opendir("../Scheema/wmff/");
    
    
    // Might be better off embeding skeema?
 
  
  } else {
    echo "Database already exists - table creation skipped - if it fails during creation, fix, drop the database and retry.  There is not yet the capability to fix a partially built system<p>";
  }
     
    // Create db
}



  // if so report and no action
}


function Preload_Data() {
// Initial MASTER_DATA

// A Year

// A User


}

// Modifys name of database for Skeema to run
function Modify_Skeema_local() {


}


function BringUptoDate($oldversion) {
  
  
}



Create_Directories();
Create_Databases();
Preload_Data();


echo "All done<p>";

/* 

  get contributions from github - save to update master_data
  
  operate skeema - create/mod data
  
  bring_uptodate run from old version to new version
  
  run any neededscripts to mod data from old to new

  Need to embed skeema in project


*/

?>
