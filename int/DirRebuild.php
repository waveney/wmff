<?php
  include_once("fest.php");
  A_Check('SysAdmin','Docs');

  dostaffhead("Scan/Rebuild Document Storage");
  echo '<script> function uploaddocs(dir) { location.href="Dir.php?"; } </script>';

  include_once("DocLib.php");
 
  global $USERID,$YEAR;
 
/* Go through each entry in dirs - is it there? if not report
   go through each dir in Store - is it dirs? if not report
   
   go through each file in documents - is it there?  is it where it should be?  Is filesize right?  if no to any report
   go through each dir in store - are there any files not in docs? is so report
   
   
   note Acts type 1 and 2 should (now) never be called - ommision of code is deliberate 
   */

// TOOLS
 
function Get_All_Dirs() {
  global $db;
  $qry = "SELECT * FROM Directories";
  $res = $db->query($qry);
  if (!$res) return 0;
  $ans = [];
  while ($rec = $res->fetch_assoc()) $ans[$rec['DirId']] = $rec;
  return $ans;
}

function Get_All_Docs() {
  global $db;
  $qry = "SELECT * FROM Documents";
  $res = $db->query($qry);
  if (!$res) return 0;
  $ans = [];
  while ($rec = $res->fetch_assoc()) $ans[$rec['DocId']] = $rec;
  return $ans;
}

// Primary Methods

function Scan_Documents($Act) {
  global $USERID,$YEAR;
  $Dirs = Get_All_Dirs();
  $RevD = [];
  $FullPs = [];
  
  echo "<h2>Scanning Directories Stage 1</h2>";
  if ($Act == 0) echo "<form method=post action=DirRebuild><div class=tablecont><table>";
  foreach ($Dirs as $D) {
    $d = $D['DirId'];
    $fullpath = "Store" . Dir_FullPname($d);
    $FullPs[$d] = $fullpath;
    $RevD[$fullpath] = $d; // Used later in stage 2-4
    if ($D['State'] == 1) continue;
    if (!is_dir($fullpath)) {
      switch ($Act) {
      case 0:
        echo "<tr>";
        echo "<td><input type=checkbox name=DIR1I$d class=SelectAllAble>";
        echo "<td>Directory $d which should be $fullpath is not a directory<br>";
        break;
            
      case 1:
        $D['State'] = 1; 
        echo "Directory $d which should be " .htmlspec($fullpath) . " has been deleted<br>";
        Put_DirInfo($D);
        break;
        
      case 2: // Not written yet dir to be created
        mkdir($fullpath,0777,1);
        echo "Directory $d which should be  " .htmlspec($fullpath) . " has been created<br>";
        break;
        
      case 3: // Fix selected - Files master
        if (isset($_POST["DIR1I$d"]) && $_POST["DIR1I$d"]) {
          $D['State'] = 1; 
          echo "Directory $d which should be " .htmlspec($fullpath) . " has been deleted<br>";
          Put_DirInfo($D);
          }
        break;
        
      case 4: // Fix selected - Database master - Not written yet dir to be created
        if (isset($_POST["DIR1I$d"]) && $_POST["DIR1I$d"]) {
          mkdir($fullpath,0777,1);
          echo "Directory $d which should be " .htmlspec($fullpath) . " has been created<br>";
        }       
        break;
      }
    }
  }
  
  $RevD['Store']=0;
  $FullPs[0] = 'Store';
//
//  var_dump($RevD);echo "<P>"; var_dump($Dirs);
//

/***********************************************************************************************************************/
  if ($Act==0) echo "<tr><td colspan=100>";
  echo "<h2>Scanning Directories Stage 2</h2>";  // Look through directories in Store do they all exist in the database?

  $DirStack = ['Store'];
  $dirindex = 0;
  $DeleteStack = [];
  $CreateStack = [];
  while ($DirStack) {
    $dir = array_pop($DirStack);
    $handle = opendir($dir);
    while (false !== ($entry = readdir($handle))) {
      if ($entry == '.' || $entry == '..') continue;
      if (is_dir("$dir/$entry")) {
        $dirindex++;
        $path = $DirStack[] = "$dir/$entry";
        if (!isset($RevD[$path])) { // Missing in DB, test if empty
          $findres = [];
          exec("find '$path' -maxdepth 32 -type f -print",$findres);
          $empty = !isset($findres[0]);
          switch ($Act) {
          case 0:
            echo "<tr><td><input type=checkbox name=DIR2I$dirindex class=SelectAllAble>";
            echo "<td>Directory " .htmlspec($path) . " which exists is not in the database";
            echo " - it " . ($empty?"is EMPTY" : "has files") . "<br>"; 
            break;
          case 1: // Create DB for directory
            break;
          case 2: // Delete Directory
            break;
            
          case 3: // Create DB for selected directories 
            if (isset($_POST["DIR2I$dirindex"]) && $_POST["DIR2I$dirindex"]) {
              $Parent = dirname($path);
              $leaf = basename($path);
              $Parentid = $RevD[$Parent];
              $ndir = ['SN'=>addslashes($leaf), 'Who'=>$USERID, 'Created'=>time(), 'Parent'=> $Parentid];
              $nid = Insert_db('Directories',$ndir);
              $RevD[$path] =  $ndir['DirId'] = $nid;
              $Dirs[$nid] = $ndir;
              echo htmlspec($path) . " Has been added to database<br>";
            }          
            break;
            
          case 4: // Delete selected directories
            if (isset($_POST["DIR2I$dirindex"]) && $_POST["DIR2I$dirindex"]) {
              // Need to stack these and do in reverse order
              $DeleteStack[] = $path;
              echo htmlspec($path) . " Has been stacked for deletion<br>";
            }          
            break;
          }
        } else if ($Dirs[$RevD[$path]]['State'] == 1) { // Marked as deleted
          switch ($Act) {
          case 0:
            echo "<tr><td><input type=checkbox name=DIR2AI$dirindex class=SelectAllAble>";
            echo "<td>Directory  " .htmlspec($path) . " which exists is marked as deleted<br>";
            break;
          case 1: // Mark as not deleted Directory
            break;
           
          case 2: // Delete directory
          
            break;
          case 3: // Mark selected as not deleted directories
            $Dirs[$RevD[$path]]['State'] =0;
            Put_DirInfo($Dirs[$RevD[$path]]);
            echo htmlspec($path) . " is back in the usable database<br>";                     
            break;
            
          case 4: // Delete selected directories
            if (isset($_POST["DIR2I$dirindex"]) && $_POST["DIR2I$dirindex"]) {
              // Need to stack these and do in reverse order
              $DeleteStack[] = $path;
              echo htmlspec($path) . " Has been stacked for deletion<br>";
            }          
          
            break;
          }
        }
      }
    }
  }

// var_dump($DeleteStack);
  // Now go through delete and create stack
  foreach (array_reverse($DeleteStack) as $path) {
    $work = rmdir($path);
    if ($work) {
      echo htmlspec($path) . " Has been deleted<br>";
    } else {
      echo htmlspec($path) . " Could NOT be deleted<br>";    
    }
  }


/***********************************************************************************************************************/ 
  if ($Act==0) echo "<tr><td colspan=100>";
  echo "<h2>Scanning Files Stage 3</h2>";  // Look through Files in database do they all exist in Store?
  
  $Docs = Get_All_Docs();
  
  foreach ($Docs as $Doc) {
    $d = $Doc['DocId'];
    $fullpath = $FullPs[$Doc['Dir']] . '/' . stripslashes($Doc['SN']);
    $RevDoc[$fullpath] = $d; // Used later in stage?
    
    if ($Doc['State'] == 1) continue;

    if (!file_exists($fullpath)) {
      switch ($Act) {
      case 0:
        echo "<tr>";
        echo "<td><input type=checkbox name=DIR3I$d class=SelectAllAble>";
        echo "<td>Document $fullpath is not there<br>";
        break;
            
      case 1:
        break;
        
      case 2: 
        break;
        
      case 3: // Fix selected - Files master
        if (isset($_POST["DIR3I$d"]) && $_POST["DIR3I$d"]) {
          $Doc['State'] = 1;
          Put_DocInfo($Doc,1);
          echo "$fullpath has been removed from the database<br>";         
        }
        break;
        
      case 4: // Fix selected - Database master - Not written yet dir to be created
        if (isset($_POST["DIR3I$d"]) && $_POST["DIR3I$d"]) {
          echo "It is not meaningful to make $fullpath<br>";
        }       
        break;
      }
    } else if (!is_file($fullpath)) {
      switch ($Act) {
      case 0:
        echo "<tr>";
        echo "<td><input type=checkbox name=DIR3IA$d class=SelectAllAble>";
        echo "<td>Document $fullpath is a directory!<br>";
        break;
            
      case 1:
        break;
        
      case 2: 
        break;
        
      case 3: // Fix selected - Files master
        if (isset($_POST["DIR3IA$d"]) && $_POST["DIR3IA$d"]) {
          $Doc['State'] = 1;
          Put_DocInfo($Doc,1);
          echo "The database entry for $fullpath has been removed as it is a directory.<br>";
        }
        break;
        
      case 4: // Fix selected - Database master - Not written yet dir to be created
        if (isset($_POST["DIR3IA$d"]) && $_POST["DIR3IA$d"]) {
          echo "It is not meaningful to make $fullpath - there is a directory of that name anyway<br>";
        }       
        break;
      }
    } else if (filesize($fullpath) != $Doc['filesize']) {
      switch ($Act) {
      case 0:
        echo "<tr>";
        echo "<td><input type=checkbox name=DIR3IB$d class=SelectAllAble>";
        echo "<td>Document $fullpath is the wrong size in the database<br>";
        break;
            
      case 1:
        break;
        
      case 2: 
        break;
        
      case 3: // Fix selected - Files master
        if (isset($_POST["DIR3IB$d"]) && $_POST["DIR3IB$d"]) {
          $Doc['filesize'] = filesize($fullpath);
          Put_DocInfo($Doc,1);
          echo "Database updated with correct file size for $fullpath <br>";
        }
        break;
        
      case 4: // Fix selected - Database master - Not meaning full
        if (isset($_POST["DIR3IB$d"]) && $_POST["DIR3IB$d"]) {
        }       
        break;
      }  
    }
  }
  
/***********************************************************************************************************************/

  if ($Act==0) echo "<tr><td colspan=100>";
  echo "<h2>Scanning Files Stage 4</h2>";  // Look through Files in Store do they exist in the database?
  $DirStack = ['Store'];
  $dirindex = 0;
  $fildex = 0;
   while ($DirStack) {
    $dir = array_pop($DirStack);
    $handle = opendir($dir);
    while (false !== ($entry = readdir($handle))) {
      if ($entry == '.' || $entry == '..' || substr($entry,0,1) == '.') continue;
      $path = "$dir/$entry";
      if (is_dir($path)) {
        $dirindex++;
        $DirStack[] = $path;
      } else if (is_file($path)) {
        if (!isset($RevDoc[$path])) { // Not in docs
          $fildex++;
          switch ($Act) {
          case 0:
        echo "<tr>";
        echo "<td><input type=checkbox name=DIR4I$fildex class=SelectAllAble>";
        echo "<td>Document $path is not in the database<br>";
            break;
            
          case 1:
            break;
        
          case 2: 
            break;
        
          case 3: // Fix selected - Files master - add to db
            if (isset($_POST["DIR4I$fildex"]) && $_POST["DIR4I$fildex"]) {
              $Docid = Doc_create($entry,$RevD[$dir],filesize($path)); 
              $RevDoc[$path]= $Docid;
              echo "$path has been added to the Database<br>";
            }
          break;
        
         case 4: // Fix selected - Database master - delete file
           if (isset($_POST["DIR4I$fildex"]) && $_POST["DIR4I$fildex"]) {
             unlink($path);
             echo "Document $path has been deleted<br>";
           }       
           break;
         }  
          
        }
      } else { // not file or directory...
      
      }
    }
    
  } 

  
 
  if ($Act==0) echo "<tr><td colspan=100>";
  echo "<h2>Scanning Finished</h2>";
  
  if ($Act == 0) {
    echo "</table></div>\n";
    echo "<input type=submit name=FIXFI value='Fix Selected - Files/Directories are master'>";
    echo "<input type=submit name=FIXDB value='Fix Selected - Database is the master'>";
  }

} 


  
  // Start Here
  
  if (isset($_POST['FIXFI'])) Scan_Documents(3);
  if (isset($_POST['FIXDB'])) Scan_Documents(4);

  if (isset($_GET['SC'])) Scan_Documents(0);
  if (isset($_GET['FI'])) Scan_Documents(1);
  if (isset($_GET['DB'])) Scan_Documents(2);
    

  echo "<h2><a href=DirRebuild?SC>Re Scan</h2>\n";
  dotail();
  
?>
