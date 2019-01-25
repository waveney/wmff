<?php
  include_once("fest.php");
  A_Check('Steward');

  dostaffhead("List Dance", "/js/clipboard.min.js","/js/emailclick.js" );
  global $YEAR,$PLANYEAR,$Dance_Comp,$Dance_Comp_Colours;
  include_once("DanceLib.php"); 
  echo "<h2>List Dance Sides $YEAR</h2>\n";

  echo "Click on column header to sort by column.  Click on Side's name for more detail and programme when available,<p>\n";

  echo "If you click on the email link, press control-V afterwards to paste the standard link into message.<p>";
  $col9 = $col8 = $col7 = '';
  $Types = Get_Dance_Types(1);
  foreach ($Types as $i=>$ty) $Colour[strtolower($ty['SN'])] = $ty['Colour'];
  
  $link = (Access('Staff')?'AddPerf.php':'AddPerf.php');
  $LastYear = $PLANYEAR-1;

  if ($_GET{'SEL'} == 'ALL') {
    $SideQ = $db->query("SELECT s.*, y.*, s.SideId FROM Sides AS s LEFT JOIN SideYear as y ON s.SideId=y.SideId AND y.year=$YEAR WHERE s.IsASide=1 ORDER BY SN");
    $col5 = "Invite";
    $col6 = "Coming";
    $col7 = "Wshp";
    if (Feature('DanceComp')) $col9 = "Dance Comp";
  } else if ($_GET{'SEL'} == 'INV') {

    $flds = "s.*, ly.Invite, ly.Coming, y.Invite, y.Invited, y.Coming";
    $SideQ = $db->query("SELECT $flds FROM Sides AS s LEFT JOIN SideYear as y ON s.SideId=y.SideId AND y.year=$PLANYEAR " .
                        "LEFT JOIN SideYear as ly ON s.SideId=ly.SideId AND ly.year=$LastYear WHERE s.IsASide=1 AND s.SideStatus=0 ORDER BY SN");
    $col5 = "Invited $LastYear";
    $col6 = "Coming $LastYear";
    $col7 = "Invite $PLANYEAR";
    $col8 = "Invited $PLANYEAR";
    $col9 = "Coming $PLANYEAR";
  } else if ($_GET{'SEL'} == 'Coming') {
    $SideQ = $db->query("SELECT s.*, y.* FROM Sides AS s, SideYear as y WHERE s.IsASide=1 AND s.SideId=y.SideId AND y.year=$YEAR AND y.Coming=" . 
                $Coming_Type['Y'] . " ORDER BY SN");
    $col5 = "Fri";
    $col6 = "Sat";
    $col7 = "Sun";
    $col8 = "Complete?";
    if (Feature('DanceComp')) $col9 = "Dance Comp";
    if (Access('Staff','Dance')) $col10 = "Proforma Emails";
    $Comp = $stot = 0;
  } else { // general public list
    $flds = "s.*, y.Sat, y.Sun";
    $SideQ = $db->query("SELECT $flds FROM Sides AS s, SideYear as y WHERE s.IsASide=1 AND s.SideId=y.SideId AND y.year=$YEAR AND y.Coming=" . 
                $Coming_Type['Y'] . " ORDER BY SN");
    $col5 = "Fri";
    $col6 = "Sat";
    $col7 = "Sun";
  }

  if (!$SideQ || $SideQ->num_rows==0) {
    echo "<h2>No Sides Found</h2>\n";
  } else {
    $coln = ($col10?1:0); // Start at 1 for select col
    echo "<table id=indextable border width=100%>\n";
    echo "<thead><tr>";
    if ($col10) echo "<th><input type=checkbox name=SelectAll id=SelectAll onchange=ToolSelectAll(event)>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Type</a>\n";
    if ($_GET{'SEL'}) {
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Contact</a>\n";
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
//      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Link</a>\n";
    }
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col5</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col6</a>\n";
    if ($col7) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col7</a>\n";
    if ($col8) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col8</a>\n";
    if ($col9) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col9</a>\n";
    if ($col10) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col10</a>\n";
//    for($i=1;$i<5;$i++) {
//      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>EM$i</a>\n";
//    }

    echo "</thead><tbody>";

    $Dance_Comp[0] = '';
    while ($fetch = $SideQ->fetch_assoc()) {
      echo "<tr>";
      echo "<td><input type=checkbox name=E$i class=SelectAllAble>";
      echo "<td><a href=$link?sidenum=" . $fetch['SideId'] . "&Y=$YEAR>" . $fetch['SN'] . "</a>";
      if ($fetch['SideStatus']) {
        echo "<td>DEAD";
      } else {
        $ty = strtolower($fetch['Type']);
        $colour = '';
        foreach($Types as $T) {
          if ($T['Colour'] == '') continue;
          $lct = "/" . strtolower($T['SN']) . "/";
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
      if ($_GET{'SEL'}) {
        echo "<td>" . $fetch['Contact'];
        echo "<td>" . linkemailhtml($fetch,'Side',(!$fetch['Email'] && $fetch['AltEmail']? 'Alt' : '' ));
      } 
      if ($col5 == "Invite") {
        echo "<td>";
        if (isset($fetch['Invite'])) echo $Invite_States[$fetch['Invite']];
        if (isset($fetch['Coming'])) {
          echo "<td style='background:" . $Coming_Colours[$fetch['Coming']] . "'>";
          echo $Coming_States[$fetch['Coming']] . "\n";
        } else {
          echo "<td>";
        }
      } else {
        $fri = "";
        if ($fetch['Fri']) $fri= "y";
        $sat = "";
        if ($fetch['Sat']) $sat= "y";
        $sun = "";
        if ($fetch['Sun']) $sun= "y";
        echo "<td>$fri<td>$sat<td>$sun\n";
      }
      if ($col7 == 'Wshp') {
        echo "<td>";
        if ($fetch['Workshops']) echo "Y";
      }
      if ($col8 == "Complete?") {
        $stot++;
        echo "<td>";
        if ($fetch['Insurance'] && $fetch['Mobile'] &&
                ((($fetch['Performers'] > 0) && $fetch['Address']) || ($fetch['Performers'] < 0)) && 
                ($fetch['Sat'] || $fetch['Sun'])) { 
          echo "Yes"; 
          $Comp++;
        } else {
          if ($fetch['Insurance']) echo "I"; 
          if ($fetch['Performers'] != 0) echo "P"; 
          if ($fetch['Address']) echo "A"; 
          if ($fetch['Mobile']) echo "M"; 
          if (!$fetch['Sat'] && !$fetch['Sun'] ) echo "?"; 
        }
        if ($fetch['Insurance'] == 1) echo " (Check)";
      }
      if ($col9 == 'Dance Comp') {
        if (!isset($fetch['DanceComp'])) $fetch['DanceComp'] = 0;
        echo "<td style='background:" . $Dance_Comp_Colours[$fetch['DanceComp']] . "'>" . $Dance_Comp[$fetch['DanceComp']] ;
      }

      if ($col10 == "Proforma Emails") {
        echo "<td>"; // None YET
      }

      
//      for($i=1;$i<5;$i++) {
//        echo "<td>" . ($fetch["SentEmail$i"]?"Y":"");
//      }
    }
    echo "</tbody></table>\n";
    
    if ($col10) {
      $Dtypes = Get_Dance_Types(0);
      echo "<div id=SelTools data-t1=Tool_Type,2 data-t2=Tool_Invite,8 data-t3=Tool_Coming,10 data-t4=Tool_Coming_Last,7></div>"; // Encode all tools below selectname,col to test
      echo "<b>Select: Type=" . fm_select($Dtypes,$_POST,'Tool_Type',1,' oninput=ToolSelect(event)') ;
      echo " Invite=" . fm_select($Invite_States,$_POST,'Tool_Invite',1,' oninput=ToolSelect(event)') ;    
      echo " Coming $PLANYEAR=" . fm_select($Coming_States,$_POST,'Tool_Coming',1,' oninput=ToolSelect(event)') ;    
      echo " Coming $LastYear=" . fm_select($Coming_States,$_POST,'Tool_Coming_Last',1,' oninput=ToolSelect(event)') . "</b><p>";    
//      echo " Day=" . fm_select($Coming_States,$_POST,'Tool_Coming',1,' oninput=ToolSelect(event)') . "</b><p>";    
    }
    
    if ($col8 == "Complete?") {
      echo "Complete: $Comp / $stot<br>\n";
    }
  }
  
  dotail();
?>
