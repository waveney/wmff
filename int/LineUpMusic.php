<html>
<head>
<title>Wimborne Minster Folk Festival | Music Acts</title>
<?php include_once("files/header.php"); ?>
<?php include_once("int/festcon.php"); ?>
<?php include_once("int/DanceLib.php"); ?>
</head>
<body>
<?php include_once("files/navigation.php"); ?>
<div class="content"><h2>Music Acts</h2>

Click on column header to sort by column.<br>
Click on Side's name for more detail and programme when available.<br>

<?php

  $yn = array('','Y');
  $Min = isset($_GET{'MIN'});
  $SideQ = $db->query("SELECT s.*, y.* FROM Sides AS s, SideYear AS y " .
	   "WHERE s.SideId=y.SideId AND y.year=$YEAR AND y.Coming=" . $Coming_Type['Y'] . " ORDER BY s.Importance DESC, s.Name");

  if (!$SideQ || $SideQ->num_rows==0) {
    echo "<h2>No Sides Found</h2>\n";
//    echo "<!-- " . var_dump($SideQ) . " -->\n";
  } else {
    $coln = 0;
    echo "<table id=indextable border>\n";
    echo "<thead><tr>";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
    if (!$Min) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Type</a>\n";
    echo "<th><a href=javascript:sorttable(" . $coln++ . ",'T')>Description</a>\n";

    if (!$Min) echo "<th><a href=javascript:sorttable(" . $coln++ . ",'T')>Fri</a>\n";
    if (!$Min) echo "<th><a href=javascript:sorttable(" . $coln++ . ",'T')>Sat</a>\n";
    if (!$Min) echo "<th><a href=javascript:sorttable(" . $coln++ . ",'T')>Sun</a>\n";
//    echo "<th><a href=javascript:sorttable(" . $coln++ . ",'T')>Website</a>\n";
    if (!$Min) echo "<th><a href=javascript:sorttable(" . $coln++ . ",'T')>Photo</a>\n";
//    echo "<th><a href=javascript:sorttable(" . $coln++ . ",'T')>Video</a>\n";
//    echo "<th><img src=/images/intIcons/Facebook.jpg>";
//    echo "<th><img src=/images/intIcons/Twitter.jpg>";
//    echo "<th><img src=/images/intIcons/Instagram.jpg>";
    echo "</thead><tbody>";
    while ($side = $SideQ->fetch_assoc()) {
      if (strlen($side['Name']) < 2) $side['Name'] .= " PADDED";
      echo "<tr><td><a href=/int/ShowDance.php?sidenum=" . $side['SideId'] . ">" . $side['Name'] . "</a></td>";
      if (!$Min) echo "<td>" . $side['Type'] . "</td>";
      echo "<td>" . $side['Description'] . "</td>";
      if (!$Min) echo "<td>" . $yn[$side['Fri']] . "</td>";
      if (!$Min) echo "<td>" . $yn[$side['Sat']] . "</td>";
      if (!$Min) echo "<td>" . $yn[$side['Sun']] . "</td>";
/*
      if ($side['Website']) {
	echo "<td>" . weblink($side['Website']);
      } else {
	echo "<td>";
      }
*/
      if (!$Min) {
	if ($side['Photo']) {
  	  echo "<td><img src='" . $side['Photo'] . "' width=200></td>";
        } else {
	  echo "<td>";
	}
      }
/*
      if ($side['Video']) {
	echo "<td><a href=" . videolink($side['Video']) . "><img src=images/intIcons/YouTube.jpg></a></td>";
      } else {
	echo "<td>";
      }
      echo "<td>" . Social_Link($side,'Facebook',1);
      echo "<td>" . Social_Link($side,'Twitter',1);
      echo "<td>" . Social_Link($side,'Instagram',1);
*/
    }
    echo "</tbody></table>\n";
  }
  
?>
  
</div>

<?php include_once("files/footer.php"); ?>
</body>
</html>
