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

<h2 class="maintitle">View Trade Applications</h2>

<table cellspacing="5" cellpadding="5" style="background-color:#000000; border-color:#000000;">
<tr>
<td style="width:25px;font-weight:bold;text-align:center;">No.</td>
<td style="width:100px;font-weight:bold;text-align:center;">Applied</td>
<td style="width:50px;font-weight:bold;text-align:center;">Cat</td>
<td style="width:50px;font-weight:bold;text-align:center;">Status</td>
<td style="width:250px;font-weight:bold;">Contact</td>
<td style="width:250px;font-weight:bold;">Pitch</td>
<td style="width:75px;font-weight:bold;">Priority</td>
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

if(empty($getcat))
  {
  $cat = "";
  }

if($getcat == 'trade')
  {
  $cat = " AND cat='non-food'";
  }

if($getcat == 'food')
  {
  $cat = " AND cat='food'";
  }

if($getcat == 'artisan')
  {
  $cat = " AND cat='artisan'";
  }

mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmfftrade WHERE festivalyear='2017'$cat ORDER BY applydate ASC");

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

  $pitchfee = number_format($pitchfee, 2, '.', '');

  if($status == 'Confirmed')
    {
    $status = "<td style=\"background:#00CC00;text-align:center;\"><strong>$status</strong>
<br /><br />Change to:
<br /><a href=\"?view=confirm&status=deposit&id=$id#$id\" style=\"color:#FFFFFF;\"><strong>Deposit</strong></a>
<br /><a href=\"?view=confirm&status=paid&id=$id#$id\" style=\"color:#FFFFFF;\"><strong>Paid</strong></a>
<br /><a href=\"?view=confirm&status=denied&id=$id#$id\" style=\"color:#FFFFFF;\"><strong>Denied</strong></a></td>";
    }

  if($status == 'Deposit')
    {
    $status = "<td style=\"background:#00CC00;text-align:center;\"><strong>$status Received</strong>
<br /><br />Change to:
<br /><a href=\"?view=confirm&status=paid&id=$id#$id\" style=\"color:#FFFFFF;\"><strong>Paid</strong></a>
<br /><a href=\"?view=confirm&status=confirmed&id=$id#$id\" style=\"color:#FFFFFF;\">(Undo)</a></td>";
    }

  if($status == 'Paid')
    {
    $status = "<td style=\"background:#00CC00;text-align:center;\"><strong>Fully $status</strong>
<br /><a href=\"?view=confirm&status=confirmed&id=$id#$id\" style=\"color:#FFFFFF;\">(Undo)</a></td>";
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

  if($cat == 'Food')
    {
    $cat = "<td style=\"background:#00CCCC;text-align:center;\"><strong>$cat</strong>
<br /><br />Change to:
<br /><a href=\"?view=confirm&changecat=non-food&id=$id#$id\" style=\"color:#FFFFFF;\"><strong>Non-food</strong></a>
<br /><a href=\"?view=confirm&changecat=artisan&id=$id#$id\" style=\"color:#FFFFFF;\"><strong>Artisan</strong></a></td>";
    }

  if($cat == 'Non-food')
    {
    $cat = "<td style=\"background:#CC00CC;text-align:center;\"><strong>$cat</strong>
<br /><br />Change to:
<br /><a href=\"?view=confirm&changecat=food&id=$id#$id\" style=\"color:#FFFFFF;\"><strong>Food</strong></a>
<br /><a href=\"?view=confirm&changecat=artisan&id=$id#$id\" style=\"color:#FFFFFF;\"><strong>Artisan</strong></a></td>";
    }

  if($cat == 'Artisan')
    {
    $cat = "<td style=\"background:#CCCC00;text-align:center;\"><strong>$cat</strong>
<br /><br />Change to:
<br /><a href=\"?view=confirm&changecat=food&id=$id#$id\" style=\"color:#FFFFFF;\"><strong>Food</strong></a>
<br /><a href=\"?view=confirm&changecat=non-food&id=$id#$id\" style=\"color:#FFFFFF;\"><strong>Non-food</strong></a></td>";
    }

  if(!empty($charity))
    {
    $charity = "<br /><strong>Charity: $charity</strong>";
    }

  if(empty($charity))
    {
    $charity = "";
    }

  echo "<tr>
<td style=\"text-align:center;\">$id</td>
<td style=\"text-align:center;\">$applydate</td>
$cat
$status
<td><a name=\"$id\"></a><strong>$contactname</strong> - <a href=\"?edit=confirm&id=$id\">Edit</a><br />$business<br />$invoiceaddress<br /><a href=\"mailto:$email\">$email</a><br />$phone</td>
<td>Fee: &#163;$pitchfee - <a href=\"?edit=confirm&id=$id#pitch\">Edit</a><br />Location: $location<br />Accepted: $accepted<br /><br />Size: $pitchsize<br />Products: $products $charity</td>
<td><a href=\"?edit=confirm&id=$id#priority\">Edit</a><br />BID:$bidlevy<br />Chamber:$chamber<br />WMFF:$festivaltrader</td>
</tr>";
  }

mysql_close($con);
?>
</table>
</div>
<?php include("files/footer.php"); ?>
</body>
</html>
