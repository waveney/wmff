<html>
<head>
<title>Wimborne Minster Folk Festival | View Word File</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
</head>
<body>
<?php 
  include("files/navigation.php"); 
  global $USERID;

  $file = $_GET{'l'};
  $sfx = pathinfo($file,PATHINFO_EXTENSION);

  system("rm Temp/$USERID.*");
  $tf = $USEDID . time() . ".$sfx";
  $c = copy($file,"Temp/$tf");
  echo "<iframe src='https://view.officeapps.live.com/op/view.aspx?src=http%3A%2F%2Fwimbornefolk.co.uk%2Fint%2FTemp%2F$tf'";
  echo " width=100% height=800";
  echo "></iframe>";

?>

<?php include("files/footer.php"); ?>
</body>
</html>
