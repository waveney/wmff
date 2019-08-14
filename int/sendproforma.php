<?php 
// Set fields in data
include_once("fest.php");
include_once("DanceLib.php");
include_once("Email.php");
global $FESTSYS,$PLANYEAR;

$id = $_GET['I'];
$proforma = $_GET['N'];

$Side = Get_Side($id);
$Sidey = Get_SideYear($id);
$subject = $FESTSYS['FestName'] . " $PLANYEAR and " . $Side['SN'];
$To = $Side['Email'];
if (isset($_REQUEST['E'])) $To = $Side[$_REQUEST['E']];
//var_dump($_REQUEST);
$too = [['to',$To,$Side['Contact']],['from','Dance@' . $FESTSYS['HostURL'],'Wimborne Dance'],['replyto','Dance@' . $FESTSYS['HostURL'],'Wimborne Dance']];
//$to = $Side['Email']; // Temp value
echo Email_Proforma($too,$proforma,$subject,'Dance_Email_Details',[$Side,$Sidey],$logfile='Dance');

?>
