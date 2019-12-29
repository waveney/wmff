<?php 
// Send Bespoke email bassed on a proforma email initially Dance only
include_once("fest.php");
include_once("DanceLib.php");
include_once("Email.php");
global $FESTSYS,$PLANYEAR;

A_Check("Staff","Dance");

$id = $_REQUEST['id'];
$proforma = $_REQUEST['N'];
$label = (isset($_REQUEST['L'])?$_REQUEST['L']:"");

$Side = Get_Side($id);
$Sidey = Get_SideYear($id);
$subject = $FESTSYS['FestName'] . " $PLANYEAR and " . $Side['SN'];
$Mess = (isset($_POST['Message'])?$_POST['Message']:(Get_Email_Proforma($proforma))['Body']);
$To = $Side['Email'];
if (isset($_REQUEST['E']) && isset($Side[$_REQUEST['E']]) ) {
  $To = $Side[$_REQUEST['E']];
}

if (isset($_POST['CANCEL'])) {  echo "<script>window.close()</script>"; exit; }

if (isset($_POST['SEND'])) {
  $too = [['to',$To,$Side['Contact']],['from','Dance@' . $FESTSYS['HostURL'],'Wimborne Dance'],['replyto','Dance@' . $FESTSYS['HostURL'],'Wimborne Dance']];
  echo Email_Proforma($too,$Mess,$subject,'Dance_Email_Details',[$Side,$Sidey],$logfile='Dance');
  
// Log to "Invited field"
  $prefix = '';

  if (strlen($Sidey['Invited'])) $Sidey['Invited'] .= ", ";
  if ($label) $prefix .= "<span " . Proforma_Background($label) . ">$label:";
  $prefix .= date('j/n/y');
  if ($label) $prefix .= "</span>";
  if (strlen($Sidey['Invited'])) {
    $Sidey['Invited'] = $prefix . ", " . $Sidey['Invited'];
  } else {
    $Sidey['Invited'] = $prefix;  
  }

  Put_SideYear($Sidey);
  echo "<script>window.close()</script>"; 
  exit;
}

dominimalhead("Email for " . $Side['SN'],["css/festconstyle.css"]);

Replace_Help('Dance',1);

echo "<h2>Email for " . $Side['SN'] . " - " . $Side['Contact'] . "</h2>";
if (isset($_POST['PREVIEW'])) {
  echo "<p><h3>Preview...</h2>";
  $MessP = $Mess;
  Parse_Proforma($MessP,$helper='Dance_Email_Details',[$Side,$Sidey],1);
  echo "<div style='background:white;border:2;border-color:blue;padding:20;margin:20;width:90%;max-width:80ch;height:50%;overflow:scroll' >$MessP</div>";
}
echo "<h3>Edit the message below, then click Preview, Send or Cancel</h3>";
echo "Put &lt;p&gt; for paras, &lt;br&gt; for line break, &lt;b&gt;<b>Bold</b>&lt;/b&gt;, &amp;amp; for &amp;, &amp;pound; for &pound; <p> ";

echo "<form method=post>" . fm_hidden('id',$id) . fm_hidden('L',$label);
echo "<div style='width:90%;height:70%'><textarea name=Message id=OrigMsg style='background:white;border:2;border-color:blue;padding:20;margin:20;width:100%;height:100%' onchange=UpdateHtml('OrigMsg','ActMsg'))>" .  htmlspec($Mess) . "</textarea></div><p><br><p>\n";

echo " <input type=submit name=PREVIEW value=Preview> <input type=submit name=SEND value=Send> <input type=submit name=CANCEL value=Cancel>\n";
echo "</form>";

//echo fm_DragonDrop(, $Type,$Cat,$id,&$Data,$Mode=0,$Mess='',$Cond=1,$tddata1='',$tdclass='',$hide=0) {

?>
