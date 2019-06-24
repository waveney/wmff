<?php
  include_once("fest.php");
  A_Check('Steward');

  dostaffhead("List Traders in Detail", ["/js/clipboard.min.js", "/js/emailclick.js"]);

  global $YEAR,$PLANYEAR,$Trade_States,$Trade_State_Colours,$Trade_State,$TS_Actions,$ButExtra;
  include_once("TradeLib.php");

  $Trade_Types = Get_Trade_Types(1);
  $TradeLocs = Get_Trade_Locs();
  $TrMon = array();
  $TrRec = array();
  $TrSub = array();
  $TrState = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
  $div = 0;

  if (isset($_REQUEST['t'])) {
    $Type = $_REQUEST['t'];

    $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Status!=2 AND t.Tid = y.Tid AND y.Year=$YEAR AND (y.BookingState=" . $Trade_State['Deposit Paid'] .
                " OR y.BookingState=" . $Trade_State['Invoiced'] . 
                " OR y.BookingState=" . $Trade_State['Fully Paid'] . " OR y.BookingState=" . $Trade_State['Accepted'] . ") AND t.TradeType=$Type ORDER BY SN";
  } else if (isset($_REQUEST['l']))  {
    $Loc = $_REQUEST['l'];
    $div = 1;
    
    if ($Loc) {
      $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Status!=2 AND t.Tid = y.Tid AND y.Year=$YEAR AND (y.BookingState=" . $Trade_State['Deposit Paid'] .
                " OR y.BookingState=" . $Trade_State['Invoiced'] . 
                " OR y.BookingState=" . $Trade_State['Quoted'] . 
                " OR y.BookingState=" . $Trade_State['Fully Paid'] . 
                " OR y.BookingState=" . $Trade_State['Accepted'] . ") AND " .
                "(y.PitchLoc0=$Loc OR y.PitchLoc1=$Loc OR y.PitchLoc2=$Loc ) ORDER BY SN";
    } else {
      $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Status!=2 AND t.Tid = y.Tid AND y.Year=$YEAR AND (y.BookingState=" . $Trade_State['Deposit Paid'] .
                " OR y.BookingState=" . $Trade_State['Invoiced'] . 
                " OR y.BookingState=" . $Trade_State['Quoted'] . 
                " OR y.BookingState=" . $Trade_State['Fully Paid'] .
                " OR y.BookingState=" . $Trade_State['Accepted'] . ") AND " .
                "(y.PitchLoc0=0 AND y.PitchLoc1=0 AND y.PitchLoc2=0 ) ORDER BY SN";    
    }
  
  } else Error_Page('Do Not Know What Details Are Needed');
  $ActsEnable = Access('Committee','Trade');
//echo "$qry<p>";
  $res = $db->query($qry);
  $totfee = $totdep = $totbal = $totrec = 0;

  if (!$res || $res->num_rows==0) {
    echo "<h2>No Traders Found</h2>\n";
  } else {
    $str = '';
    $coln = 0;
    $str .= "<div class=tablecont><table id=indextable border>\n";
    $str .= "<thead><tr>";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Type</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>State</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Fee</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Dep</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Dep Paid</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Dep Detail</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Bal</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Bal Paid</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Bal Detail</a>\n";
    $str .= "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Total Paid</a>\n";
    $str .= "</thead><tbody>";
    while ($fetch = $res->fetch_assoc()) {
      $Tid = $fetch['Tid'];
      $str .= "<tr><td>";
        $str .= "<a href=Trade?id=$Tid>";
        $str .= ($fetch['SN']?$fetch['SN']:'No Name Given');
        $str .= "</a>";
      $str .= "<td style='background:" . $Trade_Types[$fetch['TradeType']]['Colour'] . ";'>" . $Trade_Types[$fetch['TradeType']]['SN'];
      $str .= "<td id=TR$Tid";
        $stat = $fetch['BookingState'];
        if ($stat == $Trade_State['Fully Paid'] && ($fetch['Insurance'] == 0 || $fetch['RiskAssessment'] == 0)) {
          $str .= " class=TSNoInsRA>";
        } else {
          $str .= " style='background:" . $Trade_State_Colours[$stat] . ";padding:4; white-space: nowrap;'>";
        }
        $Act = $TS_Actions[$stat];
        if ($ActsEnable && $Act ) {
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
            $str .= "<button class=floatright name=ACTION value='$ac' type=submit " . $ButExtra[$ac] . " >$ac</button>";
          }
          $str .= "</form>";
        }
        $str .= $Trade_States[$stat];

      $Dep = T_Deposit($fetch);
      $fee = $ftxt = $fetch['Fee'];
      $tot = $fetch['TotalPaid'];
      if ($Dep == 0) {
        if ($fee < 0) { $ftxt = "Free"; $Bal = 0; }
        else { $Bal = $fee; };
      } else {
        if ($fee < 0) { $Bal = $fee = 0; }
        else { $Bal = max($fee - $Dep, $fee-$tot); }
      }
      $Hist = $fetch['History'];
      if (preg_match_all('/Action: *Dep Paid *of *(.*? on .*?) by /',$Hist,$mtchs)) {
        $DepDet = implode('<br>',$mtchs[1]);
      } else {
        $DepDet = '';
      }

      if (preg_match_all('/Action: *Paid *(.*? on .*?) by /',$Hist,$mtchs)) {
        $BalDet = implode('<br>',$mtchs[1]);
      } else {
        $BalDet = '';
      }

      $pitches = 0;
      for ($i = 0; $i<3; $i++) if ($fetch["PitchLoc$i"]) $pitches++;

      if ($div && $pitches>1) {
        $Dep /= $pitches;
        $fee /= $pitches;
        $tot /= $pitches;
      }

      $str .= "<td>" . Print_Pound($fee);
      $str .= "<td>" . Print_Pound($Dep);
      $str .= "<td>" . Print_Pound(($Dep <= $tot)?$Dep:$tot);
      
      $str .= "<td>" . Print_Pound($DepDet);
      $str .= "<td>" . Print_Pound($Bal);
      $str .= "<td>" . Print_Pound(($tot >= $Dep)?($tot-$Dep):0);
      $str .= "<td>" . Print_Pound($BalDet);

      $str .= "<td>" . Print_Pound($tot);

      $totfee += $fee;
      $totdep += (($Dep <= $tot)?$Dep:$tot);
      $totbal += (($tot >= $Dep)?($tot-$Dep):0);
      $totrec += $tot;
    }
    echo "$str\n";
    echo "<tr><td>Totals:<td><td><td>" . Print_Pound($totfee) . "<td><td>" . Print_Pound($totdep) .  "<td><td><td>" . Print_Pound($totbal) . "<td><td>" . Print_Pound($totrec) . "\n";
    echo "</table></div><p>";
  }
  dotail();
?>
