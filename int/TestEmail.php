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

  if (isset($_GET['U'])) {
    $uid = $_GET['U'];
    $user = Get_User($uid);

    $letter = firstword($user['SName']) . ",<p>" .
        "This is a message via the other server if so progress many now be possible...<p>".
	"Richard";
 
    SendEmail($user['Email'],"Welcome " . firstword($user['SName']) . " to WMFF Staff pages",$letter);

    echo "Email sent:<p>$letter";
  } else {
    echo "No user..."; 
  }
?>

</div>

<?php include("files/footer.php"); ?>
</body>
</html>
