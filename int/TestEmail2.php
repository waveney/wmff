<?php
  include_once("fest.php");
  A_Check('Committee','Users');
?>

<html>
<head>
<title>WMFF Staff | Welcome</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
</head>
<body>

<?php
  include("files/navigation.php");
  include("UserLib.php");


    mail("richard@wavwebs.com","Test Email",'Test Message 2');


    echo "Email sent:<p>$letter";
?>

</div>

<?php include("files/footer.php"); ?>
</body>
</html>
