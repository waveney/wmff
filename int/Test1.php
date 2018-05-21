<?php 
// Set fields in data
include_once("fest.php");
include_once("DanceLib.php");

$id = 4;
$Opt = 'Y';

//echo "In Setfields";
//var_dump($_GET);

switch ($Opt) {
case 'I':
  $Sidey = Get_SideYear($id);
  date_default_timezone_set('GMT');
  if (strlen($Sidey['Invited'])) $Sidey['Invited'] .= ", ";
  $Sidey['Invited'] .= date('j/n');
  Put_SideYear($Sidey);
  echo $Sidey['Invited'];
  break;

case 'Y':
  $Sidey = Get_SideYear($id);
  if (!$Sidey) $Sidey = Default_SY($id);
  $Sidey['Invite']=1;
  Put_SideYear($Sidey);
  break;

default:
  $Side = Get_Side($id);
  $Side[$_GET['F']]=$_GET['V'];
  Put_Side($Side);
}
?>
