<?php
  include_once("fest.php");
  A_Check('Committee','News');

  dostaffhead("Manage Front Page Articles");
  global $Importance,$SHOWYEAR,$ArticleFormats;

  include_once("NewsLib.php");
  include_once("Uploading.php");
  
//var_dump($_POST);

  $Arts = Get_All_Articles(1);
  echo "<button class='floatright FullD' onclick=\"($('.FullD').toggle())\">All Articles</button><button class='floatright FullD' hidden onclick=\"($('.FullD').toggle())\">Current/Future Articles Only</button> ";

  echo "<H2>Articles</h2>
The Articles in use will be displayed on the top page and any other pages that use the feature.  Sorting by Importance then random.<p>

Warning: Unless there are at least 6 Articles on a page (preferably more) the formatting will look poor.<p>

Titles starting with @ have special derived content, which is generated on the fly.<p>

Click on the title to edit.<p>";
  $coln = 0;
  $now = time();
  echo "<div class=tablecont><table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Index</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Title</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Start of Text</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Importance</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Order</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Thumbnail</a>\n"; 
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>When</a>\n"; 
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Usage</a>\n";
  echo "</thead><tbody>";
  if ($Arts) foreach($Arts as $t) {
    $i = $t['id'];
    echo "<tr" . (($t['StopDate']> 0 && $t['StopDate'] <$now)?" class=FullD hidden" : "" ) . ">";
    echo "<td><a href=AddArticle.php?id=$i>" . $t['id'] . "</a>";
    echo "<td><a href=AddArticle.php?id=$i>" . $t['SN'] . "</a>";
    preg_match('/(.*)[ \n].*?$/',substr($t['Text'],0,80),$mtch);
    
    echo "<td>" . (isset($mtch[1]) ? htmlspec($mtch[1]) : "") ;
    echo "<td>" . $Importance[$t['Importance']];
    echo "<td>" . $t['RelOrder'];
    echo "<td>" . ($t['Image']?("<img src=" . $t['Image'] . " height=80>") : "");
    echo "<td>" . ($t['StartDate'] ? date('j/m/y',$t['StartDate']) : "" ) . " - " . ($t['StopDate'] ? date('j/m/y',$t['StopDate']) : "" ) ;
    echo "<td>" . $t['UsedOn'] . "\n";
  }

  echo "</table></div>\n";
  
  echo "<h2><a href=AddArticle.php>Add An Article</a>, <a href=HomeFuture.php?F=7>Top Page in a Week</a></h2>\n";

  dotail();

?>
