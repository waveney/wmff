<?php
  include_once("fest.php");
  A_Check('Committee');
?>

<html>
<head>
<title>WMFF Staff | List Participants</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
</head>
<body>
<?php include("files/navigation.php"); ?>
<div class="content">

<?php
  global $db;
  $yn = array('','Y');
  include("PartLib.php");

  $ListType = 0;
  if (isset($_GET['Z'])) $ListType = $_GET['Z'];
  if ($ListType == 0) {
    echo "<h2>Other Participants Coming</h2>";
  } else if ($ListType < 0) {
    echo "<h2>All Other Participants</h2>";
  } // > 0 for year

  $People = Get_Other_People($ListType);
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
    echo "<tr><td>" . $Person['OtherId'];
    echo "<td><a href=AddPart.php?othernum=" . $Person['OtherId'] . ">" . $Person['Name'] . "</a>";
    echo "<td>" . $Person['Description'];
    echo "<td>" . $Person['Contact'] . "<td>" . linkemailhtml($Person,'Other') . "<td>" . $Person['Type'];
    echo "<td>";
    if ($Person['Fri']) echo "y";
    echo "<td>";
    if ($Person['Sat']) echo "y";
    echo "<td>";
    if ($Person['Sun']) echo "y";
  }
  echo "</tbody></table>\n";

  echo "<h2><a href=AddPart.php>Add Other Participant</a>, <a href=ListPart.php?Z=-1>List All Other Participants</a>, </h2>";
?>
  
</div>
<?php include("files/footer.php"); ?>
</body>
</html>
