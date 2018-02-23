<?php
  include_once("fest.php");
  A_Check('Steward');
?>

<html>
<head>
<title>WMFF Staff | Invite Dance</title>
<script src="/js/clipboard.min.js"></script>
<script src="/js/emailclick.js"></script>
<script src="/js/InviteThings.js"></script>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
</head>
<body>
<?php include("files/navigation.php"); ?>
<div class="content"><h2>Invite Dance Sides</h2>
<div id=hidden hidden></div>

Click on column header to sort by column.  Click on Side's name for more detail and programme when available,<p>

<?php
  include("DanceLib.php"); 
  global $YEAR,$THISYEAR;
  echo "If you click on the email link, press control-V afterwards to paste the standard link into message.<p>";

  echo "<div id=InformationPane></div><p>\n";
  $Types = Get_Dance_Types(1);
  foreach ($Types as $i=>$ty) $Colour[strtolower($ty['SName'])] = $ty['Colour'];

  echo "<h2>";
  $Loc = 0;
  if (isset($_GET{'LOC'})) $Loc = $_GET{'LOC'};
  $Contact =0;
  if (isset($_GET{'CONT'})) $Contact = $_GET{'CONT'};
  if ($Loc == 0) echo "<a href=InviteDance.php?LOC=1" . ($Contact?"&CONT=1":"") . ">Show Location</a> &nbsp; &nbsp; &nbsp; &nbsp;\n";
  if ($Contact == 0) echo "<a href=InviteDance.php?CONT=1" .($Loc?"&LOC=1":"") . ">Show Contact</a>\n";
  echo "</h2>";

  $LastYear = $THISYEAR-1;
  $flds = "s.*, ly.Invite AS LyInvite, ly.Coming AS LyComing, y.Invite, y.Invited, y.Coming";
  $SideQ = $db->query("SELECT $flds FROM Sides AS s LEFT JOIN SideYear as y ON s.SideId=y.SideId AND y.year=$THISYEAR " .
			"LEFT JOIN SideYear as ly ON s.SideId=ly.SideId AND ly.year=$LastYear WHERE s.IsASide=1 AND s.SideStatus=0 ORDER BY SName");
  $col5 = "Invited $LastYear";
  $col6 = "Coming $LastYear";
  $col7 = "Invite $THISYEAR";
  $col8 = "Invited $THISYEAR";
  $col9 = "Coming $THISYEAR";

  if (!$SideQ || $SideQ->num_rows==0) {
    echo "<h2>No Sides Found</h2>\n";
  } else {
    $coln = 0;
    echo "<table id=indextable border width=100%>\n";
    echo "<thead><tr>";
    echo "<th width=200><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Type</a>\n";
    if ($Contact) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Contact</a>\n";
    if ($Loc) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Location</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Web</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col5</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col6</a>\n";
    if ($col7) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'O')>$col7</a>\n";
    if ($col8) echo "<th width=200><a href=javascript:SortTable(" . $coln++ . ",'T')>$col8</a>\n";
    if ($col9) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col9</a>\n";
//    for($i=1;$i<5;$i++) {
//      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>EM$i</a>\n";
//    }

    echo "</thead><tbody>";
    while ($fetch = $SideQ->fetch_assoc()) {
      $snum = $fetch['SideId'];
      echo "<tr><td><a href=AddDance.php?sidenum=$snum>" . $fetch['SName'] . "</a>";
      if ($fetch['SideStatus']) {
	echo "<td>DEAD";
      } else {
	$ty = strtolower($fetch['Type']);
	$colour = '';
	foreach($Types as $T) {
	  if ($T['Colour'] == '') continue;
	  $lct = "/" . strtolower($T['SName']) . "/";
	  if (preg_match($lct,$ty)) {
	    $colour = $T['Colour'];
	    break;
	  }
	}
        if ($colour) {
	  echo "<td style='background:$colour;'>" . $fetch['Type'];
        } else {
	  echo "<td>" . $fetch['Type'];
	}
      }
      if ($Contact) echo "<td>" . $fetch['Contact'];
      if ($Loc) echo "<td>" . $fetch['Location'];

      echo "<td>";
        if (strlen($fetch['Website'])>6) echo weblink($fetch['Website'],'Web','target=_blank');
      echo "<td>" . linkemailhtml($fetch,'Side','','ReportTed(event)');
      echo "<td>";
      if (isset($fetch['LyInvite'])) echo $Invite_States[$fetch['LyInvite']];
      echo "<td>";
      if (isset($fetch['LyComing'])) echo $Coming_States[$fetch['LyComing']] . "\n";

      echo "<td>" . fm_select2($Invite_States,$fetch['Invite'],"Invite$snum",0,"id=Invite$snum onchange=ChangeInvite(event)");

      echo "<td>";
      echo "<button type=button id=Ted$snum onclick=ReportTed(event)>Y</button><span id=Vited$snum>";
      if (isset($fetch['Invited'])) echo $fetch['Invited'];
      echo "</span>";
      echo "<td>";
      if (isset($fetch['Coming'])) echo $Coming_States[$fetch['Coming']] . "\n";

//      for($i=1;$i<5;$i++) {
//        echo "<td>" . ($fetch["SentEmail$i"]?"Y":"");
//      }
    }
    echo "</tbody></table>\n";
  }
  
?>
  
</div>

<?php include("files/footer.php"); ?>
</body>
</html>
