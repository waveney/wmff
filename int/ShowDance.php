<html>
<head>
<title>Wimborne Minster Folk Festival | Dance Side</title>
<?php include_once("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("DanceLib.php"); ?>
</head>
<body>
<?php include_once("files/navigation.php"); ?>
<div class="content">
<?php
  if (isset($_GET{'sidenum'})) {
    Show_Side($_GET{'sidenum'});
  } else {
    echo "No Side Indicated";
  }
?>
  
</div>

<?php include_once("files/footer.php"); ?>
</body>
</html>
