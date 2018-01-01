<?php
// Various things to do with maps

// Call this after any mappoint or venue update
function Update_MapPoints() {
  global $db;

  $xml = "<markers>\n";
  $res = $db->query("SELECT * FROM Venues WHERE Status=0 AND Lat!='' ");
  if ($res) while($ven = $res->fetch_assoc()) {
    $xml .= '<marker id="' . $ven['VenueId'] . '" name="' . $ven['Name'] . '" lat="' . $ven['Lat'] . '" long="' . $ven['Lng'] . 
	'" imp="' . $ven['MapImp'] . '" icon="' . $ven['IconType'] . '"/>' . "\n";
  }

  $res = $db->query("SELECT * FROM MapPoints WHERE InUse=0");
  if ($res) while($mp = $res->fetch_assoc()) {
    $xml .= '<marker id="' . (1000000+$mp['id']) . '" name="' . $mp['Name'] . '" lat="' . $mp['Lat'] . '" long="' . $mp['Lng'] . 
	'" imp="' . $mp['MapImp'] . '" icon="' . $mp['Type'] . '"/>' . "\n";
  }

  $xml .= "</markers>\n";
  return file_put_contents("../files/mappoints.xml",$xml);
}

function Get_Map_Point_Types() {
  global $db;
  $res = $db->query("SELECT * FROM MapPointTypes ORDER BY id");
  if ($res) while ($mpt = $res->fetch_assoc()) $full[] = $mpt;
  return $full;
}

function Get_Map_Point_Type($mid) {
  global $db;
  $res = $db->query("SELECT * FROM MapPointTypes WHERE id=$mid");
  if ($res) $ans = $res->fetch_assoc();
  return $ans;
}

function Put_Map_Point_Type(&$now) {
  $Cur = Get_Map_Point_Type($now['id']);
  Update_db('MapPointTypes',$Cur,$now);
}

function Get_Map_Points() {
  global $db;
  $res = $db->query("SELECT * FROM MapPoints ORDER BY id");
  if ($res) while ($mpt = $res->fetch_assoc()) $full[] = $mpt;
  return $full;
}

function Get_Map_Point($mid) {
  global $db;
  $res = $db->query("SELECT * FROM MapPoints WHERE id=$mid");
  if ($res) $ans = $res->fetch_assoc();
  return $ans;
}

function Put_Map_Point(&$now) {
  $Cur = Get_Map_Point($now['id']);
  Update_db('MapPoints',$Cur,$now);
}

?>
