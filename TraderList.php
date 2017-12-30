<?php
  include_once("int/fest.php");

  dohead("Traders in $THISYEAR");
  include_once("int/TradeLib.php");
  global $db,$THISYEAR,$Trade_States,$Trade_State,$YEAR,$Trade_Days;

  echo "These traders will be at the Folk Festival (many more to confirm).<p>";

  echo "To become a trader see the <a href=/info/trade>trade info page</a>.  ";
  echo "Only those traders who have paid their deposits and have asked to be listed are shown here.<p>";

  $Locs = Get_Trade_Locs(1);
  $res = $db->query("SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE y.Year=$YEAR AND t.Tid=y.Tid AND t.ListMe=1 ORDER BY t.Name" );
/*  $res = $db->query("SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE y.Year=$YEAR AND t.Tid=y.Tid AND ( y.BookingState=" . $Trade_State['Deposit Paid'] .
		" OR y.BookingState=" . $TradeState['Fully Paid'] . ") AND t.ListMe=1 ORDER BY t.Name" );
*/

  echo "<div id=flex>\n";
  if ($res) while ($trad = $res->fetch_assoc()) {
    echo "<div class=article>";
    if ($trad['Website']) echo weblinksimple($trad['Website']);
    echo "<h2 class=articlettl>" . $trad['Name'] . "</h2>";
    if ($trad['Photo']) echo "<img class=articleimg src=" . $trad['Photo'] . ">";
    if ($trad['Website']) echo "</a>";
    echo "<p class=articletxt>" . $trad['GoodsDesc'];
    echo "<p>Will be trading in " . $Locs[$trad['PitchLoc0']]['Name'];
    if ($trad['PitchLoc2']) {
      echo ", " . $Locs[$trad['PitchLoc1']]['Name'] . " and " . $Locs[$trad['PitchLoc2']]['Name'];
    } else if ($trad['PitchLoc1']) {
      echo " and " . $Locs[$trad['PitchLoc1']]['Name'];
    }
    if ($trad['Days']) echo " on " . $Trade_Days[$trad['Days']];
    echo "<p>";
    echo "</div>";
  }
  
  dotail();
?>
