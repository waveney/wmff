<?php

$getdatetime = gmdate("Y-m-d H:i:s");

$getbusiness = strip_tags($_POST['business']);
$getcontactname = strip_tags($_POST['contactname']);
$getinvoiceaddress = strip_tags($_POST['invoiceaddress']);
$getemail = strip_tags($_POST['email']);
$getphone = strip_tags($_POST['phone']);

$getproducts = strip_tags($_POST['products']);
$getpitchsize = strip_tags($_POST['pitchsize']);
$gethealth = strip_tags($_POST['health']);
$getcharity = strip_tags($_POST['charity']);

$getbidlevy = strip_tags($_POST['bidlevy']);
$getchamber = strip_tags($_POST['chamber']);
$getfestivaltrader = strip_tags($_POST['festivaltrader']);

$getrisk = strip_tags($_POST['risk']);
$getinsurance = strip_tags($_POST['insurance']);

$getesig = strip_tags($_POST['esig']);



$getinvoiceaddress = nl2br($getinvoiceaddress);

$getbusiness = ucwords($getbusiness);
$getcontactname = ucwords($getcontactname);
$gethealth = ucwords($gethealth);
$getesig = ucwords($getesig);

  if(empty($getcontactname) | empty($getemail) | empty($getphone))
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

if ($_FILES['file']['size'] > 5000000)
{
    echo "<h2 class=\"maintitle\">File Too Large</h2><p>Please reduce the size of the file to under 5MB.</p>";
  echo "</div>";
  include("/home4/jmnetwor/public_html/domains/wmfffiles/footer.php");
  echo "</body></html>";
    die('');
}

$blacklist = array(".php", ".phtml", ".php3", ".php4", ".js", ".shtml", ".pl", ".py", ".jpg", ".jpeg", ".png", ".gif");
foreach ($blacklist as $file)
{
if(preg_match("/$file\$/i", $_FILES['file']['name']))
{
    echo "<h2 class=\"maintitle\">Invalid File Type</h2><p>The file you selected doesn't look quite right! Please only use: .pdf, .doc and .docx</p>";
  echo "</div>";
  include("/home4/jmnetwor/public_html/domains/wmfffiles/footer.php");
  echo "</body></html>";
exit;
}
}

  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "<h2 class=\"maintitle\">Failed Upload</h2><p>Return Code: " . $_FILES["file"]["error"] . "</p>";
  echo "</div>";
  include("/home4/jmnetwor/public_html/domains/wmfffiles/footer.php");
  echo "</body></html>";
    die('');
    }
  else
    {

    if (file_exists("/applyfiles/$getcontactname" . $_FILES["file"]["name"]))
      {
      echo "<h2 class=\"maintitle\">Failed Upload</h2><p>" . $_FILES["file"]["name"] . " already exists! 1</p>";
  echo "</div>";
  include("/home4/jmnetwor/public_html/domains/wmfffiles/footer.php");
  echo "</body></html>";
      die('');
      }

    else
      {

      move_uploaded_file($_FILES["file"]["tmp_name"][0],
      "/applyfiles/&getcontactname" . $_FILES["file"]["name"][0]);
      $getrisk = $_FILES["file"]["name"][0];

      move_uploaded_file($_FILES["file"]["tmp_name"][1],
      "/applyfiles/&getcontactname" . $_FILES["file"]["name"][1]);
      $getinsurance = $_FILES["file"]["name"][1];
      }
    }
  }

$getemail = strtolower($getemail);
$getemail = trim($getemail);

$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmfftrade");
$num_rows = mysql_num_rows($result);

while($row = mysql_fetch_array($result))
  {
  $nextnumber = $num_rows+1;
  }

if(empty($nextnumber))
{
$nextnumber = "1";
}

mysql_query("INSERT INTO wmfftrade (id, cat, business, contactname, invoiceaddress, email, phone, products, pitchsize, health, charity, bidlevy, chamber, festivaltrader, risk, insurance, esig, status, applydate, festivalyear)
VALUES ('$nextnumber', 'food', '$getbusiness', '$getcontactname', '$getinvoiceaddress', '$getemail', '$getphone', '$getproducts', '$getpitchsize', '$gethealth', '$getcharity', '$getbidlevy', '$getchamber', '$getfestivaltrader', '$getrisk', '$getinsurance', '$getesig', 'unconfirmed', '$getdatetime', '2017')");

mysql_close($con);

$message = "<html><body>

<table cellspacing=\"2\" cellpadding=\"2\" style=\"font-size:15px; width:100%;\">
<tr><th colspan=\"2\" style=\"background-color:#a30046; color:#FFFFFF; text-align:left; padding:10px 5px; font-size:18px;\">Trade Application: FOOD</th></tr>
<tr><td>Business:</td><td><strong>$getbusiness</strong></td></tr>
<tr><td>Name:</td><td><strong>$getcontactname</strong></td></tr><tr><td valign=\"top\">Invoice Address:</td><td><strong>$getinvoiceaddress</strong></td></tr>
<tr><td>Email:</td><td><strong>$getemail</strong></td></tr>
<tr><td>Phone:</td><td><strong>$getphone</strong></td></tr>
<br />
<tr><th colspan=\"2\" style=\"background-color:#a30046; color:#FFFFFF; text-align:left; padding:10px 5px; font-size:18px;\">About the Business</th></tr>
<tr><td>Products Sold:</td><td><strong>$getproducts</strong></td></tr>
<tr><td>Size of Pitch:</td><td><strong>$getpitchsize</strong></td></tr>
<tr><td>Health Authority:</td><td><strong>$gethealth</strong></td></tr>
<tr><td>Charity Number:</td><td><strong>$getcharity</strong></td></tr>
<tr><td>BID Levy Payer:</td><td><strong>$getbidlevy</strong></td></tr>
<tr><td valign=\"top\">Chamber Member:</td><td><strong>$getchamber</strong></td></tr>
<tr><td valign=\"top\">Previous Trader:</td><td><strong>$getfestivaltrader</strong></td></tr>
<tr><td valign=\"top\">Risk Assessment:</td><td><a href=\"http://wimbornefolk.co.uk/applyfiles/$getrisk\"><strong>$getrisk</strong></a></td></tr>
<tr><td valign=\"top\">Insurance:</td><td><a href=\"http://wimbornefolk.co.uk/applyfiles/$getinsurance\"><strong>$getinsurance</strong></a></td></tr>
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

Your application for trading at Wimborne Minster Folk Festival 2017 has been received. If you are successful, we will be in touch via email mid-January 2017.

Moe Kochar
trade@wimbornefolk.co.uk";

$headers = "From: $getemail\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

  mail( "treasurer@wimbornefolk.co.uk", "Trade Application: FOOD",
  $message, "$headers" );

  mail( "$getemail", "Wimborne Minster Folk Festival | Trade Application Received",
  $autoreply, "From: trade@wimbornefolk.co.uk" );

echo "<h2 class=\"maintitle\">Thank You, $getcontactname!</h2><p>Your Trade Application Form has been sent. If you are successful, we will be in touch with you via email mid-January 2017.</p><p>We have also sent you confirmation by email that your Application Form has been sent (please check your spam inbox), if you have not received the automated response, the email address you provided may have been mis-spelt, so please contact us directly on <a href=\"mailto:trade@wimbornefolk.co.uk\"><strong>trade@wimbornefolk.co.uk</strong></a>.";
?>
