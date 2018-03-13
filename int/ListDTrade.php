<?php
  include_once("fest.php");
  A_Check('Steward');
?>

<html>
<head>
<title>WMFF Staff | List Traders in Detail</title>
<script src="/js/clipboard.min.js"></script>
<script src="/js/emailclick.js"></script>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
</head>
<body>
<?php 
  global $YEAR,$THISYEAR,$Trade_States,$Trade_StateClasses,$Trade_State,$TS_Actions,$ButExtra;
  include("files/navigation.php");
  include_once("TradeLib.php");

  $Trade_Types = Get_Trade_Types(1);
  $TradeLocs = Get_Trade_Locs();
  $TrMon = array();
  $TrRec = array();
  $TrSub = array();
  $TrState = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

  $Type = $_GET['t'];

  $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Tid = y.Tid AND y.Year=$YEAR AND (y.BookingState=" . $Trade_State['Deposit Paid'] .
		" OR y.BookingState=" . $Trade_State['Fully Paid'] . " ) AND t.TradeType=$Type ORDER BY SName";

//echo "$qry<p>";
  $res = $db->query($qry);
  $totdep = $totbal = $totrec = 0;

  if (!$res || $res->num_rows==0) {
    echo "<h2>No Traders Found</h2>\n";
  } else {
    $str = '';
    $coln = 0;
    $str .= "<table id=indextable border>\n";
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
        $str .= "<a href=Trade.php?id=$Tid>";
        $str .= ($fetch['SName']?$fetch['SName']:'No Name Given');
        $str .= "</a>";
      $str .= "<td style='background:" . $Trade_Types[$fetch['TradeType']]['Colour'] . ";'>" . $Trade_Types[$fetch['TradeType']]['SName'];
      $str .= "<td id=TR$Tid";
        $stat = $fetch['BookingState'];
        if ($stat == $Trade_State['Fully Paid'] && ($fetch['Insurance'] == 0 || $fetch['RiskAssessment'] == 0)) {
          $str .= " class=TSNoInsRA>";
	} else {
	  $str .= " class=" . $Trade_StateClasses[$stat] . ">";
	}
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

      $str .= "<td>$fee";
      $str .= "<td>$Dep";
      $str .= "<td>" . (($Dep <= $tot)?$Dep:$tot);
      
      $str .= "<td>$DepDet";
      $str .= "<td>$Bal";
      $str .= "<td>" . (($tot >= $Dep)?($tot-$Dep):0);
      $str .= "<td>$BalDet";

      $str .= "<td>$tot";

      $totdep += (($Dep <= $tot)?$Dep:$tot);
      $totbal += (($tot >= $Dep)?($tot-$Dep):0);
      $totrec += $tot;
    }
    echo "$str\n";
    echo "<tr><td>Totals:<td><td><td><td><td>$totdep<td><td><td>$totbal<td><td>$totrec\n";
    echo "</table><p>";
  }
  dotail();
?>
