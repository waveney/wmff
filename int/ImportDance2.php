<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("Appalchian Import");
  include("DanceLib.php");

  global $YEAR;
  
/* Read Names all current sides
   REad entire File
   Split <p> each chunk then a side
   Parse each into bits - Name, Location, Web
   if (Name known) { Update Location}
   else { New entry }
*/

  $SideNames = Sides_Name_List();
  foreach($SideNames as $idx=>$nam) $SideIdxs[strtolower($nam)] = $idx;
//  $SideIdxs = array_flip($SideNames);

  $F = fopen("AppFile","r");

  $Loc = "";
  $Name = "";
  $Web = "";
  $Comments = "";
  
  while( $line = fgets($F)) {
    if (preg_match('/<p>(.*)/',$line,$mtch)) {
      if ($Name) {
	if (isset($SideIdxs[strtolower($Name)])) {
	  $s = Get_Side($SideIdxs[strtolower($Name)]);
	  $s['Location'] = $Loc;
	  Put_Side($s);
	  echo "Added location to $Name<br>\n";
	} else {
	  $s = array('Name'=>$Name,'Location'=>$Loc,'Type'=>'Appalachian','IsASide'=>1,'Website'=>$Web,'Notes'=>$Comments);
	  Insert_db('Sides',$s);
	  echo "$Name is a new side<br>\n";
	}
      }
      $Loc = $Name = $Web = $Comments = "";
      $line = $mtch[1];
    }
    $newline = trim(strip_tags($line));
    preg_match('/(.+) : (.+)/',$newline,$mtch);
    switch ($mtch[1]) {
    case 'County' : $Loc = $mtch[2]; break;
    case 'Team Name' : $Name = $mtch[2]; break;
    case 'Web' : $Web = $mtch[2]; break;
    case 'Location' : $Loc .= ", " . $mtch[2]; break;
    case 'Comments' : $Comments = $mtch[2]; break;
    }
  } 

  dotail();
?>

