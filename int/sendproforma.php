<?php 
// Set fields in data
include_once("fest.php");
include_once("DanceLib.php");
include_once("Email.php");
global $MASTER_DATA,$PLANYEAR;

$id = $_GET['I'];
$proforma = $_GET['N'];

//echo "In Setfields";
//var_dump($_GET);

$Side = Get_Side($id);
$Sidey = Get_SideYear($id);
$subject = $MASTER_DATA['FestName'] . " $PLANYEAR and " . $Side['SN'];
$to = $Side['Email']; // Temp value
echo Email_Proforma($to,$proforma,$subject,'Dance_Email_Details',[$Side,$Sidey],$logfile='Dance');

?>
