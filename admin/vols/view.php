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

<h2 class="maintitle">Volunteer Applications</h2>

<table cellspacing="5" cellpadding="5" style="background-color:#000000; border-color:#000000;">
<tr>
<td style="width:25px;font-weight:bold;text-align:center;">No.</td>
<td style="width:100px;font-weight:bold;text-align:center;">Applied</td>
<td style="width:50px;font-weight:bold;text-align:center;">Status</td>
<td style="width:250px;font-weight:bold;">Contact</td>
<td style="width:250px;font-weight:bold;">Info</td>
<td style="width:250px;font-weight:bold;">Details</td>
<td style="width:250px;font-weight:bold;">Emergency</td>
</tr>
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

$result = mysql_query("SELECT * FROM wmffvols WHERE festivalyear='2017' ORDER BY applydate ASC");

  while($row = mysql_fetch_array($result))
  {
  $id = $row['id'];
  $contactname = $row['contactname'];
  $address = $row['address'];
  $email = $row['email'];
  $phone = $row['phone'];
  $dob = $row['dob'];
  $workteam = $row['workteam'];
  $steward = $row['steward'];
  $technical = $row['technical'];
  $artistic = $row['artistic'];
  $media = $row['media'];
  $duties = $row['duties'];
  $unable = $row['unable'];
  $thu = $row['thu'];
  $fri = $row['fri'];
  $sat = $row['sat'];
  $sun = $row['sun'];
  $mon = $row['mon'];
  $dbs = $row['dbs'];
  $dbsdetails = $row['dbsdetails'];
  $emergencycontact = $row['emergencycontact'];
  $relationship = $row['relationship'];
  $emergencyphone = $row['emergencyphone'];
  $esig = $row['esig'];
  $status = $row['status'];
  $applydate = $row['applydate'];
  $festivalyear = $row['festivalyear'];
  $tickettype = $row['tickettype'];
  $parking = $row['parking'];
  $camping = $row['camping'];

  date_default_timezone_set('GMT');

  $applydate = date('j M Y', strtotime($applydate));
  $status = ucwords($status);
  $contactname = ucwords($contactname);
  $address = ucwords($address);
  $workteam = ucwords($workteam);
  $duties = ucwords($duties);
  $unable = ucwords($unable);
  $dbsdetails = ucwords($dbsdetails);
  $emergencycontact = ucwords($emergencycontact);

  if($status == 'Confirmed')
    {
    $status = "<td style=\"background:#00CC00;text-align:center;\"><strong>$status</strong>
<br /><br />Change to:
<br /><a href=\"?view=confirm&status=denied&id=$id#$id\" style=\"color:#FFFFFF;\"><strong>Denied</strong></a></td>";
    }

  if($status == 'Denied')
    {
    $status = "<td style=\"background:#CC0000;text-align:center;\"><strong>$status</strong>
<br /><br />Change to:
<br /><a href=\"?view=confirm&status=confirmed&id=$id#$id\" style=\"color:#FFFFFF;\"><strong>Confirmed</strong></a></td>";
    }

  if($status == 'Unconfirmed')
    {
    $status = "<td style=\"background:#FF6633;text-align:center;\"><strong>$status</strong>
<br /><br />Change to:
<br /><a href=\"?view=confirm&status=confirmed&id=$id#$id\" style=\"color:#FFFFFF;\"><strong>Confirmed</strong></a>
<br /><a href=\"?view=confirm&status=denied&id=$id#$id\" style=\"color:#FFFFFF;\"><strong>Denied</strong></a></td>";
    }

  echo "<tr>
<td style=\"text-align:center;\">$id</td>
<td style=\"text-align:center;\">$applydate</td>
$status
<td><a name=\"$id\"></a><strong>$contactname</strong> - <a href=\"?edit=confirm&id=$id\">Edit</a><br />$address<br /><a href=\"mailto:$email\">$email</a><br />$phone<br /><br />DOB: <strong>$dob</strong></td>
<td>Worteam: <strong>$workteam</strong> - <a href=\"?edit=confirm&id=$id#info\">Edit</a><br /><br />Steward: <strong>$steward</strong><br />Tech: <strong>$technical</strong><br />Artistic: <strong>$artistic</strong><br />Media:<strong>$media</strong><br /><br />Preferred Duties: <strong>$duties</strong><br />Unable to do:<strong>$unable</storng></td>
<td>Times <strong>NOT</strong> available - <a href=\"?edit=confirm&id=$id#details\">Edit</a><br />Thu: $thu<br />Fri: $fri<br />Sat: $sat<br />Sun: $sun<br />Mon: $mon<br /><br />DBS: <strong>$dbs</strong><br />DBS Details: <strong>$dbsdetails</strong></td>
<td>Emergency Contact - <a href=\"?edit=confirm&id=$id#details\">Edit</a><br /><strong>$emergencycontact</strong><br />Relationship: <strong>$relationship</strong><br />Phone: <strong>$emergencyphone</strong><br /><br />Complimentaries<br />Ticket: <strong>$tickettype</strong><br />Parking: <strong>$parking</strong><br />Camping: <strong>$camping</strong></td>
</tr>";
  }

mysql_close($con);
?>
</table>
</div>
<?php include("files/footer.php"); ?>
</body>
</html>
