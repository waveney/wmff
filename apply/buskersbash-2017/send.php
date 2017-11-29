<?php

$getdatetime = gmdate("Y-m-d H:i:s");

$getcontactname = strip_tags($_POST['contactname']);
$getaddress = strip_tags($_POST['address']);
$getemail = strip_tags($_POST['email']);
$getphone = strip_tags($_POST['phone']);

$getperformername = strip_tags($_POST['performername']);
$getperformernumber = strip_tags($_POST['performernumber']);
$getperformerage = strip_tags($_POST['performerage']);
$getperformerinst = strip_tags($_POST['performerinst']);
$getperformergenre = strip_tags($_POST['performergenre']);
$getperformerdemo = strip_tags($_POST['performerdemo']);

$getsquarerecords = strip_tags($_POST['squarerecords']);
$getwhitehart = strip_tags($_POST['whitehart']);
$getrisingsun = strip_tags($_POST['risingsun']);
$getqe = strip_tags($_POST['qe']);

$gettech = strip_tags($_POST['tech']);

$getesig = strip_tags($_POST['esig']);


$getaddress = nl2br($getaddress);

$getcontactname = ucwords($getcontactname);
$getperformername = ucwords($getperformername);
$getperformerinst = ucwords($getperformerinst);
$getperformergenre = ucwords($getperformergenre);
$getesig = ucwords($getesig);

  if(empty($getcontactname) | empty($getaddress) | empty($getemail) | empty($getphone) | empty($getperformername) | empty($getperformernumber) | empty($getperformerage) | empty($getperformerinst) | empty($getperformergenre))
  {
  echo"<h2 class=\"maintitle\">Missing Information</h2><p>Please go back and complete all of the fields that are marked with an asterisk (*).</p>";
  echo "</div>";
  include("/home4/jmnetwor/public_html/domains/wmfffiles/footer.php");
  echo "</body></html>";
  die('');
  }

  if(empty($getesig))
  {
  echo "<h2 class=\"maintitle\">E-Signature Required</h2><p>Your electronic signature (print name) is required so we know that you agree to our Terms & Conditions and that you have checked the details you have provided.</p>";
  echo "</div>";
  include("/home4/jmnetwor/public_html/domains/wmfffiles/footer.php");
  echo "</body></html>";
  die('');
  }

if(empty($getsquarerecords))
  {
  $getsquarerecords = "No";
  }

if(empty($getwhitehart))
  {
  $getwhitehart = "No";
  }

if(empty($getrisingsun))
  {
  $getrisingsun = "No";
  }

if(empty($getqe))
  {
  $getqe = "No";
  }

if(empty($gettech))
  {
  $gettech = "No";
  }

$getemail = strtolower($getemail);
$getemail = trim($getemail);

$getcontactname = addslashes($getcontactname);
$getaddress = addslashes($getaddress);
$getemail = addslashes($getemail);
$getphone = addslashes($getphone);
$getperformername = addslashes($getperformername);
$getperformernumber = addslashes($getperformernumber);
$getperformerage = addslashes($getperformerage);
$getperformerinst = addslashes($getperformerinst);
$getperformergenre = addslashes($getperformergenre);
$getperformerdemo = addslashes($getperformerdemo);
$getesig = addslashes($getesig);

$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmffbuskersbash");
$num_rows = mysql_num_rows($result);

while($row = mysql_fetch_array($result))
  {
  $nextnumber = $num_rows+1;
  }

if(empty($nextnumber))
{
$nextnumber = "1";
}

mysql_query("INSERT INTO wmffbuskersbash (id, contactname, address, email, phone, performername, performernumber, performerage, performerinst, performergenre, performerdemo, squarerecords, whitehart, risingsun, qe, tech, esig, status, applydate, festivalyear)
VALUES ('$nextnumber', '$getcontactname', '$getaddress', '$getemail', '$getphone', '$getperformername', '$getperformernumber', '$getperformerage', '$getperformerinst', '$getperformergenre', '', '', '', '', '', '$gettech', '$getesig', 'unconfirmed', '$getdatetime', '2017')");

mysql_close($con);

$getcontactname = stripslashes($getcontactname);
$getaddress = stripslashes($getaddress);
$getemail = stripslashes($getemail);
$getphone = stripslashes($getphone);
$getperformername = stripslashes($getperformername);
$getperformernumber = stripslashes($getperformernumber);
$getperformerage = stripslashes($getperformerage);
$getperformerinst = stripslashes($getperformerinst);
$getperformergenre = stripslashes($getperformergenre);
$getperformerdemo = stripslashes($getperformerdemo);
$getsquarerecords = stripslashes($getsquarerecords);
$getwhitehart = stripslashes($getwhitehart);
$getrisingsun = stripslashes($getrisingsun);
$getqe = stripslashes($getqe);
$gettech = stripslashes($gettech);
$getesig = stripslashes($getesig);

$message = "<html><body>

<table cellspacing=\"2\" cellpadding=\"2\" style=\"font-size:15px;\">
<tr><th colspan=\"2\" style=\"background-color:#a30046; color:#FFFFFF; text-align:left; padding:10px 5px; font-size:18px;\">Wimborne Buskers Bash Application Form</th></tr>
<tr><td>Name:</td><td><strong>$getcontactname</strong></td></tr><tr><td valign=\"top\">Address:</td><td><strong>$getaddress</strong></td></tr>
<tr><td>Email:</td><td><strong>$getemail</strong></td></tr>
<tr><td>Phone:</td><td><strong>$getphone</strong></td></tr>
<br />
<tr><th colspan=\"2\" style=\"background-color:#a30046; color:#FFFFFF; text-align:left; padding:10px 5px; font-size:18px;\">Performance Details</th></tr>
<tr><td>Act Name:</td><td><strong>$getperformername</strong></td></tr>
<tr><td>Number of Performers:</td><td><strong>$getperformernumber</strong></td></tr>
<tr><td>Ages:</td><td><strong>$getperformerage</strong></td></tr>
<tr><td>Instruments:</td><td><strong>$getperformerinst</strong></td></tr>
<tr><td>Genre:</td><td><strong>$getperformergenre</strong></td></tr>
<br />
<tr><td>Tech Question:</td><td><strong>$gettech</strong></td></tr>
<br />
<tr><td>E-Signature:</td><td><strong>$getesig</strong></td></tr>
</table>
<p>--
<br />$getcontactname
<br />$getemail
<br />$getphone
<br />
<br />Sent: $getdatetime</p>
</body></html>";

$autoreply = "Dear $getcontactname,

Your application for Wimborne Buskers Bash 2017 has been received. We will be in touch soon.

Join us at the Finals from 6pm on 13 May on Wimborne Square!

Emily Coley
emily@wimbornefolk.co.uk";

$headers = "From: $getemail\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

  mail( "james@wimbornefolk.co.uk, emily@wimbornefolk.co.uk", "Wimborne Buskers Bash Application Form",
  $message, "$headers" );

  mail( "$getemail", "Wimborne Minster Folk Festival | Auto Response",
  $autoreply, "From: Emily@wimbornefolk.co.uk" );

echo "<h2 class=\"maintitle\">Thank You, $getcontactname!</h2><p>Your Wimborne Buskers Bash Application Form has been sent. We will be in touch with you soon.</p><p>We have also sent you confirmation by email that your Application Form has been sent (please check your spam inbox), if you have not received the automated response, the email address you provided may have been mis-spelt, so please contact us directly on <a href=\"mailto:emily@wimbornefolk.co.uk\"><strong>emily@wimbornefolk.co.uk</strong></a>.</p>";
?>
