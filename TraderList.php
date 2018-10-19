<?php
  include_once("int/fest.php");

  set_ShowYear();
  include_once("int/TradeLib.php");
  global $db,$YEAR,$SHOWYEAR,$PLANYEAR,$Trade_States,$Trade_State,$YEAR,$Trade_Days;

  dohead("Traders in $YEAR");

  if ($YEAR < $PLANYEAR) {
    echo "These traders where at the Folk Festival.<p>";
  } else {
    echo "These traders will be at the Folk Festival (many more to confirm).<p>";
  }
  echo "To become a trader see the <a href=/info/trade>trade info page</a>.  ";
  echo "Only those traders who have paid their deposits and have asked to be listed are shown here.<p>";

  $Locs = Get_Trade_Locs(1);
  $res = $db->query("SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE y.Year=$YEAR AND t.Tid=y.Tid AND ( y.BookingState=" . $Trade_State['Deposit Paid'] .
		" OR y.BookingState=" . $Trade_State['Fully Paid'] . ") AND t.ListMe=1 ORDER BY t.SN" );


  echo "<div id=flex>\n";
  if ($res) while ($trad = $res->fetch_assoc()) {
    echo "<div class=article>";
    if ($trad['Website']) echo weblinksimple($trad['Website']);
    echo "<h2 class=articlettl>" . $trad['SN'] . "</h2>";
    if ($trad['Photo']) echo "<img class=articleimg src=" . $trad['Photo'] . ">";
    if ($trad['Website']) echo "</a>";
    echo "<p class=articletxt>" . $trad['GoodsDesc'];
    echo ($YEAR > $PLANYEAR?"<p>Will be trading ":"<p>Was trading ") . $Prefixes[$Locs[$trad['PitchLoc0']]['prefix']] . ' ' . $Locs[$trad['PitchLoc0']]['SN'];
    if ($trad['PitchLoc2']) {
      echo ", " . $Prefixes[$Locs[$trad['PitchLoc1']]['prefix']] . ' ' . $Locs[$trad['PitchLoc1']]['SN'] . " and " 
		. $Prefixes[$Locs[$trad['PitchLoc1']]['prefix']] . ' ' . $Locs[$trad['PitchLoc2']]['SN'];
    } else if ($trad['PitchLoc1']) {
      echo " and " . $Prefixes[$Locs[$trad['PitchLoc1']]['prefix']] . ' ' . $Locs[$trad['PitchLoc1']]['SN'];
    }
    if ($trad['Days']) echo " on " . $Trade_Days[$trad['Days']];
    echo "<p>";
    echo "</div>";
  }

  if ($YEAR > 2018) {
    echo "<h3><a href=TraderList.php?Y=" . ($YEAR-1) . "> Traders from " . ($YEAR-1) . "</h3></a>";
  }

  dotail();
?>
