<html>
<head>
<title>Wimborne Minster Folk Festival | Request</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("DanceLib.php"); ?>
</head>
<body>
<?php
  $snum=$_GET{'sidenum'};
  A_Check('Participant','Side',$snum);
  include("files/navigation.php");
  echo "<div class='content'><h2>Requested</h2>\n";

  $Side=Get_Side(snum);

  $emails = Get_Emails('Dance');

  SendEmail($emails,$Side['Name'] . " request invite",$Side['Name'] . " request an invite for $YEAR");

  Show_Side($_GET{'sidenum'});
?>
  
</div>

<?php include("files/footer.php"); ?>
</body>
</html>
