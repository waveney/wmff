<?php
  include_once("fest.php");
  A_Check('Committee','Venues');

  dostaffhead("Manage Contact Categories");
  global $PLANYEAR;

  include_once("TradeLib.php");
  include_once("InvoiceLib.php");
  include_once("ContactLib.php");
  
  
  echo "<div class='content'><h2>Manage Contact Categories</h2>\n";
  
  global $ContCatState, $ContCatColours;
  
  $Teams=Get_ContactCats(1);
//var_dump($Teams);

  if (UpdateMany('ContactCats','Put_ContactCat',$Teams,0)) $Teams=Get_ContactCats(1);

  $coln = 0;

// function fm_radio($Desc,&$defn,&$data,$field,$extra='',$tabs=1,$extra2='',$field2='',$colours=0,$multi=0,$extra3='',$extra4='') {
  echo "<form method=post action=ContactCats>";
  echo "<div class=tablecont><table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Index</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>State</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Rel Order</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Description</a>\n";
  echo "</thead><tbody>";
  foreach($Teams as $t) {
    $i = $t['id'];
    echo "<tr><td>$i" . fm_text1("",$t,'SN',1,'','',"SN$i");
    echo "<td>" . fm_radio('',$ContCatState,$t,"OpenState",'',0,'',"OpenState$i",$ContCatColours);
    echo fm_text1('',$t,'Email',1,'','',"Email$i");
    echo fm_text1('',$t,'RelOrder',1,'','',"RelOrder$i");
    echo fm_text1('',$t,'Description',4,'','',"Description$i");
    echo "\n";
  }
  $t = [];
  echo "<tr><td><td><input type=text name=SN0 >";
    echo "<td>" . fm_radio('',$ContCatState,$t,"OpenState",'',0,'',"OpenState0",$ContCatColours);
    echo fm_text1('',$t,'Email',1,'','',"Email0");
    echo fm_text1('',$t,'RelOrder',1,'','',"RelOrder0");
    echo fm_text1('',$t,'Description',4,'','',"Description0");
  echo "</table></div>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
