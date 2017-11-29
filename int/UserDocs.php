<?php
  include_once("fest.php");
  A_Check('Committee');
?>

<html>
<head>
<title>WMFF Staff | Doc Usage</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
</head>
<body>
<?php include("files/navigation.php"); ?>
<div class="content"><h2>File storage used</h2>

<?php

  include("DocLib.php");
  $AllU = Get_AllUsers();


  $qry = "SELECT Who, Sum(filesize), Count(*) FROM Documents WHERE State=0 GROUP BY Who ORDER BY Who";
  $res = $db->query($qry);

  $coln = 0;
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>User Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Files</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Size</a>\n";
  echo "</thead><tbody>";

  while($usr = $res->fetch_array()) {
    echo "<tr><td>" . $usr[0] . "<td>" . $AllU[$usr[0]] . "<td>" . $usr[2] . "<td align=right>" . formatBytes($usr[1]) . "\n";
  }
  echo "</tbody></table>\n";
  
?>
  
</div>
<?php include("files/footer.php"); ?>
</body>
</html>
