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

<h2 class="maintitle">View Live & Loud Applications</h2>

<table cellspacing="5" cellpadding="5" style="background-color:#000000; border-color:#000000;">
<tr>
<td style="width:25px;font-weight:bold;text-align:center;">No.</td>
<td style="width:100px;font-weight:bold;text-align:center;">Applied</td>
<td style="width:50px;font-weight:bold;text-align:center;">Status</td>
<td style="width:250px;font-weight:bold;">Contact</td>
<td style="width:250px;font-weight:bold;">Performer</td>
<td style="width:75px;font-weight:bold;">Audition</td>
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

$result = mysql_query("SELECT * FROM wmfflive WHERE festivalyear='2017' ORDER BY applydate ASC");

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
<td><a name=\"$id\"></a><strong>$contactname</strong> - <a href=\"?edit=confirm&id=$id\">Edit</a><br />$address<br /><a href=\"mailto:$email\">$email</a><br />$phone</td>
<td>Act Name: <strong>$performername</strong> - <a href=\"?edit=confirm&id=$id#performer\">Edit</a><br />No. in Act: <strong>$performernumber</strong><br />Ages: <strong>$performerage</strong><br />Instruments: <strong>$performerinst</strong><br />Genre: <strong>$performergenre</strong><br />Demo: <a href=\"$performerdemo\" target=\"_blank\"><strong>$performerdemo</strong></a></td>
<td><a href=\"?edit=confirm&id=$id#audition\">Edit</a><br />SR: $squarerecords<br />WH: $whitehart<br />RS: $risingsun<br />QE: $qe<br /><br />Tech Q's: $tech</td>
</tr>";
  }

mysql_close($con);
?>
</table>
</div>
<?php include("files/footer.php"); ?>
</body>
</html>
