<?php
  include_once("fest.php");
  A_Check('Steward');

  dostaffhead("List Traders", ["/js/clipboard.min.js", "/js/emailclick.js"]);

  global $YEAR,$PLANYEAR,$Trade_States,$Trade_State_Colours,$Trade_State,$TS_Actions,$ButExtra,$TradeLocData;
  include_once("TradeLib.php");

  $Sum = isset($_GET['SUM']);
  if ($Sum) {
    echo "<h2>Traders Summary $YEAR</h2>\n";
  } else {
    echo "<div class=content><h2>List Traders $YEAR</h2>\n";

    echo "Click on column header to sort by column.  Click on Traders's name for more detail.<p>";

    echo "If you accidentally click the wrong button or want to undo an action, click on the Trader's name to enable all state changes.<p>\n";

    echo "If the amount paid is not the full deposit or remainder, click on the Trader's name to enable fine control.<p>\n";

    echo "If you click on the email link, press control-V afterwards to paste the standard link into message.<p>";

    echo "The Resend action re-sends the last email to the trader (or a general status message if it does not make sense to resend).<p>";
  }

  $Trade_Types = Get_Trade_Types(1);
  $TrMon = $TrRec = $TrSub = $TrState = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
  foreach ($TradeLocData as $i=>$TLoc) {
    $TradeLocData[$i]['ReceiveTot'] = $TradeLocData[$i]['AcceptTot'] = $TradeLocData[$i]['QuoteTot'] = 0;
  }
  $TradeLocData[0]['ReceiveTot'] = $TradeLocData[0]['AcceptTot'] = $TradeLocData[0]['QuoteTot'] = 0;
  $TradeLocData[0]['SN'] = 'HOMELESS';
  $TradeLocData[0]['TLocId'] = 0;
  $TotLRec = $TotLAcc = $TotLQut = 0;

  if (isset($_GET['ACTION'])) {
    $Tid = $_GET['id'];
    $Trady = Get_Trade_Year($Tid,$YEAR);
    $Trad = Get_Trader($Tid);
    Trade_Action($_GET{'ACTION'},$Trad,$Trady,1);
  }

  if (isset($_GET{'INC'})) {
    if (!$Sum) echo "<h2><a href=ListCTrade.php?Y=$YEAR>Exclude Declined/Cancelled/Not Submitted</a>, " .
      "<a href=ListCTrade.php?Y=$YEAR&SUB>Include Submitted</a>, " .
      "<a href=ListCTrade.php?Y=$YEAR&ONLY>Only Submitted</a>, </h2>";
    $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Status!=2 AND t.Tid = y.Tid AND y.Year=$YEAR ORDER BY SN";
  } else if (isset($_GET['SUB'])) { 
    if (!$Sum) echo "<h2><a href=ListCTrade.php?Y=$YEAR&INC>Show All</a>, <a href=ListCTrade.php?Y=$YEAR>Exclude Declined/Cancelled/Submitted</a>, " .
      "<a href=ListCTrade.php?Y=$YEAR&ONLY>Only Submitted</a> </h2>";
    $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Status!=2 AND t.Tid = y.Tid AND y.Year=$YEAR AND y.BookingState>=" . $Trade_State['Submitted'] .
           " ORDER BY SN";  
  } else if (isset($_GET['ONLY'])) { 
    if (!$Sum) echo "<h2><a href=ListCTrade.php?Y=$YEAR&INC>Show All</a>, <a href=ListCTrade.php?Y=$YEAR>Exclude Declined/Cancelled/Submitted</a> </h2>";
    $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Status!=2 AND t.Tid = y.Tid AND y.Year=$YEAR AND y.BookingState=" . $Trade_State['Submitted'] .
           " ORDER BY SN";  
  } else {  
    if (!$Sum) echo "<h2><a href=ListCTrade.php?Y=$YEAR&INC>Show All</a>, <a href=ListCTrade.php?Y=$YEAR&SUB>Include Submitted</a>, " .
      "<a href=ListCTrade.php?Y=$YEAR&ONLY>Only Submitted</a> </h2>";
    $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Status!=2 AND t.Tid = y.Tid AND y.Year=$YEAR AND y.BookingState>" . $Trade_State['Submitted'] . 
      " ORDER BY SN";
  }

  $res = $db->query($qry);
  $totfee = $totrec = $totsub = 0;

  $ActsEnable = Access('Committee','Trade');
  if (!$res || $res->num_rows==0) {
    echo "<h2>No Traders Found</h2>\n";
  } else {
    $str = '';
    $coln = 0;
    $str .= "<div class=tablecont><table id=indextable border>\n";
    $str .= "<thead><tr>";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Type</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Goods</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Contact</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
    $str .= "<th style='min-width:350'><a href=javascript:SortTable(" . $coln++ . ",'T')>State</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Fee</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Sa</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Su</a>\n";
//    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Ref</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Pitches</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Power</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Location</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Ins</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Risk</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Local Auth</a>\n";
    $str .= "</thead><tbody>";
    while ($fetch = $res->fetch_assoc()) {
      $Tid = $fetch['Tid'];
      $str .= "<tr><td>";
        if ($ActsEnable) $str .= "<a href=Trade.php?id=$Tid>";
        $str .= ($fetch['SN']?$fetch['SN']:'No Name Given');
        if ($ActsEnable) $str .= "</a>";
      $str .= "<td style='background:" . $Trade_Types[$fetch['TradeType']]['Colour'] . ";'>" . $Trade_Types[$fetch['TradeType']]['SN'];
      $str .= "<td width=300>" . $fetch['GoodsDesc'];
      $str .= "<td>" . $fetch['Contact'];
      $str .= "<td>" . linkemailhtml($fetch,'Trade');
      $str .= "<td id=TR$Tid";
        $stat = $fetch['BookingState'];
        if ($stat == $Trade_State['Fully Paid'] && ($fetch['Insurance'] == 0 || $fetch['RiskAssessment'] == 0)) {
          $str .= " class=TSNoInsRA>";
        } else {
          $str .= " style='background:" . $Trade_State_Colours[$stat] . ";padding:4; white-space: nowrap;'>";
        }
        $TrState[$stat]++;

        $Act = $TS_Actions[$stat];
        if ($ActsEnable && $Act ) {
          $Acts = preg_split('/,/',$Act); 
          $str .= "<div class=floatright style='max-width:250'><form>" . fm_Hidden('id',$Tid);
          $butcount = 0;
          foreach($Acts as $ac) {
            switch ($ac) {
              case 'Quote':
                if ($fetch['Fee'] == 0) continue 2;
                break;
              case 'Accept':
                if ($fetch['Fee'] == 0) continue 2;
                break;
              case 'Paid':
                if ($fetch['Fee'] == 0) continue 2;
                break;
              default:
              }
            if ($butcount++ == 4) $str .= "<br>";
            $str .= "<button name=ACTION value='$ac' type=submit " . (isset($ButExtra[$ac])?$ButExtra[$ac]:"") . " >$ac</button>";
          }
          $str .= "</form></div>";
        }
        if ($stat == $Trade_State['Fully Paid'] && ($fetch['Insurance'] == 0 || $fetch['RiskAssessment'] == 0)) {
          $str .= "Paid";
          if ($fetch['Insurance'] ==0) $str .= ", no Insurance";
          if ($fetch['RiskAssessment'] ==0) $str .= ", no Risk Assess";
        } else {
          $str .= $Trade_States[$stat];
        }
      $str .= "<td>";
        $Dep = T_Deposit($fetch);
        $fee = $fetch['Fee'];
        if ($Dep == 0) {
          if ($fee < 0) { $str .= "Free"; }
          else if ($fee == 0) { $str .= "?"; }
          else { $str .= "0<br>$fee<br>$fee"; };
        } else {
          if ($fee < 0) { $str .= "D:$Dep<br>B:0<br>T:0"; }
          else if ($fee == 0) { $str .= "D:$Dep<br>B:?<br>T:?"; }
          else { $str .= "D:$Dep<br>B:" . ($fee - $Dep) . "<br>T:$fee"; }
        }
      $str .= "<td>";
        if ($fetch['Days'] ==0) {
          $str .= "Y<td>Y";
        } elseif ($fetch['Days'] == 1) { 
          $str .= "Y<td>";
        } else {
          $str .= "<td>Y";
        }
//      $str .= "<td>" . (1000000+$fetch['TYid']);
      $str .= "<td>" . $fetch['PitchSize0'];
        if ($fetch['PitchSize1']) $str .= "<br>" . $fetch['PitchSize1'];
        if ($fetch['PitchSize2']) $str .= "<br>" . $fetch['PitchSize2'];
      $str .= "<td>" . $fetch['Power0'];
        if ($fetch['PitchSize1']) {
          $str .= "<br>";
          $str .= $fetch['Power1'];
        }
        if ($fetch['PitchSize2']) {
          $str .= "<br>";
          $str .= $fetch['Power2'];
        }
      $str .= "<td>";
        for ($i = 0; $i<3; $i++) {
          if ($fetch["PitchSize$i"]) {
            if ($i) $str .= "<br>";
            if ($fetch["PitchLoc$i"]) {
               $str .= NoBreak($TradeLocData[$fetch["PitchLoc$i"]]['SN']);
              if ($fetch["PitchNum$i"]) $str .= "&nbsp;" . $fetch["PitchNum$i"];
            } else {
              $str .= "&nbsp;";
            }
          }
        }
      $inscols= array('red','yellow','lime');
      $str .= "<td style='background:" . ($inscols[$fetch['Insurance']]) . ";'>" . ($fetch['Insurance']?"Y":'');
//      $str .= "<td style='background:" . ($fetch['Insurance']?'lime':'red') .";'>" . ($fetch['Insurance']?"Y":'');
      $str .= "<td>" . ($fetch['RiskAssessment']?"Y":'');

      if ($Trade_Types[$fetch['TradeType']]['NeedPublicHealth']) {
        if ($fetch['PublicHealth']) {
            if ($fetch['BookingState'] >= $Trade_State['Submitted']) {
            $str .= "<td"  . ($fetch['HealthChecked']?'':' style="background:red;"') . ">";
              $str .= "<form>" . fm_Hidden('id',$Tid);
            if ($Acts && !$fetch['HealthChecked']) $str .= "<button class=floatright name=ACTION value=Checked type=submit >Checked</button>";
            $str .= "</form>";
          } else {
            $str .= "<td>";
          }
          $str .= $fetch['PublicHealth'];
        } else {
          $str .= "<td style='background:red;'><b>MISSING</b>";
        }
      } else {
        $str .= "<td>";
      }
      if ($fee > 0) {
        $totfee += $fee;
        $totrec += $fetch['TotalPaid'];
        if (!isset($TrMon[$fetch['TradeType']])) {
          $TrMon[$fetch['TradeType']] = $fee;
        } else {
          $TrMon[$fetch['TradeType']] += $fee;
        }
        $TrRec[$fetch['TradeType']] += $fetch['TotalPaid'];
        if ($stat >= $Trade_State['Submitted'] && $stat != $Trade_State['Quoted'] && $stat != $Trade_State['Wait List'] && $stat != $Trade_State['Requote']) {
          $TrSub[$fetch['TradeType']] += $fee;
          $totsub += $fee;
        }
        $pitches = 0;
        for ($i = 0; $i<3; $i++) if ($fetch["PitchLoc$i"]) $pitches++;
        if ($pitches) {
          for ($i = 0; $i < 3; $i++) {
            if ($fetch["PitchLoc$i"]) {
              if ($stat >= $Trade_State['Submitted'] && $stat != $Trade_State['Quoted'] && $stat != $Trade_State['Wait List']  && $stat != $Trade_State['Requote']) {
                $TradeLocData[$fetch["PitchLoc$i"]]['AcceptTot'] += $fee/$pitches;
              }
              $TradeLocData[$fetch["PitchLoc$i"]]['QuoteTot'] += $fee/$pitches;
              $TradeLocData[$fetch["PitchLoc$i"]]['ReceiveTot'] += $fetch['TotalPaid']/$pitches;
            }
          }
        } else if ($fee) {
          if ($stat >= $Trade_State['Submitted'] && $stat != $Trade_State['Quoted'] && $stat != $Trade_State['Wait List']) $TradeLocData[0]['AcceptTot'] += $fee;
          $TradeLocData[0]['QuoteTot'] += $fee;
          $TradeLocData[0]['ReceiveTot'] += $fetch['TotalPaid'];
        }          
      }
    }
    $str .= "</tbody></table></div>\n";
  }

  if (!$Sum && isset($str)) echo $str;

  if (!isset($_GET['ONLY'])) {
    echo "<p><div class=tablecont><table border id=narrowtable><tr><td>Type<td>Received<td>Total Accept<td>Total inc Quoted<td>Details\n";
    foreach ($Trade_Types as $t) {
      if (isset($TrMon[$t['id']]) && $TrMon[$t['id']]) {
        echo "<tr><td style='background:" . $t['Colour'] . ";'>" . $t['SN'] ;
        echo "<td>" . Print_Pound($TrRec[$t['id']]) . "<td>" . Print_Pound($TrSub[$t['id']]) . "<td>" . Print_Pound($TrMon[$t['id']]);
        echo "<td><a href=ListDTrade.php?t=" . $t['id'] . ">Details</a>\n";
      }
    }
    echo "<tr><td>Total Fees<td>" . Print_Pound($totrec) . "<td>" . Print_Pound($totsub) . "<td>" . Print_Pound($totfee) . "<td>\n";
    echo "</table></div><br>";
    echo "<div class=tablecont><table border id=narrowtable><tr><td>Location<td>Received<td>Total Accept<td>Total inc Quoted<td>Details\n";
    foreach ($TradeLocData as $TLoc) {
      if (!isset($TLoc['QuoteTot']) || $TLoc['QuoteTot'] == 0) continue;
      echo "<tr><td>" . $TLoc['SN'];
      echo "<td>" . Print_Pound($TLoc['ReceiveTot']) . "<td>" . Print_Pound($TLoc['AcceptTot']) . "<td>" . Print_Pound($TLoc['QuoteTot']);
      echo "<td><a href=ListDTrade.php?l=" . $TLoc['TLocId'] . ">Details</a>\n";
      $TotLRec += $TLoc['ReceiveTot'];
      $TotLAcc += $TLoc['AcceptTot'];
      $TotLQut += $TLoc['QuoteTot'];
      }
    
    echo "<tr><td>Total Fees<td>" . Print_Pound($TotLRec) . "<td>" . Print_Pound($TotLAcc) . "<td>" . Print_Pound($TotLQut) . "<td>\n";

    echo "</table></div><br>\n";
    echo "<div class=tablecont><table border id=narrowtable><tr><td>State<td>Number\n";
    foreach ($Trade_States as $i=>$state) {
      if (isset($TrState[$i]) && $TrState[$i]>0) echo "<tr><td style='background:" . $Trade_State_Colours[$i] . ";padding:4; white-space: nowrap;'>$state<td>" .
         $TrState[$i];
    }
    echo "</table></div>";
    echo "<p>";
  }
  dotail();
?>

