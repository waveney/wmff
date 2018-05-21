<?php
  include_once("fest.php");
  A_Check('Committee','Dance');
?>

<html>
<head>
<title>WMFF Staff | View Insurance</title>
<?php include_once("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("DanceLib.php"); ?>
<?php include_once("OtherLib.php"); ?>
<?php include_once("MusicLib.php"); ?>
</head>
<body>

<?php
  global $YEAR,$USERID;
  include_once("files/navigation.php");
  echo '<div class="content"><h2>Insurance</h2>';

  if (isset($_GET{'sidenum'})) {
    $snum = $_GET{'sidenum'};
    $data = Get_Side($snum);
    $type = 'Sides';
  } else if (isset($_GET{'othernum'})) {
    $snum = $_GET{'othernum'};
    $data = Get_Other($snum);
    $type = 'Others';
  } else if (isset($_GET{'actnum'})) {
    $snum = $_GET{'actnum'};
    $data = Get_Act($snum);
    $type = 'Acts';
  } else Error_Page("Viewing Insurance of nothing");

  $file = glob("Insurance/$type/$YEAR/$snum.*");

  if ($file) {
    $sfx = pathinfo($file[0],PATHINFO_EXTENSION);
    copy($file[0],"Temp/$USERID.$sfx");
    echo "<img src=Temp/$USERID.$sfx>\n";
  } else {
    echo "<h2>No Insurance Stored for " . $data['SName'] . "</h2>\n";
  }

?>

</div>

<?php include_once("files/footer.php"); ?>
</body>
</html>
