<?php
  include_once("fest.php");
  A_Check('SysAdmin');
?>

<html>
<head>
<title>WMFF Staff | Music Types</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("MusicLib.php"); ?>
</head>
<body>
<?php include("files/navigation.php"); ?>
<?php

  $Types=Get_Music_Types(1);

  if (isset($_POST{'Update'})) {
    foreach($Types as $i=>$t) {
      if ($_POST["Name$i"] != $t['Name'] || $_POST["Imp$i"] != $t['Importance'] ) {
	if ($_POST["Name$i"] == '') {
	  db_delete('MusicTypes',$t['TypeId']);
	} else {
          $t['Name'] = $_POST["Name$i"];
  	  $t['Importance'] = $_POST["Imp$i"];
	  Put_Music_Type($t);
	}
      }
    }
    if ($_POST["Name0"]) {
      $t = array('Name'=> $_POST['Name0'], 'Importance' => $_POST['Imp0']);
      Insert_db('MusicTypes',$t);
    }
    $Types=Get_Music_Types(1);
  } 

  echo "<h2>Music Types</h2><p>";
  echo "You do not have to have all Music Types here, these are just the categories brought out in the summary page.<p>";
  echo "<form method=post action=MusicTypes.php>";
  echo "<table border><tr><td>id<td>Name<td>Importance\n";
  foreach($Types as $i=>$t) {
    echo "<tr><td>$i<td><input type=text name=Name$i value='" . $t['Name'] . "'>";
    echo "<td><input text name=Imp$i value='" . $t['Importance'] . "'>\n";
  }
  echo "<tr><td><td><input type=text name=Name0 >";
  echo "<td><input text name=Imp0>\n";
  echo "</table>";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form>";

?>

</div>

<?php include("files/footer.php"); ?>
</body>
</html>
