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
case 'J': // Dont Save
  $Sidey = Get_SideYear($id);
  if (!$Sidey) $Sidey = Default_SY($id);
  $prefix = '';
  $label = (isset($_GET['L'])?$_GET['L']:'');
  
  if ($label) $prefix .= "<span " . Proforma_Background($label) . ">$label:";
  $prefix .= date('j/n/y');
  if ($label) $prefix .= "</span>";
  if (strlen($Sidey['Invited'])) {
    $Sidey['Invited'] = $prefix . ", " . $Sidey['Invited'];
  } else {
    $Sidey['Invited'] = $prefix;  
  }
  
  if ($Opt == 'I') {
    Put_SideYear($Sidey);
    if ($label == 'Change' || $label == 'Reinvite') {
      Dance_Record_Change($id, $prefix);
    }
  }
  echo $Sidey['Invited'];
  break;
  
case 'R': // Read
  $Sidey = Get_SideYear($id);
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
