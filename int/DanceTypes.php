<?php
  include_once("fest.php");
  A_Check('SysAdmin');
?>

<html>
<head>
<title>WMFF Staff | Dance Types</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("DanceLib.php"); ?>
</head>
<body>
<?php include("files/navigation.php"); ?>
<?php
  include_once("TradeLib.php");

  $Types=Get_Dance_Types(1);
  if (UpdateMany('DanceTypes','Put_Dance_Type',$Types,0)) $Types=Get_Dance_Types(1);

  echo "<h2>Dance Types</h2><p>";
  echo "You do not have to have all Dance Types here, these are just the categories brought out in the summary page.<p>";
  echo "<form method=post action=DanceTypes.php>";
  echo "<table border><tr><td>id<td>Name<td>Importance<td>Colour\n";
  foreach($Types as $i=>$t) {
    echo "<tr><td>$i<td><input type=text name=SName$i value='" . $t['SName'] . "'>";
    echo "<td><input type=text name=Importance$i value='" . $t['Importance'] . "'>\n";
    echo "<td><input type=text name=Colour$i value='" . $t['Colour'] . "'>\n";
  }
  echo "<tr><td><td><input type=text name=SName0 >";
  echo "<td><input type=text name=Importance0>\n";
  echo "<td><input type=text name=Colour0>\n";
  echo "</table>";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form>";

?>

</div>

<?php include("files/footer.php"); ?>
</body>
</html>
