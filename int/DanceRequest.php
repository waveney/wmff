<html>
<head>
<title>Wimborne Minster Folk Festival | Request</title>
<?php include_once("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("DanceLib.php"); ?>
</head>
<body>
<?php
  $snum=$_GET{'sidenum'};
  A_Check('Participant','Side',$snum);
  include_once("files/navigation.php");
  echo "<div class='content'><h2>Requested</h2>\n";

  $Side=Get_Side(snum);

  $emails = Get_Emails('Dance');

  SendEmail($emails,$Side['SName'] . " request invite",$Side['SName'] . " request an invite for $YEAR");

  Show_Side($_GET{'sidenum'});
?>
  
</div>

<?php include_once("files/footer.php"); ?>
</body>
</html>
