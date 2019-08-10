<?php
  include_once("fest.php");

  A_Check('SysAdmin');
  
  dostaffhead("Convert Old Archive Images");

/* Go through each file in ArchiveImages recurse into directories
   find files if format is dddd.sfx rename as ddddH0.sfx
   if dddd.sfx.ii rename as ddddHii.sfx
   if ddddIn.sfx rename as ddddIn.sfx
*/
 
 
function Convert_Dir($name) {
  $D = opendir($name);
  if (!$D) return;
  while (($file = readdir($D)) !== false) {
    if ($file[0] == '.') continue;
    if (is_dir("$name/$file")) {
      Convert_Dir("$name/$file");
    } else {
      if (preg_match("/^(\d*)(I(\d*))?\.([^.]*)(\.(\d*))?$/",$file,$match)) {
        $Base = $match[1];
        $Img = ($match[3]?$match[2]:"");
        $sfx = $match[4];
        $Issue = (isset($match[5])? $match[5]: ".0");
        
        echo "Found $name/$file is: Base:$Base Img:$Img Sfx:$sfx Iss:$Issue ";
        $NewName = "$Base$Img$Issue.$sfx";
        echo "New name = $NewName<br>";
        if (!rename("$name/$file","$name/$NewName")) {
          echo "<span class=Err>Failed to rename $file as $NewName</span>";
        }
      } elseif (preg_match("/^(\d*)(H\.?(\d)*)\.([^.]*)$/",$file,$match)) {
        $Base = $match[1];
        $Img = "";
        $sfx = $match[4];
        $Issue = (isset($match[3])? $match[3]: ".0");
        if ($Issue[0] != '.') $Issue = ".$Issue";
        
        echo "Found $name/$file is: Base:$Base Img:$Img Sfx:$sfx Iss:$Issue ";
        $NewName = "$Base$Img$Issue.$sfx";
        echo "New name = $NewName<br>";
        if (!rename("$name/$file","$name/$NewName")) {
          echo "<span class=Err>Failed to rename $file as $NewFile</span>";
        }
      } else {
        echo "Match failed on $file<p>";
      }
    }
  }
  closedir($D);
}

  Convert_Dir("ArchiveImages");

  dotail();

