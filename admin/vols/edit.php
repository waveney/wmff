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

$result = mysql_query("SELECT * FROM wmffvols WHERE id=$getid");

  while($row = mysql_fetch_array($result))
  {
  $id = $row['id'];
  $contactname = $row['contactname'];
  $address = $row['address'];
  $email = $row['email'];
  $phone = $row['phone'];
  $performername = $row['performername'];
  $performernumber = $row['performernumber'];
  $performerage = $row['performerage'];
  $performerinst = $row['performerinst'];
  $performergenre = $row['performergenre'];
  $performerdemo = $row['performerdemo'];
  $squarerecords = $row['squarerecords'];
  $whitehart = $row['whitehart'];
  $risingsun = $row['risingsun'];
  $qe = $row['qe'];
  $tech = $row['tech'];
  $esig = $row['esig'];
  $status = $row['status'];
  $applydate = $row['applydate'];
  $festivalyear = $row['festivalyear'];

  date_default_timezone_set('GMT');

  $applydate = date('j M Y', strtotime($applydate));
  $status = ucwords($status);
  $contactname = ucwords($contactname);
  $address = ucwords($address);
  $performername = ucwords($performername);
  $performernumber = ucwords($performernumber);
  $performerage = ucwords($performerage);
  $performerinst = ucwords($performerinst);
  $performergenre = ucwords($performergenre);

  $address = str_replace("<br />","","$address");

  echo "<form method=\"post\" action=\"/admin/live/?id=$getid&update=confirm&view=confirm#$id\">
<input type=\"text\" name=\"contactname\" placeholder=\"Name\" value=\"$contactname\" />
<br /><textarea name=\"address\" rows=\"4\" cols=\"25\" placeholder=\"Address\">$address</textarea>
<br /><input type=\"text\" name=\"email\" placeholder=\"Email\" value=\"$email\" />
<br /><input type=\"text\" name=\"phone\" placeholder=\"Phone\" value=\"$phone\" />
<h2 class=\"subtitle\">Performer Details</h2><a name=\"performer\"></a>
<br /><input type=\"text\" name=\"performername\" placeholder=\"Performer Name\" value=\"$performername\" />
<br /><input type=\"text\" name=\"performernumber\" placeholder=\"Performer Number\" value=\"$performernumber\" />
<br /><input type=\"text\" name=\"performerage\" placeholder=\"Performer Ages\" value=\"$performerage\" />
<br /><input type=\"text\" name=\"performerinst\" placeholder=\"Performer Instruments\" value=\"$performerinst\" />
<br /><input type=\"text\" name=\"performergenre\" placeholder=\"Performer Genre\" value=\"$performergenre\" />
<br /><input type=\"text\" name=\"performerdemo\" placeholder=\"Performer Demo\" value=\"$performerdemo\" />
<h2 class=\"subtitle\">Audition Location</h2><a name=\"audtion\"></a>
<br /><select name=\"squarerecords\">
<option value=\"$squarerecords\">SR: $squarerecords</option>
<option value=\"Yes\">SR: Yes</option>
<option value=\"No\">SR: No</option>
</select>
<br /><select name=\"whitehart\">
<option value=\"$whitehart\">WH: $whitehart</option>
<option value=\"Yes\">WH: Yes</option>
<option value=\"No\">WH: No</option>
</select>
<br /><select name=\"risingsun\">
<option value=\"$risingsun\">RS: $risingsun</option>
<option value=\"Yes\">RS: Yes</option>
<option value=\"No\">RS: No</option>
</select>
<br /><select name=\"qe\">
<option value=\"$qe\">QE: $qe</option>
<option value=\"Yes\">QE: Yes</option>
<option value=\"No\">QE: No</option>
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
