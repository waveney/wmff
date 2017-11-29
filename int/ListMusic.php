<?php
  include_once("fest.php");
  A_Check('Steward');
?>

<html>
<head>
<title>WMFF Staff | List Music Acts</title>
<script src="/js/clipboard.min.js"></script>
<script src="/js/emailclick.js"></script>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
</head>
<body>
<?php 
  global $YEAR,$THISYEAR;
  include("files/navigation.php"); 
  echo "<div class=content><h2>List Music Acts $YEAR</h2>\n";

  echo "Click on column header to sort by column.  Click on Acts's name for more detail and programme when available,<p>\n";

  echo "If you click on the email link, press control-V afterwards to paste the standard link into message.<p>";
  $col7 = '';

  if ($_GET{'SEL'} == 'ALL') {
    $flds = "y.*, s.*";
    $SideQ = $db->query("SELECT $flds FROM Sides AS s LEFT JOIN ActYear as y ON s.SideId=y.SideId WHERE s.IsAnAct=1 ORDER BY Name");
    $col5 = "Book State";
  } else if ($_GET{'SEL'} == 'INV') {
    $LastYear = $THISYEAR-1;
    $flds = "s.*, ly.YearState, y.YearState, y.ContractConfirm";
    $SideQ = $db->query("SELECT $flds FROM Sides AS s LEFT JOIN ActYear as y ON s.SideId=y.SideId AND y.year=$THISYEAR " .
			"LEFT JOIN SideYear as ly ON s.SideId=ly.SideId AND ly.year=$LastYear WHERE s.IsAnAct=1 AND s.SideStatus=0 ORDER BY Name");
    $col5 = "Invited $LastYear";
    $col6 = "Coming $LastYear";
    $col7 = "Invite $THISYEAR";
    $col8 = "Invited $THISYEAR";
    $col9 = "Coming $THISYEAR";
  } else if ($_GET{'SEL'} == 'Coming') {
    $SideQ = $db->query("SELECT s.*, y.* FROM Sides AS s, ActYear as y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.year=$YEAR AND y.YearState=" . 
		$Book_State['Booked'] . " ORDER BY Importance DESC, Name");
    $col5 = "Complete?";
  } else if ($_GET{'SEL'} == 'Booking') {
    $SideQ = $db->query("SELECT s.*, y.* FROM Sides AS s, ActYear as y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.year=$YEAR AND y.YearState>0" . 
		" ORDER BY Importance DESC, Name");
    $col5 = "Book State";
  } else { // general public list
    $flds = "s.*, y.Sat, y.Sun";
    $SideQ = $db->query("SELECT $flds FROM Sides AS s, ActYear as y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.year=$YEAR AND y.YearState=" . 
		$Book_State['Booked'] . " ORDER BY Importance DESC Name");
  }

  if (!$SideQ || $SideQ->num_rows==0) {
    echo "<h2>No Sides Found</h2>\n";
  } else {
    $coln = 0;
    echo "<table id=indextable border>\n";
    echo "<thead><tr>";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Type</a>\n";
    if ($_GET{'SEL'}) {
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Contact</a>\n";
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
//      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Link</a>\n";
    }
    if ($col5) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col5</a>\n";
    if ($col6) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col6</a>\n";
    if ($col7) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col7</a>\n";
    if ($col8) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col8</a>\n";
    if ($col9) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col9</a>\n";
//    for($i=1;$i<5;$i++) {
//      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>EM$i</a>\n";
//    }

    echo "</thead><tbody>";
    while ($fetch = $SideQ->fetch_assoc()) {
//      echo "<tr><td><a href=AddDance.php?sidenum=" . $fetch['SideId'] . ">" . $fetch['SideId'] . "</a>";
      echo "<tr><td><a href=AddMusic.php?sidenum=" . $fetch['SideId'] . ">" . $fetch['Name'] . "</a>";
      if ($fetch['SideStatus']) {
	echo "<td>DEAD";
      } else {
        echo "<td>" . $fetch['Type'];
      }
      if ($_GET{'SEL'}) {
	echo "<td>" . $fetch['Contact'];
//	echo "<td><a href=mailto:" . Clean_Email($fetch['Email']) . ">" . $fetch['Email'] . "</a>";
        echo "<td>" . linkemailhtml($fetch,'Act');
      } 

      for ($fld=5; $fld<10; $fld++) {
	$ff = "col$fld";
        switch ($$ff) {

        case 'Book State': 
	  echo "<td>" . $Book_States[$fetch['YearState']];
          break;

        case 'Confirmed':
	  echo "<td>" . ($fetch['ContractConfirm']?'Yes':'');
	  break;

        default:
	  break;

        }
      }
    }
    echo "</tbody></table>\n";
  }
  
?>
  
</div>

<?php include("files/footer.php"); ?>
</body>
</html>
