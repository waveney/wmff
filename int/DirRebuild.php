<?php
  include_once("fest.php");
  A_Check('SysAdmin','Docs');

  dostaffhead("Scan/Rebuild Document Storage");
  echo '<script> function uploaddocs(dir) { location.href="Dir.php?"; } </script>';

  include_once("DocLib.php");
 
/* Go through each entry in dirs - is it there? if not report
   go through each dir in Store - is it dirs? if not report
   
   go through each file in documents - is it there?  is it where it should be?  Is filesize right?  if no to any report
   go through each dir in store - are there any files not in docs? is so report
   
   */

// TOOLS
 
function Get_All_Dirs() {
  global $db;
  $qry = "SELECT * FROM Directories WHERE State=0";
  $res = $db->query($qry);
  if (!$res) return 0;
  $ans = [];
  while ($rec = $res->fetch_assoc()) $ans[] = $rec;
  return $ans;
}

// Primary Methods

function Scan_Documents($Act) {
  $Dirs = Get_All_Dirs();
  
  echo "<h2>Scanning Directories Stage 1</h2>";
  echo "<form method=post><table>";
  foreach ($Dirs as $D) {
    $d = $D['DirId'];
    $fullpath = "Store" . Dir_FullPname($d);
    if (!is_dir($fullpath)) {
      switch ($Act) {
      case 0:
        echo "<tr><td>Directory $d which should be $fullpath is not a directory<br>";
        break;
            
      case 1:
        $D['State'] = 1; 
        echo "Directory $d which should be $fullpath has been deleted<br>";
        Put_DirInfo($D);
        break;
        
      case 2: // Not written yet dir to be created
      }
      
    }
  }
  
  echo "<h2>Scanning Directories Stage 2</h2>";  
 
} 


  
  // Start Here
  
  if (isset($_GET['SC'])) Scan_Documents(0);
  if (isset($_GET['FI'])) Scan_Documents(1);
  if (isset($_GET['DB'])) echo "Not Written yet";
  
  dotail();
  
?>
