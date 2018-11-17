<?php

// Initialise festival system
// create databases
// preload databases as needed - Master_Data and FestUsers
// create and populate directories
// No problem to run again and again

function Create_Directories() {  // Makes all needed directories and adds .htaccess where appropriate
  $Dirs = [['int/ArchiveImages',1],
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

}

Create_Directories();
Create_Databases();
Preload_Data();


echo "All done<p>";
?>
