<?php
  include_once("fest.php");
  A_Check('Committee','Dance');
?>

<html>
<head>
<title>WMFF Staff | Dance Checking</title>
<?php include_once("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("DanceLib.php"); ?>
</head>
<body>

<?php
  global $YEAR;
  include_once("files/navigation.php");
  echo '<div class="content"><h2>Dance Check</h2>';
  echo "Programming does this as you go, this is mainly to enable it to be tested<p>";

  include_once("CheckDance.php");
  CheckDance(2);
   
?>

</div>

<?php include_once("files/footer.php"); ?>
</body>
</html>
