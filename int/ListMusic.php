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
  global $YEAR,$THISYEAR,$Book_Colour,$Book_States,$Book_Actions;
  include("files/navigation.php"); 
  include_once("DanceLib.php"); 
  include_once("MusicLib.php"); 

  if (isset($_GET['t']) && $_GET['t'] == 'O') {
    $TypeSel = ' s.IsOther=1';
    echo "<div class=content><h2>List Other Participants $YEAR</h2>\n";
  } else {
    $TypeSel = ' s.IsAnAct=1';
    echo "<div class=content><h2>List Music Acts $YEAR</h2>\n";
  }

  echo "Click on column header to sort by column.  Click on Acts's name for more detail and programme when available,<p>\n";

  echo "If you click on the email link, press control-V afterwards to paste the standard link into message.<p>";
  $col7 = '';

  if (isset($_GET['ACTION'])) {
    $sid = $_GET['SideId'];
    $side = Get_Side($sid);
    Music_Action($_GET{'ACTION'},$side);
  }

  if ($_GET{'SEL'} == 'ALL') {
    $flds = "y.*, s.*";
    $SideQ = $db->query("SELECT $flds FROM Sides AS s LEFT JOIN ActYear as y ON s.SideId=y.SideId WHERE $TypeSel ORDER BY Name");
    $col5 = "Book State";
    $col6 = "Actions";
  } else if ($_GET{'SEL'} == 'INV') {
    $LastYear = $THISYEAR-1;
    $flds = "s.*, ly.YearState, y.YearState, y.ContractConfirm";
    $SideQ = $db->query("SELECT $flds FROM Sides AS s LEFT JOIN ActYear as y ON s.SideId=y.SideId AND y.year=$THISYEAR " .
			"LEFT JOIN SideYear as ly ON s.SideId=ly.SideId AND ly.year=$LastYear WHERE $TypeSel AND s.SideStatus=0 ORDER BY Name");
    $col5 = "Invited $LastYear";
    $col6 = "Coming $LastYear";
    $col7 = "Invite $THISYEAR";
    $col8 = "Invited $THISYEAR";
    $col9 = "Coming $THISYEAR";
  } else if ($_GET{'SEL'} == 'Coming') {
    $SideQ = $db->query("SELECT s.*, y.* FROM Sides AS s, ActYear as y WHERE $TypeSel AND s.SideId=y.SideId AND y.year=$YEAR AND y.YearState=" . 
		$Book_State['Booked'] . " ORDER BY Importance DESC, Name");
    $col5 = "Complete?";
  } else if ($_GET{'SEL'} == 'Booking') {
    $SideQ = $db->query("SELECT s.*, y.* FROM Sides AS s, ActYear as y WHERE $TypeSel AND s.SideId=y.SideId AND y.year=$YEAR AND y.YearState>0" . 
		" ORDER BY Importance DESC, Name");
    $col5 = "Book State";
    $col6 = "Actions";
  } else { // general public list
    $flds = "s.*, y.Sat, y.Sun";
    $SideQ = $db->query("SELECT $flds FROM Sides AS s, ActYear as y WHERE $TypeSel AND s.SideId=y.SideId AND y.year=$YEAR AND y.YearState=" . 
		$Book_State['Booked'] . " ORDER BY Importance DESC Name");
  }

  if (!$SideQ || $SideQ->num_rows==0) {
    echo "<h2>No Acts Found</h2>\n";
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

      $State = $fetch['YearState'];
      if (isset($State)) {
        Contract_State_Check(&$fetch); 
	$State = $fetch['YearState'];
      } else {
	$state = 0;
      }
      for ($fld=5; $fld<10; $fld++) {
	$ff = "col$fld";
        switch ($$ff) {

        case 'Book State': 
	  echo "<td style='background-color:" . $Book_Colour[$Book_States[$State]] . "'>" . $Book_States[$State];
          break;

        case 'Confirmed':
	  echo "<td>" . ($fetch['ContractConfirm']?'Yes':'');
	  break;

	case 'Actions':
	  echo "<td>";
	  $acts = $Book_Actions[$Book_States[$State]];
	  if ($acts) {
	    $acts = preg_split('/,/',$acts); 
	    echo "<form>" . fm_Hidden('SEL',$_GET['SEL']) . fm_hidden('SideId',$fetch['SideId']) . (isset($_GET['t'])? fm_hidden('t',$_GET[t]) : '') ;
	    foreach($acts as $ac) {
	      echo "<button class=floatright name=ACTION value='$ac' type=submit " . $ButExtra[$ac] . " >$ac</button>";
	    }
	    echo "</form>";
	  } 
	  break;
        default:
	  break;

        }
      }
    }
    echo "</tbody></table>\n";
  }
  dotail(); 
?>
