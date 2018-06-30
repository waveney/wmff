<?php
  include_once("fest.php");
  A_Check('Steward');

  dostaffhead("List Traders", "/js/clipboard.min.js", "/js/emailclick.js");

  global $YEAR,$THISYEAR,$Trade_States,$Trade_StateClasses,$Trade_State,$TS_Actions,$ButExtra;
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
  $TradeLocs = Get_Trade_Locs();
  $TrMon = $TrRec = $TrSub = $TrState = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

  if (isset($_GET['ACTION'])) {
    $Tid = $_GET['id'];
    $Trady = Get_Trade_Year($Tid,$YEAR);
    $Trad = Get_Trader($Tid);
    Trade_Action($_GET{'ACTION'},$Trad,$Trady,1);
  }

  if (isset($_GET{'INC'})) {
    if (!$Sum) echo "<h2><a href=ListCTrade.php?Y=$YEAR>Exclude Declined/Cancelled/Not Submitted</a></h2>";
    $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Tid = y.Tid AND y.Year=$YEAR ORDER BY SName";
  } else {  
    if (!$Sum) echo "<h2><a href=ListCTrade.php?Y=$YEAR&INC=1>Include Refunded/Cancelled/Not Submitted</a></h2>";
    $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Tid = y.Tid AND y.Year=$YEAR AND y.BookingState>=" . $Trade_State['Submitted'] .
                " ORDER BY SName";
  }

  $res = $db->query($qry);
  $totfee = $totrec = $totsub = 0;

  $Acts = Access('Committee','Stalls');
  if (!$res || $res->num_rows==0) {
    echo "<h2>No Traders Found</h2>\n";
  } else {
    $str = '';
    $coln = 0;
    $str .= "<table id=indextable border>\n";
    $str .= "<thead><tr>";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Type</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Goods</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Contact</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>State</a>\n";
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
        if ($Acts) $str .= "<a href=Trade.php?id=$Tid>";
        $str .= ($fetch['SName']?$fetch['SName']:'No Name Given');
        if ($Acts) $str .= "</a>";
      $str .= "<td style='background:" . $Trade_Types[$fetch['TradeType']]['Colour'] . ";'>" . $Trade_Types[$fetch['TradeType']]['SName'];
      $str .= "<td width=300>" . $fetch['GoodsDesc'];
      $str .= "<td>" . $fetch['Contact'];
      $str .= "<td>" . linkemailhtml($fetch,'Trade');
      $str .= "<td id=TR$Tid";
        $stat = $fetch['BookingState'];
        if ($stat == $Trade_State['Fully Paid'] && ($fetch['Insurance'] == 0 || $fetch['RiskAssessment'] == 0)) {
          $str .= " class=TSNoInsRA>";
        } else {
          $str .= " class=" . $Trade_StateClasses[$stat] . ">";
        }
        $TrState[$stat]++;

        $Act = $TS_Actions[$stat];
        if ($Acts && $Act ) {
          $Acts = preg_split('/,/',$Act); 
          $str .= "<form>" . fm_Hidden('id',$Tid);
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
            $str .= "<button class=floatright name=ACTION value='$ac' type=submit " . (isset($ButExtra[$ac])?$ButExtra[$ac]:"") . " >$ac</button>";
          }
          $str .= "</form>";
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
          else { $str .= $fee; };
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
               $str .= NoBreak($TradeLocs[$fetch["PitchLoc$i"]]);
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
        if ($stat >= $Trade_State['Submitted'] && $stat != $Trade_State['Quoted'] && $stat != $Trade_State['Wait List']) {
          $TrSub[$fetch['TradeType']] += $fee;
          $totsub += $fee;
        }
      }
    }
    $str .= "</tbody></table>\n";
  }

  if (!$Sum) echo $str;

  echo "<p><table border id=narrowtable><tr><td>Type<td>Received<td>Total Accept<td>Total inc Quoted<td>Details\n";
  foreach ($Trade_Types as $t) {
    if (isset($TrMon[$t['id']]) && $TrMon[$t['id']]) {
      echo "<tr><td style='background:" . $t['Colour'] . ";'>" . $t['SName'] ;
      echo "<td>&pound;" . $TrRec[$t['id']] . "<td>&pound;" . $TrSub[$t['id']] . "<td>&pound;" . $TrMon[$t['id']];
      echo "<td><a href=ListDTrade.php?t=" . $t['id'] . ">Details</a>\n";
    }
  }
  echo "<tr><td>Total Fees<td>&pound;$totrec<td>&pound;$totsub<td>&pound;$totfee<td>\n";
  echo "</table>\n";
  echo "<table border id=narrowtable><tr><td>State<td>Number\n";
  foreach ($Trade_States as $i=>$state) {
    if (isset($TrState[$i]) && $TrState[$i]>0) echo "<tr><td class=" . $Trade_StateClasses[$i] . ">$state<td>" . $TrState[$i];
  }
  echo "</table><p>";
  dotail();
?>

