<?php
  include_once("fest.php");

  A_Check('SysAdmin');
  
  include_once("DanceLib.php");
  include_once("ViewLib.php");

  dostaffhead("Update Cached PA Spec");
  $snum = $_REQUEST['i'];
  $side = Get_Side($snum);
  
  if ($side['StagePA'] != '@@FILE@@') Error_Page('No PA Spec on file');
 
  $files = glob("PAspecs/$snum.*");
  if ($files) {
    $Current = $files[0];
    $Cursfx = pathinfo($Current,PATHINFO_EXTENSION );
    $file = "PAspecs/$snum.$Cursfx";
    if (file_exists($file)) {
      Cache_File($file);
      echo "<h2>File Cached - I hope...</h2>";
      dotail();
    }
  }
  echo "<h2>Failed...</h2>";
  dotail();
?>

