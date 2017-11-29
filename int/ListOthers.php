<?php
  include_once("fest.php");
  A_Check('Committee');
?>

<html>
<head>
<title>Wimborne Folk Festival Committee</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
</head>
<body>
<?php include("files/navigation.php"); ?>
<div class="content">

<?php
  global $db;
  $yn = array('','Y');
  include("OtherLib.php");

  $People = Get_Other_People(0);
  echo "If you click on the email link, press control-V afterwards to paste the standard link into message.<p>";

  $coln = 0;
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')></a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Description</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Contact</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Type</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Fri</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Sat</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Sun</a>\n";
  echo "</thead><tbody>";

  foreach ($People as $i=>$Person) {
    echo "<tr><td>" . $Person['OtherId'] . "<td>" .$Person['Name'] .  "<td>" . $Person['Description'];
    echo "<td>" . $Person['Contact'] . "<td>" . linkemailhtml($fetch,'Other') . "<td>" . $Person['Type'];
    echo "<td>";
    if ($People['Sat']) echo "y";
    echo "<td>";
    if ($People['Sat']) echo "y";
    echo "<td>";
    if ($People['Sun']) echo "y";
  }
  echo "</tbody></table>\n";

  echo "<h2><a href=AddOthers.php>Add Other Participant</a></h2>";
?>
  
</div>
<?php include("files/footer.php"); ?>
</body>
</html>
