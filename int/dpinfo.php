<?php // INfomation tpo display things in programming
  include("fest.php");
  include("DanceLib.php");

  global $YEAR;
  $t = $_GET['T'];
  $s = $_GET['S'];

  switch ($t) {
  case 'Side':
    $data = Get_Side($s);
    $datay = Get_SideYear($s,$YEAR);
    $link = "AddDance.php?sidenum=$s";
    break;

  case 'Other':
    $data = Get_Other($s);
    $datay = Get_OtherYear($s,$YEAR);
    $link = "AddOther.php?othernum=$s";
    break;

  case 'Act':
    $data = Get_Act($s);
    $datay = Get_ActYear($s,$YEAR);
    $link = "AddAct.php?actnum=$s";
    break;
  }

  $AllSides = Sides_all();

  echo "<html> <body>";

  echo "<table><tr><td>";
  echo "<h2>" . SName($data) . "</h2>\n";
  echo "<td align=right><a href=$link>Edit</a></table>\n";
  echo "<table>";
  if ($data['Type']) echo "<tr><td>Type:<td>" . $data['Type'];
  if ($data['Description']) echo "<tr valign=top><td>Desc:<td>" . $data['Description'];
  $surfs = 0; 
  foreach ($Surfaces as $s) if ($s && isset($data["Surface_$s"]) && $data["Surface_$s"]) $surfs++;
  if ($surfs) {
    echo "<tr><td>Sfcs:<td class=smalltext>";
    foreach ($Surfaces as $s) if ($s && isset($data["Surface_$s"]) && $data["Surface_$s"]) echo "$s ";
    echo "\n";
  }
  if ($data['StagePA']) echo "<tr><td>PA:<td class=smalltext>" . $data['StagePA'];
  if ($data['Likes']) echo "<tr><td>Rqst:<td class=smalltext>" . $data['Likes'];
  if ($data['Notes']) echo "<tr><td>notes<td class=smalltext>" . $data['notes'];
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
   
  if ($datay['Arrive']) echo "<tr><td>Start<td>" . $datay['Arrive'];
  if ($datay['Depart']) echo "<tr><td>Start<td>" . $datay['Depart'];
  
  if ($datay['YNotes']) echo "<tr><td>notes<td class=smalltext>" . $datay['YNotes'];
  if ($datay['PrivNotes']) echo "<tr><td>notes<td class=smalltext>" . $datay['PrivNotes'];

  if ($data['OverlapD1']) echo "<tr><td>D Olp<td>" . $AllSides[$data['OverlapD1']];
  if ($data['OverlapD2']) echo "<tr><td>D Olp<td>" . $AllSides[$data['OverlapD2']];
  if ($data['OverlapM1']) echo "<tr><td>M Olp<td>" . $AllSides[$data['OverlapM1']];
  if ($data['OverlapM2']) echo "<tr><td>M Olp<td>" . $AllSides[$data['OverlapM2']];

  if ($data['NoiseLevel']) echo "<tr><td>Noise:<td>" . $Noise_Levels[$data['NoiseLevel']];

  echo "</table\n</body></html>";
  

?>
