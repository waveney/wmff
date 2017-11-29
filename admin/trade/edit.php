<?php
  include_once("int/fest.php");
  A_Check('Committee','OldAdmin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>WimborneFolk.co.uk | Admin Home</title>
<?php include("files/header.php"); ?>
</head>
<body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=320647184654064&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

    <script>
      $(function() {
        $(".rslides").responsiveSlides();
      });
    </script>

<a href="/" rel="bookmark"><h1>WimborneFolk.co.uk | Admin Home</h1></a>
<div class="navigation"><?php include("files/navigation.php"); ?></div>
<div class="content">

<h2 class="maintitle">Edit ID: <?php echo $_GET['id']; ?></h2>

<?php
$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
$getid = $_GET['id'];
$getcat = $_GET['cat'];
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmfftrade WHERE id=$getid");

  while($row = mysql_fetch_array($result))
  {
  $id = $row['id'];
  $cat = $row['cat'];
  $business = $row['business'];
  $contactname = $row['contactname'];
  $invoiceaddress = $row['invoiceaddress'];
  $email = $row['email'];
  $phone = $row['phone'];
  $products = $row['products'];
  $pitchsize = $row['pitchsize'];
  $health = $row['health'];
  $charity = $row['charity'];
  $pitchfee = $row['pitchfee'];
  $location = $row['location'];
  $accepted = $row['accepted'];
  $bidlevy = $row['bidlevy'];
  $chamber = $row['chamber'];
  $festivaltrader = $row['festivaltrader'];
  $risk = $row['risk'];
  $insurance = $row['insurance'];
  $esig = $row['esig'];
  $status = $row['status'];
  $applydate = $row['applydate'];
  $festivalyear = $row['festivalyear'];

  $business = strtolower($business);
  $contactname = strtolower($contactname);

  date_default_timezone_set('GMT');

  $applydate = date('j M Y', strtotime($applydate));
  $cat = ucwords($cat);
  $status = ucwords($status);
  $business = ucwords($business);
  $contactname = ucwords($contactname);
  $invoiceaddress = ucwords($invoiceaddress);
  $location = ucwords($location);
  $accepted = ucwords($accepted);

  $invoiceaddress = str_replace("<br />","","$invoiceaddress");

  $pitchfee = number_format($pitchfee, 2, '.', '');

  if(!empty($charity))
    {
    $charity = "<br /><strong>Charity: $charity</strong>";
    }

  if(empty($charity))
    {
    $charity = "";
    }

  echo "<form method=\"post\" action=\"/admin/trade/?id=$getid&update=confirm&view=confirm#$id\">
<input type=\"text\" name=\"contactname\" placeholder=\"Name\" value=\"$contactname\" />
<br /><input type=\"text\" name=\"business\" placeholder=\"Business\" value=\"$business\" />
<br /><textarea name=\"invoiceaddress\" rows=\"4\" cols=\"25\" placeholder=\"Invoice Address\">$invoiceaddress</textarea>
<br /><input type=\"text\" name=\"email\" placeholder=\"Email\" value=\"$email\" />
<br /><input type=\"text\" name=\"phone\" placeholder=\"Phone\" value=\"$phone\" />
<h2 class=\"subtitle\">Pitch Info</h2><a name=\"pitch\"></a>
<br /><input type=\"number\" name=\"pitchfee\" placeholder=\"Pitch Fee\" value=\"$pitchfee\" />
<br /><input type=\"text\" name=\"location\" placeholder=\"Location\" value=\"$location\" />
<br /><select name=\"accepted\">
<option value=\"$accepted\">Accepted: $accepted</option>
<option value=\"Yes\">Accepted: Yes</option>
<option value=\"No\">Accepted: No</option>
</select>
<br /><input type=\"text\" name=\"pitchsize\" placeholder=\"Pitch Size\" value=\"$pitchsize\" />
<br /><input type=\"text\" name=\"health\" placeholder=\"Health\" value=\"$health\" />
<br /><input type=\"text\" name=\"products\" placeholder=\"Products\" value=\"$products\" />
<br /><input type=\"text\" name=\"charity\" placeholder=\"Charity\" value=\"$charity\" />
<h2 class=\"subtitle\">Priority</h2><a name=\"priority\"></a>
<br /><select name=\"bidlevy\">
<option value=\"$bidlevy\">BID: $bidlevy</option>
<option value=\"Yes\">BID: Yes</option>
<option value=\"No\">BID: No</option>
</select>
<br /><select name=\"chamber\">
<option value=\"$chamber\">Chamber: $chamber</option>
<option value=\"Yes\">Chamber: Yes</option>
<option value=\"No\">Chamber: No</option>
</select>
<br /><select name=\"festivaltrader\">
<option value=\"$festivaltrader\">Prv Fest Trader: $festivaltrader</option>
<option value=\"Yes\">Prv Fest Trader: Yes</option>
<option value=\"No\">Prv Fest Trader: No</option>
</select>
<br /><input type=\"submit\" value=\"Save Changes\" /></form>
";
  }

mysql_close($con);
?>
</table>
</div>
<?php include("files/footer.php"); ?>
</body>
</html>
