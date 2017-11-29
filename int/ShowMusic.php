<html>
<head>
<title>Wimborne Minster Folk Festival | Music Acts</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("DanceLib.php"); ?>
<?php include_once("MusicLib.php"); ?>
</head>
<body>
<?php include("files/navigation.php"); ?>
<div class="content">
<?php
  if (isset($_GET{'sidenum'})) {
    Show_Side($_GET{'sidenum'});
  } else {
    echo "No Act Indicated";
  }
?>
  
</div>

<?php include("files/footer.php"); ?>
</body>
</html>
