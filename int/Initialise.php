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
    if (!file_exists("../" . $D[0])) mkdir("../" . $D[0],0777,true);
    if ($D[1] && !file_exists("../" . $D[0] . "/.htaccess")) file_put_contents("../" . $D[0] . "/.htaccess","order deny,allow\ndeny from all");
  }
}

function Create_Databases() {

}


function Preload_Data() {
// Initial MASTER_DATA

// A Year

// A User


}

Create_Directories();
Create_Databases();
Preload_Data();


echo "All done<p>";
?>
