<?php
  include_once("fest.php");
  A_Check('Committee','Trade');

  dostaffhead("Manage Trade Types and Prices");
  global $THISYEAR;

  include_once("TradeLib.php");
  echo "<div class=content><h2>Manage Trade Types and Prices</h2>\n";
  
  $Trads = Get_Trade_Types(1);
  if (UpdateMany('TradePrices','Put_Trade_Type',$Trads,0)) $Trads=Get_Trade_Types(1);

  echo "This is for the basic types and their base prices.  Power is an add on which has its own base price.<p>\n";

  echo "The Order is for display, Index 1 MUST be for the default however, list this first.<p>\n";

  echo "Artisan Messages trigger local Artisan /Han related emails<p>";
  $coln = 0;
  echo "<form method=post>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Index</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Trade Type</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Order</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Colour</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Base Price</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Per Day</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Deposit</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Additional</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Charity Num</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Need Local Authority</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Need Insurance</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Need Risk Assessment</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Artisan Msgs</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Open</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Description</a>\n";
  echo "</thead><tbody>";
  if ($Trads) foreach($Trads as $t) {
    $i = $t['id'];
    echo "<tr><td>$i" . fm_text1("",$t,'SName',1,'','',"SName$i");
    echo fm_number1('',$t,'ListOrder','','min=0 max=1000',"ListOrder$i");
    echo fm_text1('',$t,'Colour',1,'','',"Colour$i");
    echo fm_text1('',$t,'BasePrice',0.5,'','',"BasePrice$i");
    echo "<td>" . fm_checkbox('',$t,'PerDay','',"PerDay$i");
    echo fm_number1('',$t,'Deposit','','min=0 max=1000',"Deposit$i");
    echo "<td>" . fm_checkbox("",$t,'Addition','',"Addition$i");
    echo "<td>" . fm_checkbox("",$t,'NeedCharityNum','',"NeedCharityNum$i");
    echo "<td>" . fm_checkbox("",$t,'NeedPublicHealth','',"NeedPublicHealth$i");
    echo "<td>" . fm_checkbox("",$t,'NeedInsurance','',"NeedInsurance$i");
    echo "<td>" . fm_checkbox("",$t,'NeedRiskAssess','',"NeedRiskAssess$i");
    echo "<td>" . fm_checkbox("",$t,'ArtisanMsgs','',"ArtisanMsgs$i");
    echo "<td>" . fm_checkbox("",$t,'TOpen','',"TOpen$i");
    echo "<td>" . fm_basictextarea($t,'Description',2,1,'',"Description$i");
    echo "\n";
  }
  echo "<tr><td><td><input type=text size=16 name=SName0 >";
  echo "<td><input type=number min=0 max=1000 name=ListOrder0>";
  echo "<td><input type=text size=16 name=Colour0>";
  echo "<td><input type=text size=8 name=BasePrice0>";
  echo "<td><input type=checkbox name=PerDay0>";
  echo "<td><input type=number min=0 max=1000 name=Deposit0>";
  echo "<td><input type=checkbox name=Addition0>";
  echo "<td><input type=checkbox name=NeedCharityNum0>";
  echo "<td><input type=checkbox name=NeedPublicHealth0>";
  echo "<td><input type=checkbox name=NeedInsurance0>";
  echo "<td><input type=checkbox name=NeedRiskAssess0>";
  echo "<td><input type=checkbox name=ArtisanMsgs0>";
  echo "<td><input type=checkbox name=TOpen0>";
  echo "<td><textarea name=Description0 cols=40></textarea>";
  echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
