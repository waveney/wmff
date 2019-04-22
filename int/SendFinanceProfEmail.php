<?php 
// Send Bespoke email bassed on a proforma email Finance Version
include_once("fest.php");
include_once("InvoiceLib.php");
include_once("Email.php");
global $MASTER_DATA,$PLANYEAR;

A_Check("Staff","Finance");

$id = $_REQUEST['id'];

//$label = (isset($_REQUEST['L'])?$_REQUEST['L']:"");

$inv = Get_Invoice($id);
$subject = $MASTER_DATA['FestName'] . " $PLANYEAR and " . $inv['BZ'];
$Mess = (isset($_POST['Message'])?$_POST['Message']:$inv['CoverNote']);
$inv['CoverNote'] = $Mess;

if (isset($_POST['CANCEL'])) {  echo "<script>window.close()</script>"; exit; }

if (isset($_POST['SEND'])) {
  $too = [['to',$inv['Email'],$Side['Contact']],['from','Finance@' . $MASTER_DATA['HostURL'],'Wimborne Finance'],['replyto','Finance@' . $MASTER_DATA['HostURL'],'Wimborne Finance']];
  echo Email_Proforma($too,$Mess,$subject,'Invoice_Email_Details',$inv,$logfile='Invoices');
  
  $inv['EmailDate'] = time();
  Put_Invoice($inv);
  echo "<script>window.close()</script>"; 
  exit;
}

if (isset($_POST['SAVE'])) {
  Put_Invoice($inv);
}

dominimalhead("Email for " . $inv['BZ'],["files/festconstyle.css"]);
echo "<h2>Email for " . $inv['BZ'] . " - " . $inv['Contact'] . "</h2>";
if (isset($_POST['PREVIEW'])) {
  echo "<p><h3>Preview...</h2>";
  $MessP = $Mess;
  Parse_Proforma($MessP,$helper='Invoice_Email_Details',$inv);
  echo "<div style='background:white;border:2;border-color:blue;padding:20;margin:20;width:90%;height:50%;overflow:scroll' >$MessP</div>";
}
echo "<h3>Edit the message below, then click Preview, Send or Cancel</h3>";
echo "Put &lt;p&gt; for paras, &lt;br&gt; for line break, &lt;b&gt;<b>Bold</b>&lt;/b&gt;, &amp;amp; for &amp;, &amp;pound; for &pound; <p> ";

echo "<form method=post>" . fm_hidden('id',$id) . fm_hidden('ACTION','BESPOKE');// . fm_hidden('L',$label);
echo "<div style='width:90%;height:70%'><textarea name=Message id=OrigMsg style='background:white;border:2;border-color:blue;padding:20;margin:20' onchange=UpdateHtml('OrigMsg','ActMsg'))>" .  htmlspec($Mess) . "</textarea></div><p><br><p>\n";

echo " <input type=submit name=PREVIEW value=Preview> <input type=submit name=SEND value=Send> <input type=submit name=SAVE value=Save> <input type=submit name=CANCEL value=Cancel><p>\n";

echo "</form><p>";

?>
