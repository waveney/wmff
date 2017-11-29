<?php
  include_once("fest.php");
  A_Check('Steward');
?>

<html>
<head>
<title>WMFF Staff | List Traders</title>
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
  echo "<div class=content><h2>List Traders $YEAR</h2>\n";

  echo "Click on column header to sort by column.  Click on Traders's name for more detail.<p>";

  echo "If you accidentally click the wrong button or want to undo an action, click on the Trader's name to enable all state changes.<p>\n";

  echo "If the amount paid is not the full deposit or remainder, click on the Trader's name to enable fine control.<p>\n";

  echo "If you click on the email link, press control-V afterwards to paste the standard link into message.<p>";

  $Trade_Types = Get_Trade_Types(1);
  $TradeLocs = Get_Trade_Locs();
  $TrMon = array();
  $TrRec = array();
  $TrSub = array();
  $TrState = array();

  if (isset($_GET['ACTION'])) {
    $Tid = $_GET['id'];
    $Trady = Get_Trade_Year($Tid,$YEAR);
    $Trad = Get_Trader($Tid);
    Trade_Action($_GET{'ACTION'},$Trad,$Trady,1);
  }

  if (isset($_GET{'INC'})) {
    echo "<h2><a href=ListCTrade.php?Y=$YEAR>Exclude Declined/Cancelled/Not Submitted</a></h2>";
    $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Tid = y.Tid AND y.Year=$YEAR ORDER BY Name";
  } else {  
    echo "<h2><a href=ListCTrade.php?Y=$YEAR&INC=1>Include Refunded/Cancelled/Not Submitted</a></h2>";
    $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Tid = y.Tid AND y.Year=$YEAR AND y.BookingState>=" . $Trade_State['Submitted'] .
		" ORDER BY Name";
  }

  $res = $db->query($qry);
  $totfee = $totrec = $totsub = 0;

  $Acts = Access('Committee','Stalls');
  if (!$res || $res->num_rows==0) {
    echo "<h2>No Traders Found</h2>\n";
  } else {
    $coln = 0;
    echo "<table id=indextable border>\n";
    echo "<thead><tr>";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Type</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Goods</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Contact</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>State</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Fee</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Sa</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Su</a>\n";
//    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Ref</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Pitches</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Power</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Location</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Ins</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Risk</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Local Auth</a>\n";
    echo "</thead><tbody>";
    while ($fetch = $res->fetch_assoc()) {
      $Tid = $fetch['Tid'];
      echo "<tr><td>";
        if ($Acts) echo "<a href=Trade.php?id=$Tid>";
        echo ($fetch['Name']?$fetch['Name']:'No Name Given');
        if ($Acts) echo "</a>";
      echo "<td style='background:" . $Trade_Types[$fetch['TradeType']]['Colour'] . ";'>" . $Trade_Types[$fetch['TradeType']]['Name'];
      echo "<td width=300>" . $fetch['GoodsDesc'];
      echo "<td>" . $fetch['Contact'];
      echo "<td>" . linkemailhtml($fetch,'Trade');
      echo "<td id=TR$Tid";
        $stat = $fetch['BookingState'];
        if ($stat == $Trade_State['Fully Paid'] && ($fetch['Insurance'] == 0 || $fetch['RiskAssessment'] == 0)) {
          echo " class=TSNoInsRA>";
	} else {
	  echo " class=" . $Trade_StateClasses[$stat] . ">";
	}
        $TrState[$stat]++;

	$Act = $TS_Actions[$stat];
	if ($Acts && $Act ) {
	  $Acts = preg_split('/,/',$Act); 
	  echo "<form>" . fm_Hidden('id',$Tid);
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
	    echo "<button class=floatright name=ACTION value='$ac' type=submit " . $ButExtra[$ac] . " >$ac</button>";
	  }
	  echo "</form>";
	}
        if ($stat == $Trade_State['Fully Paid'] && ($fetch['Insurance'] == 0 || $fetch['RiskAssessment'] == 0)) {
	  echo "Paid";
          if ($fetch['Insurance'] ==0) echo ", no Insurance";
          if ($fetch['RiskAssessment'] ==0) echo ", no Risk Assess";
	} else {
	  echo $Trade_States[$stat];
        }
      echo "<td>";
        $Dep = T_Deposit($fetch);
	$fee = $fetch['Fee'];
	if ($Dep == 0) {
	  if ($fee < 0) { echo "Free"; }
	  else if ($fee == 0) { echo "?"; }
	  else { echo $fee; };
	} else {
	  if ($fee < 0) { echo "D:$Dep<br>B:0<br>T:0"; }
	  else if ($fee == 0) { echo "D:$Dep<br>B:?<br>T:?"; }
	  else { echo "D:$Dep<br>B:" . ($fee - $Dep) . "<br>T:$fee"; }
	}
      echo "<td>";
	if ($fetch['Days'] ==0) {
	  echo "Y<td>Y";
	} elseif ($fetch['Days'] == 1) { 
	  echo "Y<td>";
	} else {
	  echo "<td>Y";
	}
//      echo "<td>" . (1000000+$fetch['TYid']);
      echo "<td>" . $fetch['PitchSize0'];
        if ($fetch['PitchSize1']) echo "<br>" . $fetch['PitchSize1'];
        if ($fetch['PitchSize2']) echo "<br>" . $fetch['PitchSize2'];
      echo "<td>" . $fetch['Power0'];
        if ($fetch['PitchSize1']) {
	  echo "<br>";
	  echo $fetch['Power1'];
        }
        if ($fetch['PitchSize2']) {
	  echo "<br>";
	  echo $fetch['Power2'];
        }
      echo "<td>";
	for ($i = 0; $i<3; $i++) {
	  if ($fetch["PitchSize$i"]) {
	    if ($i) echo "<br>";
            if ($fetch["PitchLoc$i"]) {
 	      echo NoBreak($TradeLocs[$fetch["PitchLoc$i"]]);
              if ($fetch["PitchNum$i"]) echo "&nbsp;" . $fetch["PitchNum$i"];
	    } else {
	      echo "&nbsp;";
	    }
	  }
	}
      $inscols= array('red','yellow','lime');
      echo "<td style='background:" . ($inscols[$fetch['Insurance']]) . ";'>" . ($fetch['Insurance']?"Y":'');
//      echo "<td style='background:" . ($fetch['Insurance']?'lime':'red') .";'>" . ($fetch['Insurance']?"Y":'');
      echo "<td>" . ($fetch['RiskAssessment']?"Y":'');

      if ($Trade_Types[$fetch['TradeType']]['NeedPublicHealth']) {
        if ($fetch['PublicHealth']) {
  	  if ($fetch['BookingState'] >= $Trade_State['Submitted']) {
            echo "<td"  . ($fetch['HealthChecked']?'':' style="background:red;"') . ">";
  	    echo "<form>" . fm_Hidden('id',$Tid);
	    if ($Acts && !$fetch['HealthChecked']) echo "<button class=floatright name=ACTION value=Checked type=submit >Checked</button>";
	    echo "</form>";
	  } else {
	    echo "<td>";
	  }
	  echo $fetch['PublicHealth'];
        } else {
	  echo "<td style='background:red;'><b>MISSING</b>";
        }
      } else {
	echo "<td>";
      }
      if ($fee > 0) {
	$totfee += $fee;
	$totrec += $fetch['TotalPaid'];
	$TrMon[$fetch['TradeType']] += $fee;
	$TrRec[$fetch['TradeType']] += $fetch['TotalPaid'];
	if ($stat >= $Trade_State['Submitted'] && $stat != $Trade_State['Quoted'] && $stat != $Trade_State['Wait List']) {
	  $TrSub[$fetch['TradeType']] += $fee;
	  $totsub += $fee;
	}
      }
    }
    echo "</tbody></table>\n";
  }

  echo "<p><table border id=narrowtable><tr><td>Type<td>Received<td>Total Accept<td>Total inc Quoted\n";
  foreach ($Trade_Types as $t) {
    if ($TrMon[$t['id']]) {
      echo "<tr><td style='background:" . $t['Colour'] . ";'>" . $t['Name'] ;
      echo "<td>&pound;" . $TrRec[$t['id']] . "<td>&pound;" . $TrSub[$t['id']] . "<td>&pound;" . $TrMon[$t['id']] . "\n";
    }
  }
  echo "<tr><td>Total Fees<td>&pound;$totrec<td>&pound;$totsub<td>&pound;$totfee\n";
  echo "</table>\n";
  echo "<table border id=narrowtable><tr><td>State<td>Number\n";
  foreach ($Trade_States as $i=>$state) {
    if (isset($TrState[$i]) && $TrState[$i]>0) echo "<tr><td class=" . $Trade_StateClasses[$i] . ">$state<td>" . $TrState[$i];
  }
  echo "</table><p>";
  
?>
  
</div>

<?php include("files/footer.php"); ?>
</body>
</html>
