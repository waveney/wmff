<?php
$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect("$server","$uname","$pword");
$getusername = strip_tags($_POST['username']);
$getcookies = strip_tags($_POST['cookies']);
$confirm = "confirm";

if($getcookies != $confirm)
  {
  include ("/home/jmnetwor/public_html/files/errors/top.php");
  echo "<h2 class=\"maintitle\">Cookies</h2>
<p>We are required by EU regulations (as of 26th May 2012) to obtain your consent before cookies are gathered or sent, to or from your computer. To consent to cookies, please go back a page and select the box next to \"I allow Cookies.\" before logging in. Read our <a href=\"/terms.php#privacy\" rel=\"bookmark\" target=\"_blank\">Privacy Policy</a> for more information on what we do with cookies.</p>
<p>If you do not give consent for us to use cookies, an account may not be created or used on the website. <a href=\"/?logout=confirm\" rel=\"bookmark\">Click Here</a> to clear your cookies from this website.</p>";
  include ("/home/jmnetwor/public_html/files/errors/bottom.php");
  die('');
  }

$lowerusername = strtolower($getusername);
$trimusername = trim($lowerusername);
$aposusername = str_replace("\'","","$trimusername");
$bsusername = str_replace("\\","","$aposusername");
$spaceusername = str_replace(" ","","$bsusername");

$upperusername = ucwords($spaceusername);

$getpassword = strip_tags($_POST['password']);
$getlength = $_POST['length'];
$midnight = "MIDNIGHT";
$getip=$_SERVER['REMOTE_ADDR'];

if($getlength == $midnight)
{
$tonight = strtotime('tomorrow midnight') - time();
$expire = time() + $tonight;
}

if($getlength != $midnight)
{
$expire = time() + $getlength;
}

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
$loadpage = "<html>
<head>
<script type=\"text/javascript\">
<!--
function delayer(){
    window.location = \"/\"
}
//-->
</script>
</head>
<body onLoad=\"setTimeout('delayer()', 0000)\">
</body>
</html>
";

mysql_select_db("jmnetwor_jmn", $con);

$result = mysql_query("SELECT * FROM userdata
WHERE username ='$spaceusername'");

while($row = mysql_fetch_array($result))
  {
  $getactivated = $row['activated'];
  $false = "false";
    if($getactivated == $false)
    {
  include ("/home/jmnetwor/public_html/files/errors/top.php");
  echo "<h2 class=\"maintitle\">Unactivated Account</h2>
<p>Please activate your account before logging in. We have sent you an activation code in an email to the email address you specified when registering. Please check your spam or trash folders! If you have not received an email (sent from accounts@jmnetwork.co.uk) please <a href=\"/contact.php\" rel=\"bookmark\">Contact Us</a> with your username and valid email address.</p>";
  include ("/home/jmnetwor/public_html/files/errors/bottom.php");
  die('');
    }
  }

mysql_select_db("jmnetwor_jmn", $con);

$result = mysql_query("SELECT * FROM userdata
WHERE username ='$spaceusername'");

if (!$result)
  {
  include ("/home/jmnetwor/public_html/files/errors/top.php");
  echo "<h2 class=\"maintitle\">Invalid Username or Password</h2>
<p>Your username or password did not match our records, please go back and try again or <a href=\"/?logout=confirm\" rel=\"bookmark\">Register</a> for free.</p>";
  include ("/home/jmnetwor/public_html/files/errors/bottom.php");
  die('');
  }

while($row = mysql_fetch_array($result))
  {
$getsalt = $row['salts'];
$gethash = $row['hashes'];
  }

$pwh = sha1($getpassword.$getsalt);

if ($pwh !== $gethash)
  {
  include ("/home/jmnetwor/public_html/files/errors/top.php");
  echo "<h2 class=\"maintitle\">Invalid Username or Password</h2>
<p>Your username or password did not match our records, please go back and try again or <a href=\"/?logout=confirm\" rel=\"bookmark\">Register</a> for free.</p>";
  include ("/home/jmnetwor/public_html/files/errors/bottom.php");
  die('');
  }

if($pwh === $gethash)
{
  setcookie("jmnexpire","$getlength", $expire);
  setcookie("jmnuser","$spaceusername", $expire);
  setcookie("jmncookies","confirm", time()+2678400);
  mysql_query("UPDATE userdata SET ip='$getip' WHERE username='$spaceusername'");
  echo "$loadpage";
}

mysql_close($con);
?> 