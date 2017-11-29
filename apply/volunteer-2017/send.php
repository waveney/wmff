<?php

$getdatetime = gmdate("Y-m-d H:i:s");

$getcontactname = strip_tags($_POST['contactname']);
$getaddress = strip_tags($_POST['address']);
$getemail = strip_tags($_POST['email']);
$getphone = strip_tags($_POST['phone']);
$getdob = strip_tags($_POST['dob']);
$getworkteam = strip_tags($_POST['workteam']);

$getsteward = strip_tags($_POST['steward']);
$gettech = strip_tags($_POST['tech']);
$getartistic = strip_tags($_POST['artistic']);
$getmedia = strip_tags($_POST['media']);
$getduties = strip_tags($_POST['duties']);
$getunable = strip_tags($_POST['unable']);

$getthu = strip_tags($_POST['thu']);
$getfri = strip_tags($_POST['fri']);
$getsat = strip_tags($_POST['sat']);
$getsun = strip_tags($_POST['sun']);
$getmon = strip_tags($_POST['mon']);

$getdbs = strip_tags($_POST['dbs']);
$getdbsdetails = strip_tags($_POST['dbsdetails']);

$getemergencycontact = strip_tags($_POST['emergencycontact']);
$getrelationship = strip_tags($_POST['relationship']);
$getemergencyphone = strip_tags($_POST['emergencyphone']);

$getesig = strip_tags($_POST['esig']);


$getaddress = nl2br($getaddress);

$getcontactname = ucwords($getcontactname);
$getdob = ucwords($getdob);
$getworkteam = ucwords($getworkteam);
$getemergencycontact = ucwords($getemergencycontact);
$getesig = ucwords($getesig);

  if(empty($getcontactname) | empty($getaddress) | empty($getemail) | empty($getphone) | empty($getdob) | empty($getemergencycontact) | empty($getrelationship) | empty($getemergencyphone))
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

if(empty($getsteward))
  {
  $getsteward = "No";
  }

if(empty($gettech))
  {
  $gettech = "No";
  }

if(empty($getartistic))
  {
  $getartistic = "No";
  }

if(empty($getmedia))
  {
  $getmedia = "No";
  }

$getemail = strtolower($getemail);
$getemail = trim($getemail);

$getcontactname = addslashes($getcontactname);
$getaddress = addslashes($getaddress);
$getemail = addslashes($getemail);
$getphone = addslashes($getphone);
$getdob = addslashes($getdob);
$getworkteam = addslashes($getworkteam);
$getduties = addslashes($getduties);
$getunable = addslashes($getunable);
$getthu = addslashes($getthu);
$getfri = addslashes($getfri);
$getsat = addslashes($getsat);
$getsun = addslashes($getsun);
$getmon = addslashes($getmon);
$getdbsdetails = addslashes($getdbsdetails);
$getemergencycontact = addslashes($getemergencycontact);
$getemergencyphone = addslashes($getemergencyphone);
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

$result = mysql_query("SELECT * FROM wmffvols");
$num_rows = mysql_num_rows($result);

while($row = mysql_fetch_array($result))
  {
  $nextnumber = $num_rows+1;
  }

if(empty($nextnumber))
{
$nextnumber = "1";
}

mysql_query("INSERT INTO wmffvols (id, contactname, address, email, phone, dob, workteam, steward, technical, artistic, media, duties, unable, thu, fri, sat, sun, mon, dbs, dbsdetails, emergencycontact, relationship, emergencyphone, esig, status, applydate, festivalyear, tickettype, parking, camping)
VALUES ('$nextnumber', '$getcontactname', '$getaddress', '$getemail', '$getphone', '$getdob', '$getworkteam', '$getsteward', '$gettech', '$getartistic', '$getmedia', '$getduties', '$getunable', '$getthu', '$getfri', '$getsat', '$getsun', '$getmon', '$getdbs', '$getdbsdetails', '$getemergencycontact', '$getrelationship', '$getemergencyphone', '$getesig', 'unconfirmed', '$getdatetime', '2017', 'Weekend', 'Yes', 'No')");

mysql_close($con);

$getcontactname = stripslashes($getcontactname);
$getaddress = stripslashes($getaddress);
$getemail = stripslashes($getemail);
$getphone = stripslashes($getphone);
$getdob = stripslashes($getdob);
$getworkteam = stripslashes($getworkteam);
$getduties = stripslashes($getduties);
$getunable = stripslashes($getunable);
$getthu = stripslashes($getthu);
$getfri = stripslashes($getfri);
$getsat = stripslashes($getsat);
$getsun = stripslashes($getsun);
$getmon = stripslashes($getmon);
$getdbsdetails = stripslashes($getdbsdetails);
$getemergencycontact = stripslashes($getemergencycontact);
$getemergencyphone = stripslashes($getemergencyphone);
$getesig = stripslashes($getesig);

$message = "<html><body>

<table cellspacing=\"2\" cellpadding=\"2\" style=\"font-size:15px;\">
<tr><th colspan=\"2\" style=\"background-color:#a30046; color:#FFFFFF; text-align:left; padding:10px 5px; font-size:18px;\">Volunteer Application Form</th></tr>
<tr><td>Name:</td><td><strong>$getcontactname</strong></td></tr><tr><td valign=\"top\">Address:</td><td><strong>$getaddress</strong></td></tr>
<tr><td>Email:</td><td><strong>$getemail</strong></td></tr>
<tr><td>Phone:</td><td><strong>$getphone</strong></td></tr>
<tr><td>Date of Birth:</td><td><strong>$getdob</strong></td></tr>
<tr><td>I'd like to work with:</td><td><strong>$getworkteam</strong></td></tr>
<br />
<tr><th colspan=\"2\" style=\"background-color:#a30046; color:#FFFFFF; text-align:left; padding:10px 5px; font-size:18px;\">Team Choices</th></tr>
<tr><td>Steward:</td><td><strong>$getsteward</strong></td></tr>
<tr><td>Technical:</td><td><strong>$gettech</strong></td></tr>
<tr><td>Artistic:</td><td><strong>$getartistic</strong></td></tr>
<tr><td>Media:</td><td><strong>$getmedia</strong></td></tr>
<tr><td>Preferred Duties:</td><td><strong>$getduties</strong></td></tr>
<tr><td>Unable to do:</td><td><strong>$getunable</strong></td></tr>
<br />
<tr><th colspan=\"2\" style=\"background-color:#a30046; color:#FFFFFF; text-align:left; padding:10px 5px; font-size:18px;\">Availability</th></tr>
<tr><td colspan=\"2\">Times I am <strong>NOT</strong> available.</tr></tr>
<tr><td>Thursday:</td><td><strong>$getthu</strong></td></tr>
<tr><td>Friday:</td><td><strong>$getfri</strong></td></tr>
<tr><td>Saturday:</td><td><strong>$getsat</strong></td></tr>
<tr><td>Sunday:</td><td><strong>$getsun</strong></td></tr>
<tr><td>Monday:</td><td><strong>$getmon</strong></td></tr>
<br />
<tr><th colspan=\"2\" style=\"background-color:#a30046; color:#FFFFFF; text-align:left; padding:10px 5px; font-size:18px;\">Legal Stuff</th></tr>
<tr><td>DBS:</td><td><strong>$getdbs</strong></td></tr>
<tr><td>DBS Details:</td><td><strong>$getdbsdetails</strong></td></tr>
<tr><td>Emergency Contact:</td><td><strong>$getemergencycontact</strong></td></tr>
<tr><td>Relationship:</td><td><strong>$getrelationship</strong></td></tr>
<tr><td>Phone:</td><td><strong>$getemergencyphone</strong></td></tr>
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

Your application for volunteering at Wimborne Minster Folk Festival 2017 has been received. We will be in touch soon.

Paul Gunovsky
paul@wimbornefolk.co.uk";

$headers = "From: $getemail\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

  mail( "james@wimbornefolk.co.uk, paul@wimbornefolk.co.uk, paul.gunovsky@gmail.com", "VOLUNTEER APPLICATION FORM",
  $message, "$headers" );

  mail( "$getemail", "Wimborne Minster Folk Festival | Auto Response",
  $autoreply, "From: Paul@wimbornefolk.co.uk" );

echo "<h2 class=\"maintitle\">Thank You, $getcontactname!</h2><p>Your Volunteer Application Form has been sent. We will be in touch with you soon.</p><p>We have also sent you confirmation by email that your Application Form has been sent (please check your spam inbox), if you have not received the automated response, the email address you provided may have been mis-spelt, so please contact us directly on <a href=\"mailto:paul@wimbornefolk.co.uk\"><strong>paul@wimbornefolk.co.uk</strong></a>.</p>";
?>
