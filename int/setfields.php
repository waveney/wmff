<?php 
// Set fields in data
include_once("fest.php");
include_once("DanceLib.php");

$id = $_GET['I'];
$Opt = $_GET['O'];

//echo "In Setfields";
//var_dump($_GET);

switch ($Opt) {
case 'I':
  $Sidey = Get_SideYear($id);
  if (!$Sidey) $Sidey = Default_SY($id);
  date_default_timezone_set('GMT');
  if (strlen($Sidey['Invited'])) $Sidey['Invited'] .= ", ";
  if (isset($_GET['L'])) $Sidey['Invited'] .= $_GET['L'] . ":";
  $Sidey['Invited'] .= date('j/n/y');
  Put_SideYear($Sidey);
  echo $Sidey['Invited'];
  break;

case 'Y':
  $Sidey = Get_SideYear($id);
  if (!$Sidey) $Sidey = Default_SY($id);
  $Sidey[$_GET['F']]=$_GET['V'];
  Put_SideYear($Sidey);
  break;
  
case 'TP':
  include_once("TLLib.php");
  $tl = Get_TLent($id);
  $tl['Progress'] = $_GET['V'];
  if ($tl['Progress'] == 100) {
    $tl['Completed'] = time();
  };
  Put_TLent($tl);
  break;
  
default:
  $Side = Get_Side($id);
  $Side[$_GET['F']]=$_GET['V'];
  Put_Side($Side);
}
?>
