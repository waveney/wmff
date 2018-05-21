<?php // INfomation tpo display things in programming
  include_once("fest.php");
  include_once("DanceLib.php");
  include_once("MusicLib.php");

  global $YEAR,$OlapTypes,$OlapDays,$OlapCats,$Share_Spots;
  $t = $_GET['T'];
  $s = $_GET['S'];

  switch ($t) {
  case 'Side':
    $data = Get_Side($s);
    $datay = Get_SideYear($s,$YEAR);
    $link = "AddDance.php?sidenum=$s";
    break;

  case 'Act':
  case 'Other':
    $data = Get_Side($s);
    $datay = Get_ActYear($s,$YEAR);
    $link = "AddMusic.php?othernum=$s";
    break;
  }

  $AllSides = Sides_all();

  echo "<html> <body>";

  echo "<table><tr><td>";
  echo "<h2>" . SName($data) . "</h2>\n";
  echo "<td align=right><a href=$link>Edit</a> <a onclick=infoclose(event)>X</a> &nbsp; </table>\n";
  echo "<table>";
  if ($data['Type']) echo "<tr><td>Type:<td>" . $data['Type'];
  if ($data['Description']) echo "<tr valign=top><td>Desc:<td>" . $data['Description'];
  $surfs = 0; 
  foreach ($Surfaces as $ss) if ($ss && isset($data["Surface_$ss"]) && $data["Surface_$ss"]) $surfs++;
  if ($surfs) {
    echo "<tr><td>Sfcs:<td class=smalltext>";
    foreach ($Surfaces as $ss) if ($ss && isset($data["Surface_$ss"]) && $data["Surface_$ss"]) echo "$ss ";
    echo "\n";
  }
  if ($data['StagePA'] && $data['StagePA'] != 'None') echo "<tr><td>PA:<td class=smalltext>" . $data['StagePA'];
  if ($data['Likes']) echo "<tr><td>Rqst:<td class=smalltext>" . $data['Likes'];
  if ($data['Notes']) echo "<tr><td>notes<td class=smalltext>" . $data['notes'];
  if ($data['Location']) echo "<tr><td>Loc:<td class=smalltext>" . $data['Location'];

  if ($data['Share']) echo "<tr><td>Shared:<td>" . $Share_Spots[$data['Share']];
  if ($datay['Fri']) {
    echo "<tr><td>Fri:<td>Yes ";
    if ($t == 'Side') echo "- Spots " . $datay["FriDance"];
  };
  if ($datay['Sat']) {
    echo "<tr><td>Sat:<td>Yes ";
    if ($t == 'Side') echo "- Spots " . $datay["SatDance"];
    if ($datay['Procession']) echo " Procession";
  };
  if ($datay['Sun']) {
    echo "<tr><td>Sun:<td>Yes ";
    if ($t == 'Side') echo "- Spots " . $datay["SunDance"];
  };
   
  if ($datay['Sat']) {
    if ($datay['SatArrive']) echo "<tr><td>Sat Start<td>" . $datay['SatArrive'];
    if ($datay['SatDepart']) echo "<tr><td>Sat Depart<td>" . $datay['SatDepart'];
  }
  
  if ($datay['Sun']) {
    if ($datay['SunArrive']) echo "<tr><td>Sun Start<td>" . $datay['SunArrive'];
    if ($datay['SunDepart']) echo "<tr><td>Sun Depart<td>" . $datay['SunDepart'];
  }
  
  if ($datay['YNotes']) echo "<tr><td>notes<td class=smalltext>" . $datay['YNotes'];
  if ($datay['PrivNotes']) echo "<tr><td>notes<td class=smalltext>" . $datay['PrivNotes'];

  $Olaps = Get_Overlaps_For($s);
  if ($Olaps) foreach ($Olaps as $oi=>$O) {
    $Other =  ($O['Sid1'] == $s)?'Sid2':'Sid1';
    $OtherCat =  ($O['Sid1'] == $s)?'Cat2':'Cat1';
    
    echo "<tr><td>Olap " . substr($OlapTypes[$O['Type']],0,1) . ($O['Major']?' M ':' m ');
    echo "<td>" . ($O['Days']?$OlapDays[$O['Days']]:'') . " " . $OlapCat[$O[$OtherCat]] . " " . Get_Side_Name($O[$Other]);
  } 

  if ($data['NoiseLevel']) echo "<tr><td>Noise:<td>" . $Noise_Levels[$data['NoiseLevel']];

  echo "</table>\n</body></html>";

?>
